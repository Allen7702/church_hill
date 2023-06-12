@extends('layouts.master')
@section('content')

    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-bold">{{strtoupper($title)}}</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{url('familia_zote')}}"><button class="btn btn-info btn-sm mr-1">Ongeza familia</button></a>
                    <a href="{{url('familia_orodha_kijumuiya_pdf')}}"><button class="btn btn-info btn-sm mr-1">PDF</button></a>
                    <a href="{{url('familia_orodha_kijumuiya_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table" id="table">
                
                <thead>
                    <th>S/N</th>
                    <th>Jumuiya</th>
                    <th>Kanda</th>
                    <th>Idadi ya familia</th>
                    <th>Kitendo</th>
                </thead>

                <tbody>
                    @foreach ($data_jumuiya as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->jina_la_jumuiya}}</td>
                            <td>{{$item->jina_la_kanda}}</td>
                            <td>{{$item->idadi_ya_familia}}</td>
                            <td>
                                <a href="{{url('familia/jumuiya_husika',['id'=>$item->jina_la_jumuiya])}}"><button class="btn btn-sm btn-info mr-1">Angalia</button></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        $("document").ready(function(){
            $('#table').DataTable({
                stateSave: true,
                responsive: true,
            })
        })
    </script>

@endsection