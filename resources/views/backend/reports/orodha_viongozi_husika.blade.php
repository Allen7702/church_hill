@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jina kamili</th>
            <th>Barua pepe</th>
            <th>Simu</th>
            @if($cheo == "Halmashauri ya walei")
            <th>Jumuiya</th>
            @endif
        </tr>

        @foreach ($data_viongozi as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->mawasiliano}}</td>
                @if($cheo == "Halmashauri ya walei")
                <td>{{$item->jumuiya}}</td>
                @endif
            </tr>
        @endforeach

        <tr style="font-weight: bold;">
            @if($cheo == "Halmashauri ya walei")
            <td colspan="4">Jumla</td>
            @else
            <td colspan="3">Jumla</td>
            @endif
            <td>{{number_format($viongozi_total)}}</td>
        </tr>
        
    </table>

@endsection