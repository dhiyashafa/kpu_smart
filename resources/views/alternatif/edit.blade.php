@section('js')

    <script type="text/javascript">
        $(document).ready(function() {
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

                                <div class="form-group{{ $errors->has('no_pendaftaran') ? ' has-error' : '' }}">
                                <label for="nama" class="col-md-4 control-label">No. Pendaftaran</label>
                                <div class="col-md-6">
                                    <input id="no_pendaftaran" type="text" class="form-control" name="no_pendaftaran"
                                        value="{{ $alternatifs->no_pendaftaran }}" required>
                                    @if ($errors->has('no_pendaftaran'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('no_pendaftaran') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> 

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
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-4 control-label">Email</label>
                                    <div class="col-md-6">
                                        <input id="email" type="text" class="form-control" name="email"
                                            value="{{ $alternatifs->email }}" required>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('kelamin') ? ' has-error' : '' }}">
                                    <label for="kelamin" class="col-md-4 control-label">Jenis Kelamin</label>
                                    <div class="col-md-6">
                                        <select name="kelamin" id="kelamin" class="form-control">
                                            <option value="Perempuan" {{ $alternatifs->kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                Perempuan</option>
                                            <option value="Laki-Laki" {{ $alternatifs->kelamin == 'Laki-Laki' ? 'selected' : '' }}>Laki
                                                - Laki</option>
                                        </select>
                                        {{-- <input id="email" type="text" class="form-control" name="email"
                                        value="{{ old('email') }}" required> --}}
                                        @if ($errors->has('kelamin'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('kelamin') }}</strong>
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
                                <a href="{{ route('alternatif.index') }}" class="btn btn-light pull-right">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
