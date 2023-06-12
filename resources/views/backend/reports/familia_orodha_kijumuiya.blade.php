@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jumuiya</th>
            <th>Kanda</th>
            <th>Idadi ya familia</th>
        </tr>

        @foreach ($data_jumuiya as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>{{$item->jina_la_kanda}}</td>
                <td>{{$item->idadi_ya_familia}}</td>
            </tr>
        @endforeach

    </table>

@endsection