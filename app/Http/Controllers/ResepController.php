<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\Obatalkes;
use App\Models\Signa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ResepController extends Controller
{
    public function index()
    {
        $reseps = Resep::all();
        // dd($reseps);
        return view('resep.index', compact('reseps'));
    }
    public function show($id)
    {
        $resep = Resep::with('details.obatalkes', 'details.signa')->findOrFail($id);
        return view('resep.show', compact('resep'));
    }

    public function cetak($id)
    {
        $resep = Resep::with('details.obatalkes', 'details.signa')->findOrFail($id);

        $pdf = Pdf::loadView('resep.cetak', ['resep' => $resep]);

        return $pdf->stream('resep-' . $resep->id . '.pdf');
    }


    public function create()
    {
        return view('resep.create');
    }

    public function getObat(Request $request)
    {
        $search = $request->input('search');

        $obatalkes = Obatalkes::where('obatalkes_nama', 'like', '%' . $search . '%')
            ->limit(20)
            ->get(['obatalkes_id', 'obatalkes_nama', 'stok']);


        return response()->json($obatalkes);
    }

    public function getSigna(Request $request)
    {
        $search = $request->input('search');

        $signa = Signa::where('signa_nama', 'like', '%' . $search . '%')
            ->limit(10)
            ->get(['signa_id', 'signa_nama']);

        return response()->json($signa);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'resep_details.*.obatalkes_id' => 'required|exists:obatalkes_m,obatalkes_id',
                'resep_details.*.jumlah' => 'required|integer|min:1',
                'resep_details.*.signa_id' => 'required|exists:signa_m,signa_id',
            ]);
            $resep = new Resep();
            $resep->resep_tipe = $request->resep_tipe;
            $resep->resep_tanggal = $request->resep_tanggal;
            $resep->save();

            foreach ($request->resep_details as $detail) {

                $obat = Obatalkes::find($detail['obatalkes_id']);

                if ($obat && $detail['jumlah'] > $obat->stok) {
                    return redirect()->back()->withErrors(['jumlah' => 'Jumlah obat melebihi stok yang tersedia.'])->withInput();
                }
                $resep->details()->create($detail);

                $obat->stok -= $detail['jumlah'];
                $obat->save();
            }
            Log::info('Resep berhasil disimpan', ['id' => $resep->id]);


            return redirect()->route('resep.index')->with('success', 'Non racikan resep berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan resep', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan.']);
        }
    }

    public function createRacikan()
    {
        return view('resep.racikan.create');
    }

    public function storeRacikan(Request $request)
    {
        try {

            // Validasi form
            $request->validate([
                'resep_details' => 'required|array|min:1',
                'resep_details.*.obatalkes_id' => 'required|exists:obatalkes_m,obatalkes_id',
                'resep_details.*.jumlah' => 'required|numeric|min:1',
                'nama_resep' => 'required|string|max:255',
            ], [
                'resep_details.required' => 'Minimal satu item racikan harus diisi.',
                'resep_details.*.obatalkes_id.required' => 'Obat tidak boleh kosong.',
                'resep_details.*.jumlah.required' => 'Jumlah tidak boleh kosong.',
                'jumlah.required' => 'Jumlah tidak boleh kosong.',
                'nama_resep.required' => 'Nama resep harus diisi.',
            ]);


            $isEmpty = true;
            foreach ($request->resep_details as $detail) {
                if (!empty($detail['obatalkes_id']) && !empty($detail['jumlah'])) {
                    $isEmpty = false;
                    break;
                }
            }

            if ($isEmpty) {
                return redirect()->back()->withErrors(['resep_details' => 'Form racikan tidak boleh kosong.'])->withInput();
            }

            $resep = new Resep();
            $resep->resep_tipe = $request->resep_tipe;
            $resep->resep_tanggal = $request->resep_tanggal;
            $resep->save();
            // dd($resep);

            foreach ($request->resep_details as $detail) {
                $obat = Obatalkes::find($detail['obatalkes_id']);

                if ($obat && $detail['jumlah'] > $obat->stok) {
                    return redirect()->back()
                        ->withErrors(['jumlah' => 'Jumlah obat melebihi stok yang tersedia.'])
                        ->withInput();
                }

                $detail['nama_resep'] = $request->nama_resep;
                $resep->details()->create($detail);

                $obat->stok -= $detail['jumlah'];
                $obat->save();
            }

            Log::info('Racikan resep berhasil disimpan', ['id' => $resep->id]);

            return redirect()->route('resep.index')->with('success', 'Racikan resep berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan racikan resep', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan racikan.']);
        }
    }
}
