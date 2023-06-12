@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h4>{{strtoupper($title)}}</h4>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="d-flex flex-row justify-content-end">
                    <div class="d-flex flex-row">
                        <a href="{{url('zaka')}}"><button class="btn btn-info btn-sm mr-3">Ona zaidi zaka</button></a>
                        <a href="{{url('zaka_takwimu_pdf')}}"><button class="btn btn-info btn-sm mr-3">PDF</button></a>
                        <a href="{{url('zaka_takwimu_excel')}}"><button class="btn btn-info btn-sm mr-3">EXCEL</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-12" id="zaka_chart">
            </div>
        </div>
        <hr>


        <table class="table" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>ZAKA (TZS)</th>
                <th>MWEZI</th>
                <th>KITENDO</th>
            </thead>
            <tfoot>
                <th>Jumla</th>
                <th colspan="3">{{number_format($zaka_total,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($data_zaka as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{  date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{url('zaka_mwezi',['id'=>$item->mwezi])}}"><button class="btn btn-sm btn-info">Angalia</button></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $("document").ready(function(){
        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
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
            type: 'line'
        },

        xAxis: {
            title: {
                text: 'Mwezi'
            },
            categories: [
                <?php
                    foreach(json_decode($data_zaka,true) ?? '' as $mwezi){
//                        $dateObj   = DateTime::createFromFormat('!m', $mwezi['mwezi']);
//                        $monthName = $dateObj->format('M');
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
