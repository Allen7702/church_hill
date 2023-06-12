@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Aina ya cheo</th>
            <th>Idadi ya viongozi</th>
        </tr>

        @foreach ($data_viongozi as $item)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$item->cheo}}</td>
            <td>{{$item->idadi}}</td>
        </tr>
        @endforeach

        <tr style="font-weight: bold;">
            <td colspan="2">Jumla</td>
            <td>{{number_format($viongozi_total)}}</td>
        </tr>
        
    </table>

@endsection