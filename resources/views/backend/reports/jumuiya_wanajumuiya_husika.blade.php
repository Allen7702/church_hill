@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Mwanajumuiya</th>
            <th>Namba</th>
            <th>Cheo</th>
            <th>Mawasiliano</th>
            <th>Jinsia</th>
            <th>Ubatizo</th>
            <th>Ekaristi</th>
            <th>Kipaimara</th>
            <th>Komunio</th>
            <th>Ndoa</th>
            <th>Aina ya Ndoa</th>
            <th>Taaluma</th>
            <th>Tarehe ya kuzaliwa</th>
            <th>Namba ya cheti</th>
            <th>Parokia ya ubatizo</th>
            <th>Jimbo la ubatizo</th>

        </tr>

        @foreach ($data_wanajumuiya as $item)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$item->jina_kamili}}</td>

            @if($item->namba_utambulisho == "")
            <td>--</td>
            @else
            <td>{{$item->namba_utambulisho}}</td>
            @endif

            @if($item->cheo_familia == "")
            <td>--</td>
            @else
            <td>{{$item->cheo_familia}}</td>
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

            @if($item->kipaimara == "")
                <td>--</td>
            @else
                <td>{{$item->kipaimara}}</td>
            @endif

            @if($item->komunio == "")
                <td>--</td>
            @else
                <td>{{$item->komunio}}</td>
            @endif

            @if($item->ndoa == "")
                <td>--</td>
            @else
                <td>{{$item->ndoa}}</td>
            @endif

            @if($item->aina_ya_ndoa == "")
                <td>--</td>
            @else
                <td>{{$item->aina_ya_ndoa}}</td>
            @endif

            @if($item->taaluma == "")
                <td>--</td>
            @else
                <td>{{$item->taaluma}}</td>
            @endif

            @if($item->dob == "")
                <td>--</td>
            @else
                <td>{{$item->dob}}</td>
            @endif

            @if($item->namba_ya_cheti == "")
                <td>--</td>
            @else
                <td>{{$item->namba_ya_cheti}}</td>
            @endif

            @if($item->parokia_ya_ubatizo == "")
                <td>--</td>
            @else
                <td>{{$item->parokia_ya_ubatizo}}</td>
            @endif

            @if($item->jimbo_la_ubatizo == "")
                <td>--</td>
            @else
                <td>{{$item->jimbo_la_ubatizo}}</td>
            @endif

        </tr>
    @endforeach

    </table>

@endsection
