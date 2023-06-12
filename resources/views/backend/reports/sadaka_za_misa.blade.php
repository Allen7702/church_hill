@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Aina ya Sadaka</th>
            <th>Aina ya Misa</th>
            <th>Maelezo</th>
            <th>Ilifanyika</th>
            <th>Eneo</th>
            <th>Kiasi</th>
        </tr>

        @foreach ($sadaka_za_misa as $item)

            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->aina_za_sadaka->jina_la_sadaka}}</td>

                <td>{{$item->aina_za_misa->jina_la_misa}}</td>

                @if($item->description == "")
                    <td>--</td>
                @else
                    <td>{{$item->description}}</td>
                @endif
                <td>{{$item->ilifanyika}}</td>
                <td>{{str_replace('App\\', '', $item->misaable_type)== 'CentreDetail'? 'Parokia': str_replace('App\\', '', $item->misaable_type)}}</td>
                <td>{{$item->kiasi}}</td>

            </tr>
        @endforeach
    </table>

@endsection
