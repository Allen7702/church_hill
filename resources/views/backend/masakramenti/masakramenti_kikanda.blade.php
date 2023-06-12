@extends('layouts.master')
@section('content')

<div class="row row-card-no-pd">

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body ">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('kanda')}}" style="text-decoration: none;">{{$sahihisha_kanda->name}}</a></p>
                            <h4 class="card-title">{{$kanda}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body ">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('jumuiya')}}" style="text-decoration: none;">Jumuiya</a></p>
                            <h4 class="card-title">{{$jumuiya}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('familia')}}" style="text-decoration: none;">Familia</a></p>
                            <h4 class="card-title">{{$familia}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('wanafamilia')}}" style="text-decoration: none;">Waamini</a></p>
                            <h4 class="card-title">{{$wanafamilia}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card card-stats card-round">
            <div class="card-header">
                <div class="row">
                    {{-- title --}}
                    <div class="col-sm-6 col-md-6">
                        <h4>{{strtoupper($title)}}</h4>
                    </div>
        
                    {{-- exporting --}}
                    <div class="col-sm-6 col-md-6 text-right">
                        <a href="{{url('masakramenti_kikanda_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('masakramenti_kikanda_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>    
                </div>
            </div>

            <div class="card-body">
                
                <table class="table_other table w-100" id="table">
                    <thead class="bg-light">
                        <th>ID</th>
                        <th>{{$sahihisha_kanda->name}}</th>
                        <th>Jumuiya</th>
                        <th>Waliobatizwa</th>
                        <th>Kipaimara</th>
                        <th>Wenye ndoa</th>
                        <th>Ekaristi</th>
                        <th>Komunio</th>
                        <th>Kitendo</th>
                    </thead>

                    <tfoot>
                        <th colspan="2">Jumla</th>
                        <th>{{$jumuiya_zote}}</th>
                        <th>{{$waliobatizwa}}</th>
                        <th>{{$kipaimara}}</th>
                        <th>{{$ndoa}}</th>
                        <th>{{$ekaristi}}</th>
                        <th colspan="2">{{$komunio}}</th>
                    </tfoot>
                    
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

                                <td>
                                    <a href="{{url('masakramenti_kanda_husika',['id'=>$item->id])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>        
        </div>
    </div>
    
</div>

<script>
    $("document").ready(function(){
        //datatables
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
</script>

    
@endsection