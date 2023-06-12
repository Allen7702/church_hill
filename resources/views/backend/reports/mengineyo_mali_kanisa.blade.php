@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>S/N</th>
                <th>Jina la mali</th>
                <th>Aina</th>
                <th>Thamani (TZS)</th>
                <th>Usajili</th>
                <th>Hali ya sasa</th>
                <th>Maelezo</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($data_mali as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->jina_la_mali}}</td>
                    <td>{{$item->aina_ya_mali}}</td>
                    <td>{{number_format($item->thamani,2)}}</td>
                    <td>{{$item->usajili}}</td>
                    <td>{{$item->hali_yake}}</td>
                    <td>{{$item->maelezo}}</td>
                </tr>
            @endforeach
        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td colspan="3">Jumla</td>
            <td colspan="4">{{number_format($mali_total,2)}}</td>
        </tr>
    </table>

@endsection