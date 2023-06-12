@extends('layouts.master')
@section('content')

<body onload="loadingPage()">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{$title}}</h3>
                        </div>
                    </div>
                </div>
    
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <form method="POST" action="{{route('fedha_ripoti_generate')}}" id="fedhaForm">
    
                                @csrf

                                <div class="form-group">
                                    <label for="">Aina ya ripoti:</label>
                                    <select name="aina_ya_ripoti" id="aina_ya_ripoti" class="form-control select2" required>
                                        <option value="">Chagua ripoti</option>
                                        <option value="mapato">Mapato</option>
                                        <option value="matumizi">Matumizi</option>
                                        <option value="mapato_matumizi">Mapato na Matumizi</option>
                                        <option value="mizania">Balance sheet</option>
                                    </select>
                                </div>
    
                                <div class="form-group">
                                    <label for="">Mchujo:</label>
                                    <select name="mchujo" id="mchujo" class="form-control">
                                        <option value="tarehe_zote">Mwaka huu</option>
                                        <option value="tarehe_husika">Tarehe husika</option>
                                    </select>
                                </div>
    
                                <div class="form-group" id="tareheDiv">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Kuanzia:</label>
                                            <input type="date" class="form-control" style="padding:7px;" name="kuanzia">
                                        </div>
            
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Hadi tarehe:</label>
                                            <input type="date" class="form-control" style="padding:7px;" name="ukomo">
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
            //initially hide the un-necessary fields
            $('#tareheDiv').hide();
            $('#fedhaForm')[0].reset();
            $('#aina_ya_ripoti').val('').trigger('change');
        }
    </script>
</body>    
@endsection