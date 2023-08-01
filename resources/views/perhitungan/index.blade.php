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

        <div class="col-lg-2">
            <a href="{{ route('perhitungan.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i>
                Add {{ $param['title'] }}</a>
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
                            <h4 class="card-title pull-left">List {{ $param['title'] }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <form action="{{ route('perhitungan.delete_all') }}"class="pull-right" method="post">
                                {{ csrf_field() }}
                                {{ method_field('post') }}
                                <a href="{{ route('perhitungan.semua','0') }}" class="btn btn-success">Lihat Semua Perhitungan</a>
                                
                                <button type="submit" class="btn btn-danger">Hapus Semua</button>
                                {{-- <button type="submit" class="btn btn-danger">PDF</button> --}}
                                <a href="{{ route('perhitungan.pdf') }}" target="blank" class="btn btn-outline-info">PDF</a>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>
                                        Rank
                                    </th>
                                    <th>
                                        Nama Anggota
                                    </th>
                                    <th>
                                        hasil
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($param['perhitungan'] as $key => $perhitungans)
                                    <tr>
                                        <td>{{ $perhitungans['ranking'] }}</td>
                                        <td>{{ $perhitungans['perhitungan']->nama }}</td>
                                        <td>{{ $perhitungans['perhitungan']->hasil }}</td>
                                        <td>
                                            
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn btn-success dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                                    <a class="dropdown-item"
                                                        href="{{ route('perhitungan.edit', $perhitungans['perhitungan']->id) }}">Edit</a>
                                                        <a class="dropdown-item" 
                                                        href="{{ route('perhitungan.show',$perhitungans['perhitungan']->id) }}">Detail</a>
                                                    <form
                                                        action="{{ route('perhitungan.destroy', $perhitungans['perhitungan']->id) }}"
                                                        class="pull-left" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button class="dropdown-item"
                                                            onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                                                            Delete
                                                        </button>
                                                        
                                                    </form>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <!-- {{-- {!! $kriterias->links() !!} --}} -->
                </div>
            </div>
        </div>
    </div>
@endsection
