@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Tarehe</th>
            <th>Sadaka (TZS)</th>
            <th>Imewekwa</th>
        </tr>

        @foreach ($data_sadaka as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{Carbon::parse($item->tarehe)->format('d-M-Y')}}</td>
                <td>{{number_format($item->kiasi,2)}}</td>
                <td>{{$item->imewekwa_na}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2">Jumla</td>
            <td colspan="2">{{number_format($data_sadaka_total,2)}}</td>
        </tr>
        
    </table>

@endsection