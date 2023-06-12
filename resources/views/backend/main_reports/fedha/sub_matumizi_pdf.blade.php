@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    
    @if($aina_ya_matumizi=='matumizi_jumla')
              
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
                <th style="font-size:18px;">{{number_format($jumla_matumizi,2)}}</th>
            </thead>

        </table>

 

    </div>

    @else


    @php $jumla=0; @endphp
    @foreach($matumizi as $tumizi)
    @php $jumla += $tumizi->kiasi; @endphp
    @endforeach

    <div class="card-body">
        <table class="table_other table">

            <thead class="bg-light">
                <th>Maelezo</th>
                <th>Kiasi</th>
                <th>Tarehe</th>
            </thead>
        
            <tbody>
                @foreach($matumizi as $tumizi)
                <tr>
                    <td>{{ucfirst($tumizi->maelezo)}}</td>
                    <td>{{number_format($tumizi->kiasi,2)}}</td>
                    <td>{{\Carbon\carbon::parse($tumizi->tarehe)->format('d/m/Y')}}</td>
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