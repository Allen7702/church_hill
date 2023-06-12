@extends('layouts.master')
@section('content')
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <h4>{{strtoupper($title)}}</h4>
            </div>
            <div class="col-sm-12 col-md-7 text-right">
                <a href="{{url('matumizi_taslimu')}}"><button class="btn btn-info btn-sm mr-2">Matumizi taslimu</button></a>
                <a href="{{url('matumizi_benki')}}"><button class="btn btn-info btn-sm mr-2">Matumizi benki</button></a>
                <a href="{{url('aina_za_matumizi')}}"><button class="btn btn-info btn-sm mr-2">Aina za matumizi</button></a>
                <button class="btn btn-info btn-sm mr-2">PDF</button>
                <button class="btn btn-info btn-sm mr-0">Excel</button>
            </div>
        </div>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <div id="taslimu_chart">

                </div>
            </div>
            <div class="col-md-6">
                <div id="bank_chart">

                </div>
            </div>
        </div>

        <hr>

        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>Chanzo</th>
                <th>Kiasi</th>
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <tr style="font-weight: bold;">
                    <td>Jumla</td>
                    <td colspan="2">{{number_format($matumizi_total,2)}}</td>
                </tr>
            </tfoot>

            <tbody>

                <tr>
                    <td>Matumizi taslimu</td>
                    <td>{{number_format($matumizi_taslimu,2)}}</td>
                    <td><a href="{{url('matumizi_taslimu_zaidi')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

                <tr>
                    <td>Matumizi Benki</td>
                    <td>{{number_format($matumizi_benki,2)}}</td>
                    <td><a href="{{url('matumizi_benki_zaidi')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script>
    $("document").ready(function(){
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
</script>

{{-- ========== MATUMIZI CHART ===========--}}
<script type="text/javascript">
        
    Highcharts.chart('taslimu_chart', {
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
                    foreach(json_decode($takwimu_taslimu,true) ?? '' as $mwezi){
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
            name: 'Matumizi taslimu',
            data: [
                <?php 
                    foreach(json_decode($takwimu_taslimu,true) ?? '' as $taslimu){
                        echo $taslimu['kiasi'];
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

{{-- ========== MATUMIZI CHART ===========--}}
<script type="text/javascript">
        
    Highcharts.chart('bank_chart', {
        title: {
            text: ''
        },

        chart: {
            polar: true,
            height: 360,
            type: 'line'
        },

        xAxis: {
            categories: [
                <?php 
                    foreach(json_decode($takwimu_benki,true) ?? '' as $mwezi){
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
            name: 'Matumizi benki',
            data: [
                <?php 
                    foreach(json_decode($takwimu_benki,true) ?? '' as $benki){
                        echo $benki['kiasi'];
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