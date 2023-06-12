@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-info mr-2">CHAPISHA</button>
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

                        @foreach ($data_sadaka as $item)
                            <tr>
                                <td>{{$item->misa}}</td>
                                <td>{{number_format($item->kiasi,2)}}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td>Sadaka za jumuiya</td>
                            <td>{{number_format($sadaka_jumuiya,2)}}</td>
                        </tr>

                        <tr>
                            <td>Mapato ya Zaka</td>
                            <td>{{number_format($zaka_total,2)}}</td>
                        </tr>

                        <tr style="font-weight: bold;">
                            <td style="border-bottom:double; border-style: double;">Jumla ya mapato ya kawaida</td>
                            <td style="border-bottom:double; border-style: double;">{{number_format($mapato_kawaida_total,2)}}</td>
                        </tr>
                    </tbody>

                    <thead class="bg-light">
                        <th colspan="2" class="text-info">B: Mapato ya maendeleo</th>
                    </thead>
                    
                    <tbody>
                            <tr style="font-weight: bold;">
                                <td>Aina ya mchango</td>
                                <td>Kiasi (TZS)</td>
                            </tr>
                        @foreach ($data_mapato_maendeleo as $mapato)
                            <tr>
                                <td>{{$mapato->aina_ya_mchango}}</td>
                                <td>{{number_format($mapato->kiasi,2)}}</td>
                            </tr>
                        @endforeach
                            <tr style="font-weight: bold;">
                                <td style="border-bottom:double; border-style: double;">Jumla ya mapato maendeleo</td>
                                <td style="border-bottom:double; border-style: double;">{{number_format($mapato_michango_total,2)}}</td>
                            </tr>

                            <tr style="background-color:#F8F9FA;font-weight:bold; color:blue;">
                                <td>Jumla ya mapato yote (A+B)</td>
                                <td>{{number_format($mapato_yote_total,2)}}</td>
                            </tr>
                    </tbody>
                
                </table>
            </div>


        </div>
    </div>
</div>
@endsection