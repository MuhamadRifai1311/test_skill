<x-app-layout>
    <div class="mx-auto p-4">
        <div>
            <h2 class="text-2xl font-bold">Detail Resep</h2>
        </div>
        <div>
            <p class="text-lg">Tipe Resep: {{ $resep->resep_tipe }}</p>
            <p class="text-lg">Tanggal: {{ \Carbon\Carbon::parse($resep->resep_tanggal)->format('d-M-y') }}</p>
            <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            @if ($resep->details->isNotEmpty() && $resep->details->first()->nama_resep)
                                <th>Nama Resep</th>
                            @endif

                            <th>Obatalkes</th>
                            <th>Signa</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resep->details as $details)
                            <tr>
                                @if ($details->nama_resep == true)
                                    <td>{{ $details->nama_resep }}</td>
                                @endif
                                <td>{{ $details->obatalkes ? $details->obatalkes->obatalkes_nama : 'N/A' }}</td>
                                <td>{{ $details->signa ? $details->signa->signa_nama : 'N/A' }}</td>
                                <td>{{ $details->jumlah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <div>
                    <a href="{{ route('resep.index') }}">
                        <button class="btn btn-warning">Kembali ke Daftar Resep</button>
                    </a>
                </div>
                <div>
                    <a href="{{ route('resep.cetak', $resep->id) }}">
                        <button class="btn btn-info">Cetak</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
