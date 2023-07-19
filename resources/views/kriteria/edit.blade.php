@section('js')

<script type="text/javascript">
    $(document).ready(function () {
        $(".users").select2();
    });

</script>

@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('kriteria.update', $kriterias->id) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit <b>{{$kriterias->nama}}</b> </h4>
                            <form class="forms-sample">
                                <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                    <label for="nama" class="col-md-4 control-label">Nama Kriteria</label>
                                    <div class="col-md-6">
                                        <input id="nama" type="text" class="form-control" name="nama"
                                            value="{{ $kriterias->nama }}" required>
                                        @if ($errors->has('nama'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nama') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                                    <label for="weight" class="col-md-4 control-label">Bobot</label>
                                    <div class="col-md-6">
                                        <input id="weight" type="text" class="form-control" name="weight"
                                            value="{{ $kriterias->weight }}" required>
                                        @if ($errors->has('weight'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('weight') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="submit">
                                    Update
                                </button>
                                <a href="{{route('kriteria.index')}}" class="btn btn-light pull-right">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
