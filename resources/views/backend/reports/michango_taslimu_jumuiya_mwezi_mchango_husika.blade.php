@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Mwanajumuiya</th>
            <th>Kiasi (TZS)</th>
            <th>Nafasi</th>
        </tr>

        @foreach ($michango_taslimu as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$loop->index+1}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2">Jumla</td>
            <td colspan="2">{{number_format($michango_total,2)}}</td>
        </tr>
        
    </table>

@endsection