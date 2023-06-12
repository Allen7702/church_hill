@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- ================== THE HEADER PART =========== --}}
            <div class="card-header">
                <div class="row text-right">
                    <div class="col-md-12">
                        <a href="{{route('matumizi',[$kundi,$aina_ya_matumizi,'pdf',$kuanzia,$ukomo])}}"><button
                                class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{route('matumizi',[$kundi,$aina_ya_matumizi,'excel',$kuanzia,$ukomo])}}"><button
                                class="btn btn-info btn-sm mr-0">EXCEL</button></a>

                    </div>
                </div>

                <div class="col-md-12">
                    @include('layouts.header')
                </div>
            </div>


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

                    <tfoot>

                        <th>Jumla</th>
                        <th colspan="3"> {{number_format($tumizi->sum('kiasi'),2)}}</th>
                        
                    </tfoot>
                    <tbody>
                        @foreach($tumizi as $t)

                        <tr>
                            <td>{{ucfirst($t->maelezo)}}</td>
                            <td>{{number_format($t->kiasi,2)}}</td>
                            <td>{{\Carbon\carbon::parse($t->tarehe)->format('d/m/Y')}}</td>
                        </tr>

                        @endforeach


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
                    <tfoot>
                        <th>Jumla</th>
                        <th colspan="3">{{number_format($jumla,2)}}</th>
                    </tfoot>
                    <tbody>
                        @foreach($matumizi as $tumizi)
                        <tr>
                            <td>{{ucfirst($tumizi->maelezo)}}</td>
                            <td>{{number_format($tumizi->kiasi,2)}}</td>
                            <td>{{\Carbon\carbon::parse($tumizi->tarehe)->format('d/m/Y')}}</td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>
            @endif
         
        </div>
    </div>
</div>
@endsection