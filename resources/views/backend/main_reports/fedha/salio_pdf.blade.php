@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <div class="card-body">
        <table class="table_other table">
    
            <thead class="bg-light">
                <th colspan="2" class="text-info">A: Sadaka za misa</th>
                <th></th>
            </thead>
            <tbody>
                <tr>
                    <th>Misa</th>
                    <th>Kiasi</th>
                    <th>Tarehe</th>
                </tr>
         
            <tbody>
                @foreach($sadaka_za_misa as $sadaka)
                <tr>
                    <td>{{$sadaka->aina_za_misa->jina_la_misa}}</td>
                    <td>{{number_format($sadaka->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($sadaka->ilifanyika)->format('d/m/Y')}}</td>
    
                </tr>
    
                @endforeach
                <tr>
    
                    <th>Jumla</th>
                    <th colspan="3">{{number_format($sadaka_za_misa->sum('kiasi'),2)}}</th>
                </tr>
    
            </tbody>
            </tbody>
        </table>
        <table class="table_other table">
            @php $jumla_jumuiya=0; @endphp
            @foreach($sadaka_za_jumuiya as $sadaka)
            @php $jumla_jumuiya += $sadaka->kiasi; @endphp
            @endforeach
            <thead class="bg-light">
                <th colspan="2" class="text-info">B: Sadaka za jumuiya</th>
                <th></th>
            </thead>
            <tbody>
                <th>Jumuiya</th>
                <th>Kiasi</th>
                <th>Tarehe</th>
    
         
            <tbody>
                @foreach($sadaka_za_jumuiya as $sadaka)
    
                <tr>
                    <td>{{$sadaka->jina_la_jumuiya}}</td>
                    <td>{{number_format($sadaka->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($sadaka->tarehe)->format('d/m/Y')}}</td>
                </tr>
    
                @endforeach
                <tr>
    
                    <th>Jumla</th>
                    <th colspan="3">{{number_format($jumla_jumuiya,2)}}</th>
                </tr>
    
            </tbody>
            </tbody>
    
        </table>
    
        <table class="table_other table">
            @php $jumla_zaka=0; @endphp
            @foreach($mapato_ya_zaka as $zaka)
            @php $jumla_zaka += $zaka->kiasi; @endphp
            @endforeach
            <thead class="bg-light">
                <th colspan="2" class="text-info">C: Mapato ya zaka</th>
                <th></th>
            </thead>
    
            <tbody>
                <td>S/N</td>
                <th>Kiasi</th>
                <th>Mwezi</th>
    
         
            <tbody>
                @php $count=1; @endphp
                @foreach($mapato_ya_zaka as $zaka)
    
                <tr>
                    <td>{{$count}}</td>
                    <td>{{number_format($zaka->kiasi,2)}}</td>
                    <td>{{ date("F", mktime(0, 0, 0, $zaka->mwezi, 1)) }}</td>
                </tr>
                @php $count++; @endphp
                @endforeach

                <tr>
    
                    <th>Jumla</th>
                    <th colspan="3">{{number_format($jumla_zaka,2)}}</th>
                </tr>
    
            </tbody>
            <tbody>
    
            </tbody>
            </tbody>
    
        </table>
        <table class="table">
            <thead class="bg-light">
                <th colspan="3" class="text-info">Jumla ya mapato ya kawaida (A+B+C) </th>
                <th style="font-size:15px;">
                    {{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi'),2)}}</th>
            </thead>
    
        </table>
    
    
    </div>
    
    <div class="card-body">
        @foreach($aina_ya_michango_jumla as $key=>$michango)
        <table class="table_other table">
    
            <thead class="bg-light">
    
                <th colspan="2" class="text-info">{{ucfirst(strtolower($key))}}</th>
                <th></th>
            </thead>
            <tbody>
                <th>Aina ya Mchango</th>
                <th>Kiasi</th>
                <th>Tarehe</th>
    
      
            <tbody>
                @foreach($michango as $mchango)
    
                <tr>
                    <td>{{$mchango->aina_ya_mchango}}</td>
                    <td>{{number_format($mchango->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($mchango->tarehe)->format('d/m/Y')}}</td>
                </tr>
    
                @endforeach
                <tr>
    
                    <th>Jumla</th>
                    <th colspan="3"> {{number_format($michango->sum('kiasi'),2)}}</th>
                </tr>
    
            </tbody>
            </tbody>
    
        </table>
        @endforeach
    
        @php
        $jumla_mapato_maendeleo=0;
        @endphp
        @foreach($aina_ya_michango_jumla as $key=>$michango)
        @php $jumla_mapato_maendeleo +=$michango->sum('kiasi'); @endphp
        @endforeach
    
        <table class="table">
            <thead class="bg-light">
                <th colspan="3" class="text-info">Jumla ya mapato ya Maendeleo</th>
                <th style="font-size:15px;">{{number_format($jumla_mapato_maendeleo,2)}}</th>
            </thead>
    
        </table>
    
        <table class="table">
            <thead class="bg-light" >
                <th colspan="3" class="text-info" style="font-weight:bold;font-size:15px;">Jumla ya mapato yote</th>
                <th style="font-size:15px;" class="d-flex ">
                    {{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi')+$jumla_mapato_maendeleo,2)}}</th>
            </thead>
    
        </table>
    
    </div>

    <div class="card-body">
        @foreach($matumizi as $key=>$tumizi)
        <table class="table_other table">

            <thead class="bg-light">

                <th colspan="3" class="text-info">Matumizi ya {{ucfirst(strtolower($key))}}</th>
                
                
            </thead>
            <tbody>
                <th>Maelezo</th>
                <th>Kiasi</th>
                <th>Tarehe</th>

          
            <tbody>
                @foreach($tumizi as $t)

                <tr>
                    <td>{{ucfirst($t->maelezo)}}</td>
                    <td>{{number_format($t->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($t->tarehe)->format('d/m/Y')}}</td>
                </tr>

                @endforeach
                <tr>

                    <th>Jumla</th>
                    <th colspan="3"> {{number_format($tumizi->sum('kiasi'),2)}}</th>
                </tr>

            </tbody>
            </tbody>

        </table>
        @endforeach

        @php
        $jumla_matumizi=0;
        @endphp
        @foreach($matumizi as $key=>$tumizi)
        @php $jumla_matumizi +=$tumizi->sum('kiasi'); @endphp
        @endforeach

        <table class="table">
            <thead class="bg-light">
                <th colspan="3" class="text-info">Jumla ya matumizi yote</th>
                <th style="font-size:15px;">{{number_format($jumla_matumizi,2)}}</th>
            </thead>

        </table>


        <table class="table">
            <thead class="bg-light" >
                <th colspan="3" class="text-info" style="font-weight:bold;font-size:15px;">SALIO (Mapato-Matumizi)</th>
                <th style="font-size:15px;" class="d-flex ">
                    {{number_format(($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi')+$jumla_mapato_maendeleo)-$jumla_matumizi,2)}}</th>
            </thead>
    
        </table>
        @include('layouts.report_footer_signature')
    </div>

    

@endsection