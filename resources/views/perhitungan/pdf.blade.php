<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penilaian</title>
    <style>
        table,
        th,
        td {
            padding: 10px;
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>


<body>
    <center>
        <h1>Laporan Penilaian</h1>
    </center>

    @if ($perhitungan->isEmpty())
        <center>
            <h3>Data Tidak Ditemukan</h3>
        </center>
    @else
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Nama Anggota</th>
                    <th>Nilai</th>
            </thead>
            <tbody>
                @foreach ($perhitungan as $key => $perhitungans)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $perhitungans->nama }}</td>
                        <td>{{ $perhitungans->hasil }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


</body>

</html>
