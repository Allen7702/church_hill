@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>SADAKA (TZS)</th>
            <th>MWEZI</th>
        </tr>

        @foreach ($data_sadaka_jumuiya as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
            </tr>
        @endforeach

        <tr>
            <td>Jumla</td>
            <td colspan="2">{{number_format($sadaka_jumuiya,2)}}</td>
        </tr>
        
    </table>

@endsection