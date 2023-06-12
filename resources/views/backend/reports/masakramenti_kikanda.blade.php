@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>{{$sahihisha_kanda->name}}</th>
                <th>Jumuiya</th>
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
                <td>{{$item->jina_la_kanda}}</td>
                <td>{{$item->idadi_ya_jumuiya}}</td>
                <td>
                    {{-- dealing with ubatizo --}}
                    {{App\Mwanafamilia::where('kandas.jina_la_kanda',$item->jina_la_kanda)
                    ->where('mwanafamilias.ubatizo','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with kipaimara --}}
                    {{App\Mwanafamilia::where('kandas.jina_la_kanda',$item->jina_la_kanda)
                    ->where('mwanafamilias.kipaimara','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with ndoa --}}
                    {{App\Mwanafamilia::where('kandas.jina_la_kanda',$item->jina_la_kanda)
                    ->where('mwanafamilias.ndoa','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with ekaristi --}}
                    {{App\Mwanafamilia::where('kandas.jina_la_kanda',$item->jina_la_kanda)
                    ->where('mwanafamilias.ekaristi','anapokea')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with komunio --}}
                    {{App\Mwanafamilia::where('kandas.jina_la_kanda',$item->jina_la_kanda)
                    ->where('mwanafamilias.komunio','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->count()}}
                </td>
            </tr>
            @endforeach
        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td colspan="2">Jumla</td>
            <td>{{$jumuiya_zote}}</td>
            <td>{{$waliobatizwa}}</td>
            <td>{{$kipaimara}}</td>
            <td>{{$ndoa}}</td>
            <td>{{$ekaristi}}</td>
            <td colspan="1">{{$komunio}}</td>
        </tr>
    </table>

@endsection