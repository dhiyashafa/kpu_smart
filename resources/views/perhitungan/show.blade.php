@section('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $(".users").select2();
        });
    </script>

@stop

@extends('layouts.app')

@section('content')

        <div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
                <div class="row flex-grow">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Detail <b>Perhitungan</b> </h4>
                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Nama Anggota</label>
                                    <div class="col-md-6">
                                        <input id="tes_tulis" type="text" class="form-control"
                                            name="tes_tulis" value="{{$param['perhitungan']->nama}}" required readonly>
                                        @if ($errors->has('nama'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @foreach ($param['subkreteria'] as $key =>$value)
                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">{{$value->nama}}</label>
                                    <div class="col-md-6">
                                        <input id="tes_tulis" type="number" onkeyup="max100(this)" class="form-control"
                                            name="tes_tulis" value="{{$value->nilai}}" required readonly>

                                        @if ($errors->has('tes_tulis'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tes_tulis') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach

                              
                                <a href="{{ route('perhitungan.index') }}" class="btn btn-light pull-right">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
 
 
@endsection
