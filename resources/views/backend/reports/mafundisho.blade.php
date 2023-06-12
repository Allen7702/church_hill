@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>Jina kamili</th>
            <th>Namba ya utambulisho</th>
            <th>Hali</th>
            <th>Alianza</th>
            <th>Akamaliza</th>
        </tr>

        @foreach ($enrollments as $item)
            <tr>
                <td>{{$item->mwanafamilia->jina_kamili}}</td>

                @if($item->mwanafamilia->namba_utambulisho == "")
                    <td>--</td>
                @else
                    <td>{{$item->mwanafamilia->namba_utambulisho}}</td>
                @endif

                @if($item->status == "")
                    <td>--</td>
                @else
                    <td> <span class="badge badge-circle badge-{{$item->status == 'fresher'?'info':'success'}}">{{$item->status == 'fresher'? 'anasoma': 'kamaliza'}}</span></td>
                @endif

                @if($item->started_at == "")
                    <td>--</td>
                @else
                    <td>{{$item->started_at}}</td>
                @endif
                @if($item->ended_at == "")
                    <td>--</td>
                @else
                    <td>{{$item->ended_at}}</td>
                @endif

            </tr>
        @endforeach
    </table>

@endsection
