@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>S/N</th>
                <th>Jina kamili</th>
                <th>Anwani</th>
                <th>Huduma</th>
                <th>Mawasiliano</th>
                <th>Imeundwa</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($data_watoahuduma as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>
                <td>{{$item->anwani}}</td>
                <td>{{$item->aina_ya_huduma}}</td>
                <td>{{$item->mawasiliano}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
            </tr>
            @endforeach
        </tbody>
        
    </table>

@endsection