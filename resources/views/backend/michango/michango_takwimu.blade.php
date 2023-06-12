@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <h4>{{strtoupper($title)}}</h4>
                </div>
                <div class="col-sm-12 col-md-7 text-right">
                    <a href="{{url('michango_taslimu_kijumuiya')}}"><button class="btn btn-info btn-sm mr-2">Michango taslimu</button></a>
                    <a href="{{url('michango_benki_kijumuiya')}}"><button class="btn btn-info btn-sm mr-2">Michango benki</button></a>
                    <a href="{{url('aina_za_michango')}}"><button class="btn btn-info btn-sm mr-2">Aina za michango</button></a>
                    <a href="{{url('michango_takwimu_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                    <a href="{{url('michango_takwimu_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
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
            
            <table class="table" id="table">
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Chanzo</th>
                    <th>Kiasi (TZS)</th>
                    <th>Kitendo</th>
                </thead>
                <tfoot>
                    <th colspan="2">Jumla</th>
                    <th colspan="2">{{number_format($michango_total,2)}}</th>
                </tfoot>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Michango pesa taslimu</td>
                        <td>{{number_format($michango_taslimu,2)}}</td>
                        <td><a href="{{url('michango_taslimu_miezi')}}"><button class="btn btn-sm btn-info">Angalia</button></a></td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Michango benki</td>
                        <td>{{number_format($michango_benki,2)}}</td>
                        <td><a href="{{url('michango_benki_miezi')}}"><button class="btn btn-sm btn-info">Angalia</button></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $("document").ready(function(){
            $('#table').DataTable({
                responsive: true,
                stateSave: true,
            })
        })
    </script>

{{-- ========== MICHANGO CHART ===========--}}
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
            name: 'Michango taslimu',
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

{{-- ========== MICHANGO CHART ===========--}}
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
            name: 'Michango benki',
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