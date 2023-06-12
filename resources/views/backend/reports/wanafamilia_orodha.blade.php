@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Jina kamili</th>
            <th>Namba</th>
            <th>Simu</th>
            <th>Jinsia</th>
            <th>Kuzaliwa</th>
            <th>Taaluma</th>
            <th>Ndoa</th>
            <th>Ubatizo</th>
            <th>Ekaristi</th>
            <th>Komunio</th>
            <th>Kipaimara</th>
            {{-- <th>Imeundwa</th> --}}
        </tr>

        @foreach ($data_wanafamilia as $item)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->jina_kamili}}</td>

                @if($item->namba_utambulisho == "")
                <td>--</td>
                @else
                <td>{{$item->namba_utambulisho}}</td>
                @endif

                @if($item->mawasiliano == "")
                <td>--</td>
                @else
                <td>{{$item->mawasiliano}}</td>
                @endif

                @if($item->jinsia == "")
                <td>--</td>
                @else
                <td>{{$item->jinsia}}</td>
                @endif

                @if($item->dob == "")
                <td>--</td>
                @else
                <td>{{Carbon::parse($item->dob)->format('d-M-Y')}}</td>
                @endif

                @if($item->taaluma == "")
                <td>--</td>
                @else
                <td>{{$item->taaluma}}</td>
                @endif

                @if($item->ndoa == "")
                <td>--</td>
                @else
                <td>{{$item->ndoa}}</td>
                @endif

                @if($item->ubatizo == "")
                <td>--</td>
                @else
                <td>{{$item->ubatizo}}</td>
                @endif

                @if($item->ekaristi == "")
                <td>--</td>
                @else
                <td>{{$item->ekaristi}}</td>
                @endif

                @if($item->komunio == "")
                <td>--</td>
                @else
                <td>{{$item->komunio}}</td>
                @endif

                @if($item->kipaimara == "")
                <td>--</td>
                @else
                <td>{{$item->kipaimara}}</td>
                @endif

                {{-- <td>{{Carbon::parse($item->created_at)->format('d-M-Y')}}</td> --}}
            </tr>
        @endforeach
    </table>

@endsection