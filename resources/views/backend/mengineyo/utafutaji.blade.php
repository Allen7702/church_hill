@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{strtoupper($title)}}</h4>
        </div>
        <div class="card-body">
            <table class="table w-100" id="table">
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Mwanajumuiya</th>
                    <th>Jumuiya</th>
                    <th>Mawasiliano</th>
                    <th>Taaluma</th>
                    <th>Cheo</th>
                    <th>Namba utambulisho</th>
                    <th>Kitendo</th>
                </thead>
                <tbody>
                    @foreach ($data_tafuta as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->jina_kamili}}</td>
                            <td>{{$item->jina_la_jumuiya}}</td>
                            <td>{{$item->mawasiliano}}</td>
                            <td>{{$item->taaluma}}</td>
                            <td>{{$item->cheo_familia}}</td>
                            <td>{{$item->namba_utambulisho}}</td>
                            <td>
                                <a href="{{url('mwanafamilia',['id'=>$item->id])}}"><button class="btn btn-info btn-sm">Angalia</button></a>
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
                responsive: true,
                stateSave: true,
            })
        })
    </script>
@endsection