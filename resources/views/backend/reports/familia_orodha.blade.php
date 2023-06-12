@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>Jina la familia</th>
            <th>Jina la jumuiya</th>
            <th>Wanafamilia</th>
            <th>Imeundwa</th>
        </tr>

        @foreach ($data_familia as $item)
            <tr>
                <td>{{$item->jina_la_familia}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>{{$item->wanafamilia}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
            </tr>
        @endforeach
    </table>

@endsection