@extends('layouts.master')
@section('content')

<body onload="loadingPage()">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Ripoti za masakramenti</h3>
                        </div>
                    </div>
                </div>
    
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <form method="POST" action="{{route('masakramenti_ripoti_generate')}}" id="masakramentiForm">
    
                                @csrf

                                <div class="form-group">
                                    <label for="">Aina ya ripoti:</label>
                                    <select name="aina_ya_ripoti" id="aina_ya_ripoti" class="form-control select2" required>
                                        <option value="">Chagua ripoti</option>
                                        <option value="sensa_waamini">Sensa ya waamini</option>
                                    </select>
                                </div>
    
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Ngazi:</label>
                                            <select name="ngazi" id="ngazi" class="form-control" required>
                                                <option value="">Chagua ngazi</option>
                                                <option value="Familia">Familia</option>
                                                <option value="Jumuiya">Jumuiya</option>
                                            </select>
                                        </div>
            
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Mchujo:</label>
                                            <select name="mchujo" id="mchujo" class="form-control">
                                                <option value="tarehe_zote">Tarehe zote</option>
                                                <option value="tarehe_husika">Tarehe husika</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="form-group" id="tareheDiv">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Kuanzia:</label>
                                            <input type="date" class="form-control" style="padding:7px;" name="kuanzia">
                                        </div>
            
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Hadi tarehe:</label>
                                            <input type="date" class="form-control" style="padding:7px;" name="kuanzia">
                                        </div>
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12" id="familiaDiv">
                                            <label for="">Familia:</label>
                                            <select name="familia" id="familia" class="form-control">
                                                @foreach ($orodha_familia as $item)
                                                    <option value="{{$item->id}}">{{$item->jina_la_familia}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                
                                        <div class="col-sm-12 col-md-12" id="jumuiyaDiv">
                                            <label for="jumuiya">Jumuiya:</label>
                                            <select name="jumuiya" class="form-control select2" id="jumuiya">
                                                @foreach ($orodha_jumuiya as $item)
                                                    <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <button class="btn btn-sm btn-info">Wasilisha &nbsp;&nbsp;<i class="fas fa-fw fa-check-circle"></i></button>
                                </div>
                                    
                            </div>
            
                                
                            </form>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div> 
    
    <script>
        $("document").ready(function(){
            //callling the custom select
    
            $('#aina_ya_ripoti').select2({
                theme: 'bootstrap4',
                placeholder: 'chagua ripoti...',
                allowClear: true,
                width: '100%',
            });
    
            $('#jumuiya').select2({
                theme: 'bootstrap4',
                placeholder: 'tafuta jumuiya...',
                allowClear: true,
                width: '100%',
            });
    
            $('#familia').select2({
                theme: 'bootstrap4',
                placeholder: 'tafuta familia...',
                allowClear: true,
                width: '100%',
            });
    
            //switching the familia and jumuiya
            $('#ngazi').on('change',function(){
                if($(this).val() == "Familia"){
                    $('#jumuiyaDiv').hide();
                    $('#familiaDiv').show();
                }
    
                else if($(this).val() == "Jumuiya"){
                    $('#jumuiyaDiv').show();
                    $('#familiaDiv').hide();
                }
                else{
                    $('#jumuiyaDiv').hide();
                    $('#familiaDiv').hide();
                }
            })

            //switching the dates
            $('#mchujo').on('change',function(){
                if($(this).val() == "tarehe_husika"){
                    $('#tareheDiv').show();
                }
                else{
                    $('#tareheDiv').hide();
                }
            })
        })
    </script>

    <script>
        function loadingPage(){
            //initially hide the familia
            $('#familiaDiv').hide();
            $('#jumuiyaDiv').hide();
            $('#tareheDiv').hide();
            $('#masakramentiForm')[0].reset();
            $('#aina_ya_ripoti').val('').trigger('change');
            $('#familia').val('').trigger('change');
            $('#jumuiya').val('').trigger('change');
        }
    </script>
</body>    
@endsection