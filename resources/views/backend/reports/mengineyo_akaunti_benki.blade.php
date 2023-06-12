@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>S/N</th>
                <th>Jina la benki</th>
                <th>Jina la akaunti</th>
                <th>Akaunti namba</th>
                <th>Tawi</th>
                <th>Hali yake</th>
                <th>Imeundwa</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($data_akaunti_benki as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_la_benki}}</td>
                <td>{{$item->jina_la_akaunti}}</td>
                <td>{{$item->akaunti_namba}}</td>
                <td>{{$item->tawi}}</td>
                <td>{{$item->hali_ya_akaunti}}</td>
                <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
            </tr>
        @endforeach
        </tbody>
        
    </table>

@endsection