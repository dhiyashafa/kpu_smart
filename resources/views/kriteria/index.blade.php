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
            <a href="{{ route('kriteria.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i>
                Add Kriteria</a>
        </div>
        <div class="col-lg-12">
            @if (Session::has('message'))
                <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">
                    {{ Session::get('message') }}</div>
            @endif
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title pull-left">List Kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>
                                        No.
                                    </th>
                                    <th>
                                        Nama Kriteria
                                    </th>
                                    <th>
                                        Bobot
                                    </th>
                                    <th>
                                        Eigen
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $key => $kriterias)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}

                                        </td>
                                        <td>
                                            {{ $kriterias->nama }}
                                        </td>
                                        <td>
                                            {{ $kriterias->weight }}
                                        </td>
                                        <td>
                                            {{ $kriterias->eigen }}
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn btn-success dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                                    <a class="dropdown-item"
                                                        href="{{ route('kriteria.edit', $kriterias->id) }}">Edit</a>
                                                    <form action="{{ route('kriteria.destroy', $kriterias->id) }}"
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
                                <tr>
                                    <td colspan="2" class="text-center">
                                        Total
                                    </td>
                                    <td class="text-center">
                                        {{ $kriteria->sum('weight') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $kriteria->sum('eigen') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{--  {!! $kriterias->links() !!} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
