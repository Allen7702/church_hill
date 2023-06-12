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
                            <p class="card-category"><a href="{{url('kikundi')}}" style="text-decoration: none;">Vyama vya kitume</a></p>
                            <h4 class="card-title">{{$vyakitume}}</h4>
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
                        <a href="{{url('masakramenti_jumla_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('masakramenti_jumla_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>    
                </div>
            </div>

            <div class="card-body">
                
                <table class="table_other table w-100" id="table">
                    
                    <thead class="bg-light">
                        <th>ID</th>
                        <th>Jumuiya</th>
                        <th>Wanajumuiya</th>
                        <th>Waliobatizwa</th>
                        <th>Kipaimara</th>
                        <th>Wenye ndoa</th>
                        <th>Ekaristi</th>
                        <th>Komunio</th>
                    </thead>

                    <tfoot>
                        <th colspan="2">Jumla</th>
                        <th>{{$wanajumuiya}}</th>
                        <th>{{$waliobatizwa}}</th>
                        <th>{{$kipaimara}}</th>
                        <th>{{$ndoa}}</th>
                        <th>{{$ekaristi}}</th>
                        <th>{{$komunio}}</th>
                    </tfoot>
                    
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