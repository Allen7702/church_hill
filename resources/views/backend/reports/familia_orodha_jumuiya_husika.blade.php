@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>ID</th>
            <th>Jina la familia</th>
            <th>Mawasiliano</th>
            <th>Wanafamilia</th>
        </tr>

        @foreach ($data_familia as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->jina_la_familia}}</td>
                @if($item->mawasiliano == "")
                <td>--</td>
                @else
                <td>{{$item->mawasiliano}}</td>
                @endif
                <td>{{$item->wanafamilia}}</td>
            </tr>
        @endforeach
    </table>

@endsection