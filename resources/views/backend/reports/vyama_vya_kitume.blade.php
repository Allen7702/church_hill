@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>Jina la chama</th>
            <th>Idadi ya wanachama</th>
            <th>Imeundwa</th>
        </tr>

        @foreach ($data_vyama as $item)
            <tr>
                <td>{{$item->jina_la_kikundi}}</td>
                <td>{{$item->idadi_ya_waumini}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
            </tr>
        @endforeach
    </table>

@endsection