@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Mwanajumuiya</th>
                <th>Simu</th>
                <th>Ndoa</th>
                <th>Ubatizo</th>
                <th>Kipaimara</th>
                <th>Komunio</th>
                <th>Ekaristi</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($takwimu_za_kiroho as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->jina_kamili}}</td>
                    <td>{{$item->mawasiliano}}</td>
                    <td>{{$item->ndoa}}</td>
                    <td>{{$item->ubatizo}}</td>
                    <td>{{$item->kipaimara}}</td>
                    <td>{{$item->komunio}}</td>
                    <td>{{$item->ekaristi}}</td>
                </tr>
            @endforeach
        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td>Jumla</td>
            <td colspan="2">{{$wanajumuiya_wote}}</td>
            <td>{{$ndoa}}</td>
            <td>{{$ubatizo}}</td>
            <td>{{$kipaimara}}</td>
            <td>{{$komunio}}</td>
            <td>{{$ekaristi}}</td>
        </tr>
    </table>

@endsection