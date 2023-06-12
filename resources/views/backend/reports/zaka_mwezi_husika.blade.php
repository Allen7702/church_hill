@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jumuiya</th>
            <th>Kiasi</th>
            <th>Nafasi</th>
        </tr>

        @foreach ($data_zaka as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jumuiya}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$loop->index+1}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2">Jumla</td>
            <td colspan="2">{{number_format($zaka_mwezi,2)}}</td>
        </tr>
        
    </table>

@endsection