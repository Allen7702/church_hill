@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jumuiya</th>
            <th>Kiasi (TZS)</th>
            <th>Nafasi</th>
        </tr>

        @foreach ($jumuiya_data as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$loop->index+1}}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="2">Jumla</th>
            <th colspan="2">{{number_format($michango_total,2)}}</th>
        </tr>
        
    </table>

@endsection