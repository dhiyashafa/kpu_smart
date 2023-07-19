@section('js')

<script type="text/javascript">
    $(document).ready(function() {
        $(".users").select2();
    });
</script>

@stop

@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('perhitungan.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add {{$param['title']}}</h4>

                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">Nama Anggota</label>
                                <div class="col-md-6">
                                    <!-- <input id="nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required> -->
                                    <select name="anggota" id="anggota" class="form-control">
                                        @foreach($param['at'] as $key =>$value)
                                        <option value="{{$value->id}}">{{$value->nama}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">Kelengkapan Data</label>
                                <div class="col-md-6">
                                    <select name="Kel_data" id="Kel_data" class="form-control">
                                        @foreach($param['kd'] as $key =>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">Tes Tulis</label>
                                <div class="col-md-6">
                                    <input id="tes_tulis" type="number" onkeyup="max100(this)" class="form-control" name="tes_tulis" value="{{ old('nama') }}" required>

                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                                <label for="weight" class="col-md-4 control-label">Tes Wawancara</label>
                                <div class="col-md-6">
                                    <!-- <input id="weight" type="text" class="form-control" name="weight" value="{{ old('weight') }}" required> -->
                                    <select name="tes_wawancara" id="tes_wawancara" class="form-control">
                                        @foreach($param['tw'] as $key =>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('weight'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('weight') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">Tanggapan Masyarakat</label>
                                <div class="col-md-6">
                                    <!-- <input id="nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required> -->
                                    <select name="tang_masya" id="tang_masya" class="form-control">
                                        @foreach($param['tm'] as $key =>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-danger">
                                Reset
                            </button>
                            <a href="{{route('perhitungan.index')}}" class="btn btn-light pull-right">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
<script>
    function max100(val) {
        if (Number(val.value) > 100) {
            val.value = 100
        }
    }
</script>
@endsection