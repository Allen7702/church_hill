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
                        <a href="{{url('sadaka_jumuiya')}}"><button class="btn btn-info btn-sm mr-2">Ona zaidi Sadaka Jumuiya</button></a>
{{--                        <a href="{{url('sadaka_kuu')}}"><button class="btn btn-info btn-sm mr-2">Ona zaidi Sadaka</button></a>--}}
                        <a href="{{url('sadaka_takwimu_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('sadaka_takwimu_excel')}}"><button class="btn btn-info btn-sm mr-0">EXCEL</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
{{--            <div class="col-sm-12 col-md-6" id="sadaka_chart">--}}
{{--            </div>--}}
            <div class="col-sm-12 col-md-12" id="sadaka_jumuiya_chart">
            </div>
        </div>
        <hr>

        <table class="table" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>SADAKA</th>
                <th>KIASI (TZS)</th>
                <th>KITENDO</th>
            </thead>
{{--            <tfoot>--}}
{{--                <th colspan="2">Jumla</th>--}}
{{--                <th colspan="2">{{number_format($sadaka_total,2)}}</th>--}}
{{--            </tfoot>--}}

            <tbody>
{{--                <tr>--}}
{{--                    <td>1</td>--}}
{{--                    <td>Sadaka</td>--}}
{{--                    <td>{{number_format($sadaka_kuu,2)}}</td>--}}
{{--                    <td>--}}
{{--                        <a href="{{url('sadaka_kuu_zaidi')}}"><button class="btn btn-sm btn-info">Angalia</button></a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
                <tr>
                    <td>1</td>
                    <td>Sadaka za Jumuiya</td>
                    <td>{{number_format($sadaka_jumuiya,2)}}</td>
                    <td>
                        <a href="{{url('sadaka_jumuiya_zaidi')}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                    </td>
                </tr>

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

{{-- ========== SADAKA CHART ===========--}}
<script type="text/javascript">

    Highcharts.chart('sadaka_chart', {
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

{{-- ========== SADAKA JUMUIYA CHART ===========--}}
<script type="text/javascript">

    Highcharts.chart('sadaka_jumuiya_chart', {
        title: {
            text: ''
        },

        chart: {
            polar: true,
            height: 360,
            type: 'column'
        },

        xAxis: {

            type: 'date',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%M',
            },

            categories: [

                <?php
                    foreach(json_decode($data_sadaka_jumuiya,true) ?? '' as $mwezi){
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
            name: 'Sadaka za jumuiya',
            data: [
                <?php
                    foreach(json_decode($data_sadaka_jumuiya,true) ?? '' as $kiasi){
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
