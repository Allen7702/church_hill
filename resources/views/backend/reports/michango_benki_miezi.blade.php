@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>KIASI (TZS)</th>
            <th>MWEZI</th>
        </tr>

        @foreach ($michango_benki_miezi as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
            </tr>
        @endforeach

        <tr>
            <th>Jumla</th>
            <th colspan="2">{{number_format($michango_total,2)}}</th>
        </tr>
        
    </table>

@endsection