@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jumuiya</th>
            <th>Kiasi</th>
            <th>Mwezi</th>
        </tr>

        @foreach ($data_sadaka_jumuiya as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2">Jumla</td>
            <td colspan="2">{{number_format($sadaka_jumuiya_total,2)}}</td>
        </tr>
        
    </table>

@endsection