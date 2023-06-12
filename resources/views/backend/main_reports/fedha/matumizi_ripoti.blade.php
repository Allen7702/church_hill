@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- ================== THE HEADER PART =========== --}}
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
                        <thead class="bg-light text-info">
                            <th width="55%">A.Matumizi ya kawaida</th>
                            <th width="*">Kiasi (TZS)</th>
                        </thead>
                        <tbody>
                            
                            @foreach ($data_kawaida as $item)
                                <tr>
                                    <td>{{$item->aina_ya_matumizi}}</td>
                                    <td>{{number_format($item->kiasi,2)}}</td>
                                </tr>
                            @endforeach
                            <tr class="text-primary" style="font-weight: bold;">
                                <td>Jumla</td>
                                <td>{{number_format($kawaida_total,2)}}</td>
                            </tr>

                            <thead class="bg-light text-info">
                                <th width="55%">B.Matumizi maendeleo</th>
                                <th width="*">Kiasi (TZS)</th>
                            </thead>

                            @foreach ($data_maendeleo as $item)
                            <tr>
                                <td>{{$item->aina_ya_matumizi}}</td>
                                <td>{{number_format($item->kiasi,2)}}</td>
                            </tr>
                            @endforeach
                            <tr class="text-primary" style="font-weight: bold;">
                                <td>Jumla</td>
                                <td>{{number_format($maendeleo_total,2)}}</td>
                            </tr>
                            <tr style="font-weight: bold;">
                                <td>Jumla ya matumizi (A+B)</td>
                                <td>{{number_format($matumizi_total,2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
@endsection