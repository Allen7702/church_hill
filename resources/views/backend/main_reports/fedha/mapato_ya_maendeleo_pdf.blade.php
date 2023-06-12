@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    @if($name=='jumla_mapato_maendeleo')
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
                <th style="font-size:18px;">{{number_format($jumla_mapato_maendeleo,2)}}</th>
            </thead>

        </table>

    </div>

    @elseif($name=='jumla_ya_mapato_yote')
    <div class="card-body">
        <table class="table_other table justify-content-center">

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
                    <td>{{\Carbon\carbon::parse($sadaka->tarehe)->format('d/m/Y')}}</td>

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
                <th style="font-size:18px;">
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
                <th style="font-size:18px;">{{number_format($jumla_mapato_maendeleo,2)}}</th>
            </thead>

        </table>

        <table class="table">
            <thead class="bg-light" >
                <th colspan="3" class="text-info" style="font-weight:bold;font-size:18px;">Jumla ya mapato yote</th>
                <th style="font-size:21px;">
                    {{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi')+$jumla_mapato_maendeleo,2)}}</th>
            </thead>

        </table>

    </div>

    @else

    @php $jumla=0; @endphp
    @foreach($aina_ya_michango_jumla as $michango)
    @php $jumla += $michango->kiasi; @endphp
    @endforeach

    <div class="card-body">
        <table class="table_other table">

            <thead class="bg-light">
                <th>Aina ya mchango</th>
                <th>Kiasi</th>
                <th>Tarehe</th>
            </thead>
       
            <tbody>
                @foreach($aina_ya_michango_jumla as $michango)
                <tr>
                    <td>{{$michango->aina_ya_mchango}}</td>
                    <td>{{number_format($michango->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($michango->tarehe)->format('d/m/Y')}}</td>
                </tr>

                @endforeach
                <tr>
                    <th>Jumla</th>
                    <th colspan="3">{{number_format($jumla,2)}}</th>
                </tr>

            </tbody>

        </table>
    </div>

    @endif
    @include('layouts.report_footer_signature')

@endsection