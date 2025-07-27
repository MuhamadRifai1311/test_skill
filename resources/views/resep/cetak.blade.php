<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px; font-size: 24px; font-weight: bold;">
        <h2>Detail Resep</h2>
    </div>
    <p style="font-size: 15px">Tipe Resep : {{ $resep->resep_tipe }}</p>
    <p style="font-size: 15px">Tanggal : {{ \Carbon\Carbon::parse($resep->resep_tanggal)->format('d-M-y') }}</p>

    <table>
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
</body>

</html>
