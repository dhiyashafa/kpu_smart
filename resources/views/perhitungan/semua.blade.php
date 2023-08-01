
@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-lg-2">
            <a href="{{ route('perhitungan.index') }}" class="btn btn-light btn-rounded btn-fw"> <i
                    class="fa fa-arrow-left"></i> Back {{ $param['title'] }}</a>
            {{-- <a href="{{ route('perhitungan.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i>
                Add {{ $param['title'] }}</a> --}}
        </div>
        <div class="col-lg-12">
            @if (Session::has('message'))
                <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">
                    {{ Session::get('message') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title pull-left">Nilai Setiap Kriteria {{ $param['title'] }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                   
                                    <th>
                                        Nama Anggota
                                    </th>
                                    @foreach ($param['kriteria'] as $key => $subkreteria)
                                        <th>
                                            {{ $subkreteria->nama }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($param['sub_kriteria'] as $key => $perhitungans)
                                    <tr>
                                        
                                        <td>{{ $perhitungans['perhitungan']->nama }}</td>
                                        @foreach ($perhitungans['perhitungan']->subkreteria as $key => $subkreteria)
                                            <td>
                                                {{ $subkreteria }}
                                            </td>
                                        @endforeach
                                        {{-- @foreach ($param['kriteria'] as $key => $subkreteria)
                                            @if ($subkreteria->id == $perhitungans['perhitungan']->kriterias_id)
                                                <td>
                                                    {{ $subkreteria->id == $perhitungans['perhitungan']->kriterias_id ?? $perhitungans['perhitungan']->nilai }}
                                                </td>
                                            @endif
                                        @endforeach --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title pull-left">Menentukan nilai utility tiap kriteria</h4>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
        
                                    <th>
                                        Nama Anggota
                                    </th>
                                    @foreach ($param['kriteria'] as $key => $subkreteria)
                                        <th>
                                            {{ $subkreteria->nama }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($param['sub_kriteria'] as $key => $perhitungans)
                                    @if ($param['id'] == '0' ||$param['id'] == $perhitungans['perhitungan']->id)
                                        <tr>
                                            <td>{{ $perhitungans['perhitungan']->nama }}</td>
                                            @foreach ($perhitungans['perhitungan']->subperhitungans_na as $key => $subkreteria)
                                                <td>
                                                    {{ $subkreteria }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title pull-left">Menentukan hasil akhir</h4>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                   
                                    <th>
                                        Nama Anggota
                                    </th>
                                    <th>
                                        Hasil Akhir
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($param['sub_kriteria'] as $key => $perhitungans)
                                    @if ($param['id'] == '0' ||$param['id'] == $perhitungans['perhitungan']->id)
                                        <tr>
                                            
                                            <td>{{ $perhitungans['perhitungan']->nama }}</td>
                                            <td>{{ $perhitungans['perhitungan']->hasil }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
