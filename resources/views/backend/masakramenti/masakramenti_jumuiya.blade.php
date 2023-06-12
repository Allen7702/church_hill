@extends('layouts.master')
@section('content')

<div class="row row-card-no-pd">

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
            <div class="card-body ">
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
                            <p class="card-category"><a href="{{url('wanafamilia')}}" style="text-decoration: none;">Wanajumuiya</a></p>
                            <h4 class="card-title">{{$wanafamilia}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->ngazi=='Parokia' || Auth::user()->ngazi=='administrator' || Auth::user()->ngazi=='Kanda' )
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('users')}}" style="text-decoration: none;">Viongozi</a></p>
                            <h4 class="card-title">{{$viongozi}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   @endif
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
                        <a href="{{url('masakramenti_jumuiya_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('masakramenti_jumuiya_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>    
                </div>
            </div>

            <div class="card-body">
                
                <table class="table_other table w-100" id="table">
                    <thead class="bg-light">
                        <th>ID</th>
                        <th>Jumuiya</th>
                        <th>Familia</th>
                        <th>Waliobatizwa</th>
                        <th>Kipaimara</th>
                        <th>Wenye ndoa</th>
                        <th>Ekaristi</th>
                        <th>Komunio</th>
                        <th>Kitendo</th>
                    </thead>

                    <tfoot>
                        <th colspan="2">Jumla</th>
                        <th>{{$familia_zote}}</th>
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

                                <td>
                                    <a href="{{url('masakramenti_jumuiya_husika',['id'=>$item->id])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
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