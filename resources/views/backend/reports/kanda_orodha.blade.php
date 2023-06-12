@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jina la kanda</th>
            <th>Herufi ufupisho</th>
            <th>Idadi ya jumuiya</th>
            <th>Imeundwa</th>
        </tr>

        @foreach ($data_kanda as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_la_kanda}}</td>
                <td>{{$item->herufi_ufupisho}}</td>
                <td>{{$item->idadi_ya_jumuiya}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-M-Y')}}</td>
            </tr>
        @endforeach
        
    </table>

@endsection