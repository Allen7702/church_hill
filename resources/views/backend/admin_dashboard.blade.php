@extends('layouts.master')
@section('content')
    
<div class="row row-card-no-pd">
    
    @if(Auth::user()->ngazi=='Parokia' || Auth::user()->ngazi=='administrator')
    <div class="col-sm-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body ">
                <div class="row">
                    <div class="col-12 col-stats">
                        <div class="numbers">
                            <p class="card-category"><a href="{{url('kanda')}}" style="text-decoration: none;">{{$sahihisha_kanda ? $sahihisha_kanda->name : 'Kanda'}}</a></p>
                            <h4 class="card-title">{{$kanda}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-2">
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
   @endif
    <div class="col-sm-6 col-md-2">
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
                            <p class="card-category"><a href="{{url('wanafamilia')}}" style="text-decoration: none;">Waamini</a></p>
                            <h4 class="card-title">{{number_format($waumini_wote)}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@if(Auth::user()->ngazi=='Parokia' || Auth::user()->ngazi=='administrator')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h4>MWENENDO WA MATOLEO MWAKA {{date('Y')}}</h4>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="d-flex flex-row justify-content-end">
                    <div class="d-flex flex-row">
                        <a href="{{url('sadaka_kuu_takwimu')}}"><button class="btn btn-info btn-sm mr-3">Ukurasa wa Sadaka</button></a>
                        <a href="{{url('zaka_takwimu')}}"><button class="btn btn-info btn-sm">Ukurasa wa zaka</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-6" id="sadaka_chart">
            </div>
            <div class="col-sm-12 col-md-6" id="zaka_chart">
            </div>
        </div>
        <hr>

        <table class="table_other table w-100" id="table">
            <thead class="bg-light">
                <th>ID</th>
                <th>{{$sahihisha_kanda ? $sahihisha_kanda->name : 'Kanda'}}</th>
                <th>Jumuiya</th>
                <th>Waliobatizwa</th>
                <th>Kipaimara</th>
                <th>Wenye ndoa</th>
                <th>Ekaristi</th>
                <th>Komunio</th>
                <th>Kitendo</th>
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

                        <td>
                            <a href="{{url('masakramenti_kanda_husika',['id'=>$item->id])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>


    </div>
</div>
@endif
<script>
    $("document").ready(function(){
        //datatables
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
</script>

{{-- ========== SADAKA CHART ===========--}}
<script type="text/javascript">
        
    Highcharts.chart('sadaka_chart', {
        title: {
            text: 'Utoaji wa sadaka'
        },

        chart: {
            polar: true,
            height: 360,
            type: 'line'
        },

        xAxis: {
            categories: [
                <?php 
                    foreach(json_decode($data_sadaka,true) ?? '' as $mwezi){
                        echo $mwezi['mwezi'];
                        echo ',';
                    }
                ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Kiasi (TZS)'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'Sadaka',
            data: [
                <?php 
                    foreach(json_decode($data_sadaka,true) ?? '' as $kiasi){
                        echo $kiasi['kiasi'];
                        echo ',';
                    }
                ?>
            ]
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 1366
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        verticalAlign: 'bottom',
                        align: 'center'
                    }
                }
            }]
        }
    });

</script>

{{-- ========== ZAKA CHART ===========--}}
<script type="text/javascript">
        
    Highcharts.chart('zaka_chart', {
        title: {
            text: ''
        },

        chart: {
            polar: true,
            height: 360,
            type: 'column'
        },

        xAxis: {
            categories: [
                <?php 
                    foreach(json_decode($data_zaka,true) ?? '' as $mwezi){
                        echo $mwezi['mwezi'];
                        echo ',';
                    }
                ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Kiasi (TZS)'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'Zaka',
            data: [
                <?php 
                    foreach(json_decode($data_zaka,true) ?? '' as $kiasi){
                        echo $kiasi['kiasi'];
                        echo ',';
                    }
                ?>
            ]
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 1366
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        verticalAlign: 'bottom',
                        align: 'center'
                    }
                }
            }]
        }
    });

</script>

@endsection