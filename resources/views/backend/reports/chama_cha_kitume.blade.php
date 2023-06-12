@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jina la mwanachama</th>
            <th>Familia</th>
            <th>Jumuiya</th>
            <th>Mawasiliano</th>
            <th>Imeundwa</th>
        </tr>

        @foreach ($data_wanachama as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{$item->jina_la_familia}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>{{$item->mawasiliano}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
            </tr>
        @endforeach
        
    </table>

@endsection