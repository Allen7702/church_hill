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

                    {{-- since we retrieve data from backend use it here to link with other models --}}
                    <td>{{App\Jumuiya::where('jina_la_jumuiya',$item->jina_la_jumuiya)->value('id')}}</td>
                    <td>{{$item->jina_la_jumuiya}}</td>
                    <td>{{App\Familia::where('jina_la_jumuiya',$item->jina_la_jumuiya)->sum('wanafamilia')}}</td>
                    <td>
                        {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                        ->where('mwanafamilias.ubatizo','tayari')->count()}}
                    </td>
                    <td>
                        {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                        ->where('mwanafamilias.kipaimara','tayari')->count()}}
                    </td>
                    <td>
                        {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                        ->where('mwanafamilias.ndoa','tayari')->count()}}
                    </td>
                    <td>
                        {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                        ->where('mwanafamilias.ekaristi','anapokea')->count()}}
                    </td>
                    <td>
                        {{App\Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$item->jina_la_jumuiya)
                        ->where('mwanafamilias.komunio','tayari')->count()}}
                    </td>
                    
                </tr>
            @endforeach
        </tbody>

        <tr style="font-weight:bold; background-color:white; color:black;">
            <td colspan="2">Jumla</td>
            <td>{{$wanajumuiya}}</td>
            <td>{{$waliobatizwa}}</td>
            <td>{{$kipaimara}}</td>
            <td>{{$ndoa}}</td>
            <td>{{$ekaristi}}</td>
            <td>{{$komunio}}</td>
        </tr>
    </table>

@endsection