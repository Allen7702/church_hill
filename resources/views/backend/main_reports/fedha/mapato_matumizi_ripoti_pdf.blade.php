@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')
    
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
                        <td>{{number_format($data_sadaka,2)}}</td>
                    </tr>
            

                <tr>
                    <td>Sadaka za jumuiya</td>
                    <td>{{number_format($sadaka_jumuiya,2)}}</td>
                </tr>

                <tr>
                    <td>Mapato ya Zaka</td>
                    <td>{{number_format($zaka_total,2)}}</td>
                </tr>

                <tr style="font-weight: bold;">
                    <td>Jumla ya mapato ya kawaida</td>
                    <td>{{number_format($mapato_kawaida_total,2)}}</td>
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
                        <td>{{number_format($mapato->kiasi,2)}}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td>Jumla ya mapato maendeleo</td>
                    <td>{{number_format($mapato_michango_total,2)}}</td>
                </tr>
                <tr style="background-color:#F8F9FA;font-weight:bold;" class="text-primary">
                    <td>Jumla ya mapato yote (A+B)</td>
                    <td>{{number_format($mapato_yote_total,2)}}</td>
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
                    <td>{{number_format($item->kiasi,2)}}</td>
                </tr>
                @endforeach

                <tr class="text-primary" style="font-weight: bold;">
                    <td>Jumla</td>
                    <td>{{number_format($kawaida_total,2)}}</a></td>
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
                    <td>{{number_format($item->kiasi,2)}}</td>
                </tr>
                @endforeach

                <tr style="font-weight: bold;">
                    <td>Jumla</td>
                    <td>{{number_format($maendeleo_total,2)}}</td>
                </tr>

                <tr style="font-weight: bold;" class="text-primary">
                    <td>Jumla ya matumizi (C+D)</td>
                    <td>{{number_format($matumizi_total,2)}}</td>
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
                    <td>{{number_format($salio,2)}}</td>
                </tr>
                @endif

            </tbody>
        </table>

        @include('layouts.report_footer_signature')
    </div>

    

@endsection