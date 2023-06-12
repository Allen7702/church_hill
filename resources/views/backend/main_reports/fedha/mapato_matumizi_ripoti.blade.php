@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- ================== THE HEADER PART =========== --}}
                <div class="card-header">
                    <div class="row text-right">
                        <div class="col-md-12">
                                <a href="{{route('fedha_ripoti_generate_print',['pdf',$kuanzia,$ukomo])}}"><button
                                        class="btn btn-info btn-sm mr-2">PDF</button></a>
                                <a href="{{route('fedha_ripoti_generate_print',['excel',$kuanzia,$ukomo])}}"><button
                                        class="btn btn-info btn-sm mr-0">EXCEL</button></a>
                            <a href="{{url()->previous()}}"><button class="btn btn-warning btn-sm">Rudi</button></a>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        @include('layouts.header')    
                    </div>
                </div>

                <div class="card-body">
                    <table class="table_other table">

                        <thead class="bg-light">
                            <th colspan="2" class="text-info">A: Mapato ya kawaida</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="55%">Aina ya pato</th>
                                <th width="*">Kiasi (TZS)</th>
                            </tr>
                            
                                <tr>
                                    <td>Sadaka za misa</td>
                                    <td><a href="{{route('mapato_ya_kawaida',['sadaka_za_misa',$kuanzia,$ukomo])}}">{{number_format($data_sadaka,2)}}</a></td>
                                </tr>
                        
    
                            <tr>
                                <td>Sadaka za jumuiya</td>
                                <td><a href="{{route('mapato_ya_kawaida',['sadaka_za_jumuiya',$kuanzia,$ukomo])}}">{{number_format($sadaka_jumuiya,2)}}</a></td>
                            </tr>
    
                            <tr>
                                <td>Mapato ya Zaka</td>
                                <td><a href="{{route('mapato_ya_kawaida',['mapato_ya_zaka',$kuanzia,$ukomo])}}">{{number_format($zaka_total,2)}}</a></td>
                            </tr>
    
                            <tr style="font-weight: bold;">
                                <td>Jumla ya mapato ya kawaida</td>
                                <td><a href="{{route('mapato_ya_kawaida',['jumla_mapato_ya_kawaida',$kuanzia,$ukomo])}}">{{number_format($mapato_kawaida_total,2)}}</a></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            <tr class="bg-light">
                                <th colspan="2" class="text-info">B: Mapato ya maendeleo</th>
                            </tr>
                            
                            <tr style="font-weight: bold;">
                                <td>Aina ya mchango</td>
                                <td>Kiasi (TZS)</td>
                            </tr>
                            @foreach ($data_mapato_maendeleo as $mapato)
                                <tr>
                                    <td>{{$mapato->aina_ya_mchango}}</td>
                                    <td><a href="{{route('mapato_ya_maendeleo',[space_to_underscore(strtolower($mapato->aina_ya_mchango)),'null',$kuanzia,$ukomo])}}" >{{number_format($mapato->kiasi,2)}}</a></td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold;">
                                <td>Jumla ya mapato maendeleo</td>
                                <td><a href="{{route('mapato_ya_maendeleo',['jumla_mapato_maendeleo','null',$kuanzia,$ukomo])}}" >{{number_format($mapato_michango_total,2)}}</a></td>
                            </tr>
                            <tr style="background-color:#F8F9FA;font-weight:bold;" class="text-primary">
                                <td>Jumla ya mapato yote (A+B)</td>
                                <td><a href="{{route('mapato_ya_maendeleo',['jumla_ya_mapato_yote','null',$kuanzia,$ukomo])}}">{{number_format($mapato_yote_total,2)}}</a></td>
                            </tr>
    
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
    
                            <tr class="bg-light text-info">
                                <th width="55%">C.Matumizi ya kawaida</th>
                                <th width="*">Kiasi (TZS)</th>
                            </tr>
    
                            @foreach ($data_kawaida as $item)
                            <tr>
                                <td>{{$item->aina_ya_matumizi}}</td>
                                <td><a href="{{route('matumizi',['kawaida',$item->aina_ya_matumizi,'null',$kuanzia,$ukomo])}}" >{{number_format($item->kiasi,2)}}</a></td>
                            </tr>
                            @endforeach

                            <tr class="text-primary" style="font-weight: bold;">
                                <td>Jumla</td>
                                <td><a href="{{route('matumizi',['kawaida','jumla','null',$kuanzia,$ukomo])}}" >{{number_format($kawaida_total,2)}}</a></td>
                            </tr>

                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            <tr class="bg-light text-info">
                                <th width="55%">D.Matumizi maendeleo</th>
                                <th width="*">Kiasi (TZS)</th>
                            </tr>
    
                            @foreach ($data_maendeleo as $item)
                            <tr>
                                <td>{{$item->aina_ya_matumizi}}</td>
                                <td><a href="{{route('matumizi',['maendeleo',$item->aina_ya_matumizi,'null',$kuanzia,$ukomo])}}" >{{number_format($item->kiasi,2)}}</a></td>
                            </tr>
                            @endforeach

                            <tr style="font-weight: bold;">
                                <td>Jumla</td>
                                <td><a href="{{route('matumizi',['maendeleo','jumla','null',$kuanzia,$ukomo])}}" >{{number_format($maendeleo_total,2)}}</a></td>
                            </tr>

                            <tr style="font-weight: bold;" class="text-primary">
                                <td>Jumla ya matumizi (C+D)</td>
                                <td><a href="{{route('matumizi',['null','matumizi_jumla','null',$kuanzia,$ukomo])}}" >{{number_format($matumizi_total,2)}}</a></td>
                            </tr>

                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            @if($salio < 0)
                            <tr style="font-weight:bold;font-style:italic;" class="text-danger">
                                <td>SALIO (Mapato-Matumizi)</td>
                                <td>{{number_format($salio,2)}}</td>
                            </tr>
                            @else
                            <tr style="font-weight:bold;font-style:italic;" class="text-primary">
                                <td>SALIO (Mapato-Matumizi)</td>
                                <td><a href="{{route('salio',['null',$kuanzia,$ukomo])}}">{{number_format($salio,2)}}</a></td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
@endsection