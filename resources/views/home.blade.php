@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable({
                "iDisplayLength": 50
            });

        });
    </script>
@stop
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-left">
                            <i class="mdi mdi-poll-box text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Kriteria</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $kriteria }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Kriteria
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-left">
                            <i class="mdi mdi-receipt text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Calon Anggota</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $anggota }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Total Calon Anggota
                    </p>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title">Data Hasil Ranking</h4>

                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>
                                        Ranking
                                    </th>
                                    <th>
                                        Nama
                                    </th>
                                    <th>
                                        Nilai
                                    </th>
                                </tr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
