@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Mwanajumuiya</th>
            <th>Namba</th> 
            <th>Kiasi</th>
            <th>Nafasi</th>
        </tr>

        @foreach ($data_zaka as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{$item->namba_utambulisho}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$loop->index+1}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3">Jumla</td>
            <td colspan="2">{{number_format($zaka_total,2)}}</td>
        </tr>
        
    </table>

@endsection