@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Jumuiya</th>
                <th>Familia</th>
                <th>Waliobatizwa</th>
                <th>Kipaimara</th>
                <th>Wenye ndoa</th>
                <th>Ekaristi</th>
                <th>Komunio</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($takwimu_za_kiroho as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                @if($item->idadi_ya_familia == 0)
                    <td>--</td>
                @else
                <td>{{$item->idadi_ya_familia}}</td>
                @endif
                <td>
                    {{ App\Mwanafamilia::where('mwanafamilias.ubatizo','tayari')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->count()}}
                </td>

                <td>
                    {{ App\Mwanafamilia::where('mwanafamilias.kipaimara','tayari')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->count()}}
                </td>

                <td>
                    {{ App\Mwanafamilia::where('mwanafamilias.ndoa','tayari')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->count()}}
                </td>

                <td>
                    {{ App\Mwanafamilia::where('mwanafamilias.ekaristi','anapokea')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->count()}}
                </td>

                <td>
                    {{ App\Mwanafamilia::where('mwanafamilias.komunio','tayari')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->count()}}
                </td>
            </tr>
        @endforeach
        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td colspan="2">Jumla</td>
            <td>{{$familia_zote}}</td>
            <td>{{$waliobatizwa}}</td>
            <td>{{$kipaimara}}</td>
            <td>{{$ndoa}}</td>
            <td>{{$ekaristi}}</td>
            <td colspan="1">{{$komunio}}</td>
        </tr>
    </table>

@endsection