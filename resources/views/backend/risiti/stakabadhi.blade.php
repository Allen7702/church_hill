@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">   
            <div class="card-header">
                <h4>{{strtoupper($title_mkupuo)}}</h4>
            </div>
    
            <div class="card-body">
                
                <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                    
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-zaka-tab" data-toggle="pill" href="#pills-zaka" role="tab" aria-controls="pills-zaka" aria-selected="true">Matoleo ya Zaka</a>
                    </li>
    
                    <li class="nav-item">
                        <a class="nav-link" id="pills-michango-tab" data-toggle="pill" href="#pills-michango" role="tab" aria-controls="pills-michango" aria-selected="false">Matoleo ya Michango</a>
                    </li>
    
                </ul>
    
                <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-zaka" role="tabpanel" aria-labelledby="pills-zaka-tab">
                        
                        <form action="{{route('stakabadhi_zaka_mkupuo.post')}}" method="POST" id="mkupuoZakaForm">
                            @csrf

                            <div class="form-group">
                                <label for="">Ngazi:</label>
                                <select name="ngazi" id="ngazi_zaka_mkupuo" class="form-control" required>
                                    <option value="">Chagua ngazi</option>
                                    <option value="risiti_za_wanajumuiya">Risiti za wanajumuiya</option>
                                    <option value="risiti_za_jumuiya">Risiti za jumuiya</option>
                                </select>
                            </div>

                            <div class="form-group" id="wanajumuiya_zaka_mkupuo_div">
                                <label for="">Jumuiya:</label>
                                <select type="text" name="wanajumuiya_zaka_mkupuo" class="form-control select2" id="wanajumuiya_zaka_mkupuo">
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($jumuiyas as $data_zaka_jumuiya)
                                        <option value="{{$data_zaka_jumuiya->id}}">{{$data_zaka_jumuiya->jina_la_jumuiya}}</option>
                                    @endforeach     
                                </select>
                            </div>
    
                            <div class="form-group" id="kanda_jumuiya_zaka_mkupuo_div">
                                <label for="">Risiti za jumuiya:</label>
                                <select name="kanda_zaka" id="kanda_zaka" class="form-control select2">
                                    <option value="">Chagua kanda</option>
                                    @foreach ($kanda as $item)
                                        <option value="{{$item->jina_la_kanda}}">{{$item->jina_la_kanda}}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="form-group">
                                <label for="">Tarehe:</label>
                                <input type="date" name="tarehe" class="form-control" style="padding:8px;" id="tarehe" required>
                            </div>
    
                            <div class="form-group">
                                <button type="reset" class="btn btn-sm btn-warning mr-2">Anzisha</button>
                                <button type="submit" class="btn btn-sm btn-info">Wasilisha</button>
                            </div>
                        </form>
    
                    </div>
    
                    <div class="tab-pane fade" id="pills-michango" role="tabpanel" aria-labelledby="pills-michango-tab">
                        <form action="{{route('stakabadhi_mchango_mkupuo.post')}}" method="POST" id="mkupuoMchangoForm">
                            @csrf

                            <div class="form-group">
                                <label for="">Ngazi:</label>
                                <select name="ngazi" id="ngazi_mchango_mkupuo" class="form-control" required>
                                    <option value="">Chagua ngazi</option>
                                    <option value="risiti_za_wanajumuiya">Risiti za wanajumuiya</option>
                                    <option value="risiti_za_jumuiya">Risiti za jumuiya</option>
                                </select>
                            </div>

                            <div class="form-group" id="wanajumuiya_mchango_mkupuo_div">
                                <label for="">Jumuiya:</label>
                                <select type="text" name="wanajumuiya_mchango_mkupuo" class="form-control select2" id="wanajumuiya_mchango_mkupuo">
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($jumuiyas as $data_mchango_jumuiya)
                                        <option value="{{$data_mchango_jumuiya->id}}">{{$data_mchango_jumuiya->jina_la_jumuiya}}</option>
                                    @endforeach     
                                </select>
                            </div>
    
                            <div class="form-group" id="kanda_jumuiya_mchango_mkupuo_div">
                                <label for="">Kanda:</label>
                                <select name="kanda_mchango" id="kanda_mchango" class="form-control select2">
                                    <option value="">Chagua kanda</option>
                                    @foreach ($kanda as $item)
                                        <option value="{{$item->jina_la_kanda}}">{{$item->jina_la_kanda}}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="form-group">
                                <label for="">Aina za michango:</label>
                                <select name="aina_ya_mchango" id="aina_ya_mchango_mkupuo" class="form-control select2" required>
                                    <option value="">Chagua mchango</option>
                                    @foreach ($aina_za_michango as $item)
                                        <option value="{{$item->aina_ya_mchango}}">{{$item->aina_ya_mchango}}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="form-group">
                                <label for="">Tarehe:</label>
                                <input type="date" name="tarehe" class="form-control" style="padding:8px;" id="tarehe" required>
                            </div>
    
                            <div class="form-group">
                                <button type="reset" class="btn btn-sm btn-warning mr-2">Anzisha</button>
                                <button type="submit" class="btn btn-sm btn-info">Wasilisha</button>
                            </div>
    
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>{{strtoupper($title_kawaida)}}</h4>
            </div>
            <div class="card-body">

                <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                    
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-zaka-kawaida-tab" data-toggle="pill" href="#pills-zaka-kawaida" role="tab" aria-controls="pills-home" aria-selected="true">Matoleo ya zaka</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-michango-kawaida-tab" data-toggle="pill" href="#pills-michango-kawaida" role="tab" aria-controls="pills-michango-kawaida" aria-selected="false">Matoleo ya michango</a>
                    </li>
                    
                </ul>

                <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-zaka-kawaida" role="tabpanel" aria-labelledby="pills-zaka-kawaida-tab">
                        <form action="{{route('stakabadhi_zaka_kawaida.post')}}" method="POST" id="zakaKawaidaForm">
                            @csrf

                            <div class="form-group">
                                <label for="">Ngazi:</label>
                                <select name="ngazi" id="ngazi_zaka_kawaida" class="form-control" required>
                                    <option value="">Chagua ngazi</option>
                                    <option value="jumuiya">Jumuiya</option>
                                    <option value="mwanajumuiya">Mwanajumuiya</option>
                                </select>
                            </div>

                            <div class="form-group" id="jumuiya_zaka_kawaida_div">
                                <label for="">Jumuiya:</label>
                                <select type="text" name="jumuiya_zaka_kawaida" class="form-control select2" id="jumuiya_zaka_kawaida">
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($jumuiyas as $data_zaka_jumuiya)
                                        <option value="{{$data_zaka_jumuiya->id}}">{{$data_zaka_jumuiya->jina_la_jumuiya}}</option>
                                    @endforeach     
                                </select>
                            </div>

                            <div class="form-group" id="muumini_zaka_kawaida_div">
                                <label for="">Muumini:</label>
                                <select type="text" name="muumini" class="form-control select2" id="muumini_zaka_kawaida">
                                    <option value="">Chagua muumini</option>
                                    @foreach ($waumini as $data_muumini)
                                        <option value="{{$data_muumini->mwanafamilia_id}}">{{$data_muumini->jina_kamili}} ({{$data_muumini->jina_la_jumuiya}})</option>
                                    @endforeach     
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Tarehe:</label>
                                <input type="date" name="tarehe" class="form-control" style="padding:8px;" id="tarehe" required>
                            </div>
                            
                            <div class="form-group">
                                <button type="reset" class="btn btn-sm btn-warning mr-2">Anzisha</button>
                                <button type="submit" class="btn btn-sm btn-info">Wasilisha</button>
                            </div>

                        </form>
                    
                    </div>

                    <div class="tab-pane fade" id="pills-michango-kawaida" role="tabpanel" aria-labelledby="pills-michango-kawaida-tab">
                        <form action="{{route('stakabadhi_mchango_kawaida.post')}}" method="POST" id="mchangoKawaidaForm">
                            @csrf

                            <div class="form-group">
                                <label for="">Ngazi:</label>
                                <select name="ngazi" id="ngazi_mchango_kawaida" class="form-control" required>
                                    <option value="">Chagua ngazi</option>
                                    <option value="jumuiya">Jumuiya</option>
                                    <option value="mwanajumuiya">Mwanajumuiya</option>
                                </select>
                            </div>

                            <div class="form-group" id="jumuiya_mchango_kawaida_div">
                                <label for="">Jumuiya:</label>
                                <select type="text" name="jumuiya_mchango_kawaida" class="form-control select2" id="jumuiya_mchango_kawaida">
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($jumuiyas as $data_mchango_jumuiya)
                                        <option value="{{$data_mchango_jumuiya->id}}">{{$data_mchango_jumuiya->jina_la_jumuiya}}</option>
                                    @endforeach     
                                </select>
                            </div>

                            <div class="form-group" id="muumini_mchango_kawaida_div">
                                <label for="">
                                    Muumini:
                                </label>

                                <select type="text" name="muumini" class="form-control select2" id="muumini_mchango_kawaida">
                                    <option value="">Chagua muumuni</option>
                                    @foreach ($waumini as $item)
                                        <option value="{{$item->mwanafamilia_id}}">{{$item->jina_kamili}} - ({{$item->jina_la_jumuiya}})</option>
                                    @endforeach  
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Aina ya mchango:</label>
                                <select name="aina_ya_mchango" id="aina_ya_mchango_kawaida" class="form-control select2" required>
                                    <option value="">Chagua mchango</option>
                                    
                                    @foreach ($aina_za_michango as $item)
                                        <option value="{{$item->aina_ya_mchango}}">{{$item->aina_ya_mchango}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Tarehe:</label>
                                <input type="date" name="tarehe" class="form-control" style="padding:8px;" id="tarehe" required>
                            </div>
                            
                            <div class="form-group">
                                <button type="reset" class="btn btn-sm btn-warning mr-2">Anzisha</button>
                                <button type="submit" class="btn btn-sm btn-info">Wasilisha</button>
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

        $('#jumuiya_zaka_kawaida_div').hide();
        $('#muumini_zaka_kawaida_div').hide();
        $('#jumuiya_mchango_kawaida_div').hide();
        $('#muumini_mchango_kawaida_div').hide();
        $('#wanajumuiya_zaka_mkupuo_div').hide();
        $('#kanda_jumuiya_zaka_mkupuo_div').hide();
        $('#wanajumuiya_mchango_mkupuo_div').hide();
        $('#kanda_jumuiya_mchango_mkupuo_div').hide();

        //reseting values
        $('#kanda_zaka').val('').trigger('change');
        $('#kanda_mchango').val('').trigger('change');
        $('#aina_ya_mchango_mkupuo').val('').trigger('change');
        $('#muumini_mchango_kawaida').val('').trigger('change');
        $('#ngazi_zaka_kawaida').val('').trigger('change');
        $('#muumini_zaka_kawaida').val('').trigger('change');
        $('#aina_ya_mchango_kawaida').val('').trigger('change');
        $('#mkupuoZakaForm')[0].reset();
        $('#mkupuoMchangoForm')[0].reset();
        $('#mchangoKawaidaForm')[0].reset();
        $('#zakaKawaidaForm')[0].reset();

        //loading the select2 plugin for kanda
        $('#kanda_zaka').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta kanda...',
            allowClear: true,
            width: '100%',
        });

        $('#kanda_mchango').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta kanda...',
            allowClear: true,
            width: '100%',
        });

        $('#aina_ya_mchango_mkupuo').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta aina...',
            allowClear: true,
            width: '100%',
        });

        $('#muumini_zaka_kawaida').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta muumini...',
            allowClear: true,
            width: '100%',
        });

        $('#muumini_mchango_kawaida').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta muumini...',
            allowClear: true,
            width: '100%',
        });

        $('#aina_ya_mchango_kawaida').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta mchango...',
            allowClear: true,
            width: '100%',
        });

        $('#jumuiya_zaka_kawaida').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });

        $('#jumuiya_mchango_kawaida').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });

        $('#wanajumuiya_zaka_mkupuo').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });

        $('#wanajumuiya_mchango_mkupuo').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });

        //focusing on changes of data
    
        //switching ngazi
        $('#ngazi_zaka_kawaida').on('change',function(){
            if($(this).val() == "jumuiya"){
                $('#jumuiya_zaka_kawaida_div').show();
                $('#muumini_zaka_kawaida_div').hide();
            }
            else if($(this).val() == "mwanajumuiya"){
                $('#jumuiya_zaka_kawaida_div').hide();
                $('#muumini_zaka_kawaida_div').show();
            }
            else{
                $('#jumuiya_zaka_kawaida_div').hide();
                $('#muumini_zaka_kawaida_div').hide();
            }
        })

        //switching ngazi
        $('#ngazi_zaka_mkupuo').on('change',function(){
            if($(this).val() == "risiti_za_wanajumuiya"){
                $('#wanajumuiya_zaka_mkupuo_div').show();
                $('#kanda_jumuiya_zaka_mkupuo_div').hide();
            }
            else if($(this).val() == "risiti_za_jumuiya"){
                $('#wanajumuiya_zaka_mkupuo_div').hide();
                $('#kanda_jumuiya_zaka_mkupuo_div').show();
            }
            else{
                $('#wanajumuiya_zaka_mkupuo_div').hide();
                $('#kanda_jumuiya_zaka_mkupuo_div').hide();
            }
        })

        //switching ngazi
        $('#ngazi_mchango_mkupuo').on('change',function(){
            if($(this).val() == "risiti_za_wanajumuiya"){
                $('#wanajumuiya_mchango_mkupuo_div').show();
                $('#kanda_jumuiya_mchango_mkupuo_div').hide();
            }
            else if($(this).val() == "risiti_za_jumuiya"){
                $('#wanajumuiya_mchango_mkupuo_div').hide();
                $('#kanda_jumuiya_mchango_mkupuo_div').show();
            }
            else{
                $('#wanajumuiya_mchango_mkupuo_div').hide();
                $('#kanda_jumuiya_mchango_mkupuo_div').hide();
            }
        })

        //switching ngazi
        $('#ngazi_mchango_kawaida').on('change',function(){
            if($(this).val() == "jumuiya"){
                $('#jumuiya_mchango_kawaida_div').show();
                $('#muumini_mchango_kawaida_div').hide();
            }
            else if($(this).val() == "mwanajumuiya"){
                $('#jumuiya_mchango_kawaida_div').hide();
                $('#muumini_mchango_kawaida_div').show();
            }
            else{
                $('#jumuiya_mchango_kawaida_div').hide();
                $('#muumini_mchango_kawaida_div').hide();
            }
        })

        //switching ngazi
        $('#ngazi_mchango_kawaida').on('change',function(){
            if($(this).val() == "jumuiya"){
                $('#jumuiya_mchango_kawaida_div').show();
                $('#muumini_mchango_kawaida_div').hide();
            }
            else if($(this).val() == "mwanajumuiya"){
                $('#jumuiya_mchango_kawaida_div').hide();
                $('#muumini_mchango_kawaida_div').show();
            }
            else{
                $('#jumuiya_mchango_kawaida_div').hide();
                $('#muumini_mchango_kawaida_div').hide();
            }
        })

    })
    
</script>

@endsection