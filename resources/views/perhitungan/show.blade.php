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
                                <h4 class="card-title">Detail <b>{{ $param['perhitungan']->nama }}</b> </h4>
                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Nama Anggota</label>
                                    <div class="col-md-6">
                                        <select name="anggota" id="anggota" class="form-control" required="" readonly>
                                            @foreach ($param['at'] as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{ $value->id == $param['perhitungan']->alternatifs_id ? 'selected' : '' }}>
                                                    {{ $value->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('nama'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group{{ $errors->has('Kel_data') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Kelengkapan Data</label>
                                    <div class="col-md-6">
                                        <select name="Kel_data" id="Kel_data" class="form-control" required="" readonly>
                                            @foreach ($param['kd'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $key == $param['kd_value'] ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('Kel_data'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('Kel_data') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Tes Tulis</label>
                                    <div class="col-md-6">
                                        <input id="tes_tulis" type="number" onkeyup="max100(this)" class="form-control"
                                            name="tes_tulis" value="{{ old('tes_tulis') }}" required readonly>

                                        @if ($errors->has('tes_tulis'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tes_tulis') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                                    <label for="weight" class="col-md-4 control-label">Tes Wawancara</label>
                                    <div class="col-md-6">
                                        {{-- <select name="tes_wawancara" id="tes_wawancara" class="form-control">
                                            @foreach ($param['tw'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $key == $param['tw_value'] ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                        <input id="tes_tulis" type="number" onkeyup="max100(this)" class="form-control"
                                            name="tes_wawancara" value="{{ old('tes_wawancara') }}" required readonly>
                                        @if ($errors->has('tes_tulis'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tes_tulis') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Tanggapan Masyarakat</label>
                                    <div class="col-md-6">
                                        <select name="tang_masya" id="tang_masya" class="form-control" required="" readonly>
                                            @foreach ($param['tm'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $key == $param['tm_value'] ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('tang_masya'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tang_masya') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                              
                                <a href="{{ route('perhitungan.index') }}" class="btn btn-light pull-right">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
 
 
@endsection
