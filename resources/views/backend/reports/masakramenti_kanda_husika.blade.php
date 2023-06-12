@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Jumuiya</th>
                <th>Wanajumuiya</th>
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
                <td>{{$item->jumuiya_id}}</td>
                <td>{{$item->jina_la_jumuiya}}</td>
                <td>
                    {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                    ->count()}}
                </td>
                <td>
                    {{-- dealing with ubatizo --}}
                    {{App\Mwanafamilia::where('jumuiyas.id',$item->jumuiya_id)
                    ->where('mwanafamilias.ubatizo','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with kipaimara --}}
                    {{App\Mwanafamilia::where('jumuiyas.id',$item->jumuiya_id)
                    ->where('mwanafamilias.kipaimara','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with wenye ndoa --}}
                    {{App\Mwanafamilia::where('jumuiyas.id',$item->jumuiya_id)
                    ->where('mwanafamilias.ndoa','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with wenye ekaristi --}}
                    {{App\Mwanafamilia::where('jumuiyas.id',$item->jumuiya_id)
                    ->where('mwanafamilias.ekaristi','anapokea')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->count()}}
                </td>

                <td>
                    {{-- dealing with wenye komunio --}}
                    {{App\Mwanafamilia::where('jumuiyas.id',$item->jumuiya_id)
                    ->where('mwanafamilias.komunio','tayari')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->count()}}
                </td>   
            </tr>
        @endforeach

        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td>Jumla</td>
            <td>{{$jumuiya_zote}}</td>
            <td>{{$wanajumuiya_wote}}</td>
            <td>{{$waliobatizwa}}</td>
            <td>{{$kipaimara}}</td>
            <td>{{$ndoa}}</td>
            <td>{{$ekaristi}}</td>
            <td colspan="1">{{$komunio}}</td>
        </tr>
    </table>

@endsection