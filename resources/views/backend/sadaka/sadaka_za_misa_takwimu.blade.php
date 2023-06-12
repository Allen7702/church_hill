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
                        <button id="addBtn" class="btn btn-info btn-sm mr-2">Ongeza sadaka za misa</button>
                        <a href="{{route('sadaka_za_misas.download_pdf', ['type' => $type , 'year'=> $year , 'sadaka' => $sadaka , 'misa'=> $misa,  ])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{route('sadaka_za_misas.download_excel', ['type' => $type , 'year'=> $year, 'sadaka' => $sadaka , 'misa'=> $misa ])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-12" id="sadaka_chart">
            </div>

        </div>
        <hr>

        <table class="table" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>ENEO</th>
                <th>KIASI (TZS)</th>
                <th>KITENDO</th>
            </thead>
            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="2">{{number_format($total,2)}}</th>
            </tfoot>

            <tbody>
            @foreach($sadaka_za_misa as $sadaka )
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{str_replace('App\\', '', $sadaka->misaable_type)== 'CentreDetail'? 'Parokia': str_replace('App\\', '', $sadaka->misaable_type)}}</td>
                    <td>{{number_format($sadaka->kiasi,2)}}</td>
                    <td>
                        <a href="{{route('sadaka_za_misas.index', ['type' => str_replace('App\\', '', $sadaka->misaable_type)])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
<div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="my-modal-title">Ongeza sadaka kwenye misa </h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                </button>
            </div>
            <form id="mafundisho-add-Form" action="{{route('sadaka_za_misas.store')}}"  method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Chagua aina ya sadaka:</label>
                        <select name="sadaka" id="filter_sadaka" class="form-control select2 " required>
                            <option value="" disabled>Chagua aina ya sadaka</option>
                            @foreach ((\App\AinaZaSadaka::all(['id','jina_la_sadaka'])) as $item)

                                <option value="{{$item->id}}"  >{{$item->jina_la_sadaka}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Chagua aina ya misa:</label>
                        <select name="misa" id="filter_misa" class="form-control select2 " required>
                            <option value="" disabled>Chagua aina ya misa</option>
                            @foreach (\App\AinaZaMisa::all(['id', 'jina_la_misa']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_misa}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Ilifanyika:</label>
                        <input class="form-control" type="date" id="ilifanyika" name="ilifanyika" placeholder="Tarehe waliyoanza" required>
                    </div>
                    <div class="form-group">
                        <label for="">Kiasi:</label>
                        <input class="form-control" type="number" id="kiasi" name="kiasi" placeholder="Kiasi"/>
                    </div>
                    <div class="form-group" id="eneo-div">
                        <label for="">Eneo:</label>
                        <select name="eneo" id="eneo" class="form-control select2">
                            <option value="" disabled selected>Chagua Eneno</option>
                            <option value="parokia" >Parokia</option>
                            <option value="jumuiya" >Jumuiya</option>
                            <option value="kanda" >Kanda</option>
                        </select>
                    </div>

                    <div class="form-group" id="jumuiya-div" style="display: none">
                        <label for="">Chagua jumuiya:</label>
                        <select name="jumuiya" id="jumuiya" class="form-control select2 " required>
                            <option value="" disabled>Chagua jumuiya</option>
                            @foreach (\App\Jumuiya::all(['id', 'jina_la_jumuiya']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_jumuiya}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group " id="kanda-div" style="display: none">
                        <label for="">Chagua kanda:</label>
                        <select name="kanda" id="kanda" class="form-control select2 " required>
                            <option value="" disabled>Chagua kanda</option>
                            @foreach (\App\Kanda::all(['id', 'jina_la_kanda']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="description" class="form-control" id="description" placeholder="Maelezo" rows="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="action-method" value="">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                </div>
            </form>

        </div>
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
    $("document").ready(function() {
        //turning on select2
        $('#jumuiya').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya..',
            allowClear: true,
            // width: '100%',
        });
        $('#eneo').on('change', function () {
            filter_eneo = $('#eneo').val()

            if (filter_eneo == 'jumuiya') {
                $('#kanda-div').css('display', 'none')
                $('#jumuiya-div').css('display', 'block')
            }

            if (filter_eneo == 'parokia') {
                $('#kanda-div').css('display', 'none')
                $('#jumuiya-div').css('display', 'none')
            }

            if (filter_eneo == 'kanda') {
                $('#jumuiya-div').css('display', 'none')
                $('#kanda-div').css('display', 'block')
            }
        })

        //handling the submission of zaka form
        function ajaxCall1() {
            setTimeout(() => {
                $('#zakaForm').trigger('submit');
            }, 4500);
        }


        $('#addBtn').on('click', function () {
            $('#addModal').modal({backdrop: 'static', keyboard: false});
            $('#addModal').modal('show');
        })

        $('#addModal').on('hidden.bs.modal', function () {
            location.reload()
        });

    })
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
                    foreach(json_decode($sadaka_za_misa,true) ?? '' as $mwaka){
                        echo $mwaka['mwezi'];
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
                    foreach(json_decode($sadaka_za_misa,true) ?? '' as $kiasi){
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
