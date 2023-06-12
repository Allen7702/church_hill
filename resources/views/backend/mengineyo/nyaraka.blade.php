@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>NYARAKA MBALIMBALI</h4>
                </div>
                <div class="card-body">
                    <ul style="list-style-type: none;">

                        <li>
                            <a href="{{url('uploads/nyaraka/kanda.xls')}}" style="text-decoration: none;"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka ya kupakia {{$sahihisha_kanda->name}}</h4></a>
                        </li>

                        <li>
                            <a href="{{url('uploads/nyaraka/jumuiya.xls')}}" style="text-decoration: none;"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka ya kupakia jumuiya</h4></a>
                        </li>

                        <li>
                            <a href="{{url('uploads/nyaraka/familia.xls')}}" style="text-decoration: none;"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka ya kupakia familia</h4></a>
                        </li>

                        <li>
                            <a href="#" id="chooseJumuiyaFamiliaBtn" style="text-decoration: none;"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka ya kupakia wanafamilia</h4></a>
                            <!-- href="{{url('uploads/nyaraka/wanafamilia.xls')}}" -->

                        </li>
                        <li>
                            <a href="#" style="text-decoration: none;" id="chooseJumuiyaBtn"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka za kupakia zaka <span style="color:rgb(224, 94, 54)">(Mfumo unapokea tarehe wenye format dd/mm/YY)</span></h4></a>
                        </li>

                        <li>
                            <a href="{{url('uploads/nyaraka/majina.xls')}}"  style="text-decoration: none;"><h4><i class="fas fa-fw fa-angle-double-right"></i> &nbsp;Nyaraka ya kutengeneza familia, wanafamilia (wanajumuiya)</h4></a>
                        </li>
                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="jumuiyaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
    
                <form id="jumuiyaForm" method="POST" action="{{route('pukua_jumuiya.excel')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="createDiv">
                            <label for="">Changua jumuiya:</label>
                            <select name="jina_la_jumuiya" id="jina_la_jumuiya" class="form-control select2">
                                <option value="">Chagua jumuiya</option>
                                @foreach ($jumuiyas as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                        <input type="hidden" name="action" id="action" value="">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Pakua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="jumuiyaFamiliaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
    
                <form id="jumuiyaFamiliaForm" method="POST" action="{{route('pukua_jumuiya_mwanafamilia.excel')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="createDiv">
                            <label for="">Changua jumuiya:</label>
                            <select name="jina_la_jumuiya" id="jina_la_jumuiya" class="form-control select2">
                                <option value="">Chagua jumuiya</option>
                                @foreach ($jumuiyas as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                        <input type="hidden" name="action" id="action" value="">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Pakua</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
              //turning on the 
              
              $('#jina_la_jumuiya').select2({

            dropdownParent: $('#jumuiyaModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });
        $('#chooseJumuiyaBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#jumuiyaForm')[0].reset();
            $('.modal-title').text('Chagua jumuiya');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Pakua');
            $('#jumuiyaModal').modal({backdrop: 'static',keyboard: false});
            $('#jumuiyaModal').modal('show');
        })


        $('#chooseJumuiyaFamiliaBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#jumuiyaFamiliaForm')[0].reset();
            $('.modal-title').text('Chagua jumuiya');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Pakua');
            $('#jumuiyaFamiliaModal').modal({backdrop: 'static',keyboard: false});
            $('#jumuiyaFamiliaModal').modal('show');
        });


        })

    </script>
@endsection
