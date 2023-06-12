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
            <th>Cheo</th>
            <th>Jumuiya</th>
        </tr>

        @foreach ($data_viongozi as $item)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$item->jina_kamili}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->mawasiliano}}</td>
            <td>{{$item->cheo}}</td>
            @if($item->jumuiya == "")
            <td>--</td>
            @else
            <td>{{$item->jumuiya}}</td>
            @endif
        </tr>
        @endforeach

        <tr style="font-weight: bold;">
            <td colspan="5">Jumla</td>
            <td>{{number_format($viongozi_total)}}</td>
        </tr>
        
    </table>

@endsection