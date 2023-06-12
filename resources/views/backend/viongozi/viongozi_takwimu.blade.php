@extends('layouts.master')
@section('content')
    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-md-7">
                    <h4>{{strtoupper($title)}}</h4>
                </div>
                <div class="col-md-5 text-right">
                    <a href="{{url('viongozi_zaidi')}}"><button class="btn btn-info btn-sm mr-2">Ongeza kiongozi</button></a>
                    <a href="{{url('orodha_idadi_viongozi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                    <a href="{{url('orodha_idadi_viongozi_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table w-100" id="table">
                
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Aina ya kamati</th>
                    <th>Idadi ya viongozi</th>
                    <th>Kitendo</th>
                </thead>

                <tfoot>
                    <th colspan="2"> Jumla</th>
                    <th colspan="2" >{{number_format($viongozi_total)}}</th>
                </tfoot>

                <tbody>
                    {{-- @foreach ($data_viongozi as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->cheo}}</td>
                            <td>{{$item->idadi}}</td>
                            <td>
                                <a href="{{url('viongozi_husika',['id'=>$item->cheo])}}"><button class="btn btn-info btn-sm">Angalia</button></a>
                            </td>
                        </tr>
                    @endforeach --}}
                    <tr>
                        <td>1</td>
                        <td>Kamati tendaji parokia</td>
                        <td>{{$kamati_tendaji_parokia}}</td>
                        <td><a href="{{url('viongozi_kamati',['aina_ya_kamati'=>'parokia'])}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Kamati tendaji jumuiya</td>
                        <td>{{$kamati_tendaji_jumuiya}}</td>
                        <td><a href="{{url('viongozi_kamati',['aina_ya_kamati'=>'jumuiya'])}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Kamati tendaji {{$sahihisha_kanda->name}}</td>
                        <td>{{$kamati_tendaji_kanda}}</td>
                        <td><a href="{{url('viongozi_kamati',['aina_ya_kamati'=>$sahihisha_kanda->name])}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Kamati tendaji Vyama vya kitume</td>
                        <td>{{$kamati_vyama_kitume}}</td>
                        <td><a href="{{url('viongozi_kamati',['aina_ya_kamati'=>'vyama_vya_kitume'])}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Wengineo</td>
                        <td>{{$kamati_tendaji_wengineo}}</td>
                        <td><a href="{{url('viongozi_kamati',['aina_ya_kamati'=>'wengineo'])}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                    </tr>
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