@section('js')

<script type="text/javascript">
    $(document).ready(function () {
        $(".users").select2();
    });

</script>
@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('alternatif.update', $alternatifs->id) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Calon Anggota</h4>

                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">Nama</label>
                                <div class="col-md-6">
                                    <input id="nama" type="text" class="form-control" name="nama"
                                        value="{{ $alternatifs->nama }}" required>
                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                                <label for="alamat" class="col-md-4 control-label">Alamat</label>
                                <div class="col-md-6">
                                    <input id="alamat" type="text" class="form-control" name="alamat"
                                        value="{{ $alternatifs->alamat }}" required>
                                    @if ($errors->has('alamat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('alamat') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('nomer') ? ' has-error' : '' }}">
                                <label for="nomer" class="col-md-4 control-label">Nomer</label>
                                <div class="col-md-6">
                                    <input id="nomer" type="text" class="form-control" name="nomer"
                                        value="{{ $alternatifs->nomer }}" required>
                                    @if ($errors->has('nomer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nomer') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit">
                                Ubah
                            </button>
                            <button type="reset" class="btn btn-danger">
                                Reset
                            </button>
                            <a href="{{route('alternatif.index')}}" class="btn btn-light pull-right">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
