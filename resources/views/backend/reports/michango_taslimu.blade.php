@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Mwanajumuiya</th>
            <th>Kiasi (TZS)</th>
            <th>Aina ya mchango</th>
            <th>Tarehe</th>
        </tr>

        @foreach ($michango_taslimu as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$item->aina_ya_mchango}}</td>
                <td>{{Carbon::parse($item->tarehe)->format('d-M-Y')}}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="2">Jumla</th>
            <th colspan="3">{{number_format($michango_total,2)}}</th>
        </tr>
        
    </table>

@endsection