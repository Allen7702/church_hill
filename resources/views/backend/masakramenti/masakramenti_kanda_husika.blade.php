@extends('layouts.master')
@section('content')

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
                        <a href="{{url('masakramenti_kanda_husika_pdf',['id'=>$kanda])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('masakramenti_kanda_husika_excel',['id'=>$kanda])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>    
                </div>
            </div>

            <div class="card-body">
                
                <table class="table table_other w-100" id="table">
                    
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
                        <th>Jumla</th>
                        <th>{{$jumuiya_zote}}</th>
                        <th>{{$wanajumuiya_wote}}</th>
                        <th>{{$waliobatizwa}}</th>
                        <th>{{$kipaimara}}</th>
                        <th>{{$ndoa}}</th>
                        <th>{{$ekaristi}}</th>
                        <th colspan="2">{{$komunio}}</th>
                    </tfoot>
                    
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