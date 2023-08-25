<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
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
        <h1>{{ $title }}</h1>
    </center>

    @if (empty($perhitungan))
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
                    @if ($perhitungan[0]['perhitungan']->deleted_at)
                    <th>Tanggal Delete</th>
                    @endif
            </thead>
            <tbody>
                @foreach ($perhitungan as $key => $perhitungans)
                    <tr>
                        <td>{{ $perhitungans['ranking'] }}</td>
                        <td>{{ $perhitungans['perhitungan']->nama }}</td>
                        <td>{{ $perhitungans['perhitungan']->hasil }}</td>
                        @if ($perhitungans['perhitungan']->deleted_at)
                            <td>{{ $perhitungans['perhitungan']->deleted_at }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


</body>

</html>
