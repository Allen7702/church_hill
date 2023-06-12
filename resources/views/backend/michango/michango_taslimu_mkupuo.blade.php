@extends('layouts.master')
@section('content')
<div class="card">

    <div class="card-header">
        <div class="row">

            <div class="col-md-7 mt-3">
                <h3>{{strtoupper($title)}}</h3>
            </div>

            <div class="col-md-5">

                <form name="initial_form" id="initialForm" method="POST" action="{{route('michango_taslimu_mkupuo.vuta_jumuiya')}}">
                    @csrf
                    <div class="form-group">
                        <select name="jumuiya" id="jumuiya" class="form-control select2" required>
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiya as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}-{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div class="card-body">

        <p class="text-primary">Hakiki taarifa zilizojazwa kabla ya kuwasilisha mchango husika</p>

        <form id="michangoForm" method="POST" action="{{route('michango_taslimu_mkupuo.store')}}">
        
            @csrf
    
            @if($action == "mkupuo")
    
                <div class="form-group">
                    
                    <div class="row">
    
                        <div class="col-md-4">
                            <input type="text" name="jumuiya" class="form-control" value="{{$selected_jumuiya}}" style="padding:8px;" required>
                        </div>

                        <div class="col-md-4">
                            <select name="aina_ya_mchango" id="aina_ya_mchango" class="form-control" required>
                                <option value="">Chagua aina ya mchango</option>
                                @foreach ($aina_za_michango as $michango)
                                    <option value="{{$michango->aina_ya_mchango}}">{{$michango->aina_ya_mchango}}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-md-4">
                            <input type="date" id="tarehe" name="tarehe" class="form-control" style="padding:8px;" required>
                        </div>
    
                    </div>
                    <hr>
                    
                </div>
            
                <div class="form-group">
                    <div class="row">
                        @foreach ($members as $row)
                        <div class="col-md-4 mb-3">
                            <label>{{$row->jina_kamili}}</label>
                            <input type="hidden" name="record[{{$loop->index+0}}][mwanajumuiya]" value="{{$row->mwanajumuiya_id}}">
                            <input type="number" name="record[{{$loop->index+0}}][kiasi]" class="kiasi form-control" step="any" min="0.0" placeholder="{{$row->jina_kamili}}">
                        </div>
                        @endforeach
                    </div>
                    {{-- <hr> --}}
                </div>
    
            @endif
    
            <div class="form-group">
                <button type="reset" class="btn btn-warning mr-2">Anza upya</button>
                <button id="wasilishaMichangoBtn" type="button" class="btn btn-info">Wasilisha sadaka</button>
            </div>
    
        </form>

    </div>

</div>

<div id="denominationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                </button>
            </div>

        <form id="denominationForm">
            <div class="modal-body">

                    @csrf
                
                    {{-- <hr> --}}
                    <b class="text-primary">Noti (Idadi ya noti husika)</b>
                    <hr>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">10,000 &nbsp; X </span>
                            </div>
                            <input type="number" min="0" name="noti_10000" id="n10000" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">5,000 &nbsp; X </span>
                                    </div>
                                    <input type="number" min="0" name="noti_5000" id="n5000" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">2,000 &nbsp; X </span>
                                    </div>
                                    <input type="number" id="n2000" name="noti_2000" min="0" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">1,000 &nbsp; X </span>
                                    </div>
                                    <input type="number" id="n1000" name="noti_1000" min="0" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">&nbsp;&nbsp;&nbsp;500 &nbsp; X </span>
                                    </div>
                                    <input type="number" id="n500" min="0" name="noti_500" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- <hr> --}}
                    <b class="text-primary">Sarafu (Idadi ya sarafu husika)</b>
                    <hr>

                    <div class="form-group" style="margin-bottom:0;">
                        <div class="row" style="margin-bottom:0;">
                            
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">500 &nbsp; X </span>
                                    </div>
                                    <input type="number" min="0" id="s500" name="sarafu_500" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">200 &nbsp; X </span>
                                    </div>
                                    <input type="number" min="0" id="s200" name="sarafu_200" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row"">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">100 &nbsp; X </span>
                                    </div>
                                    <input type="number" min="0"  id="s100" name="sarafu_100" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">50 &nbsp; X </span>
                                    </div>
                                    <input type="number" min="0" id="s50" name="sarafu_50" class="form-control form-control-sm" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="jumla_kuu_modal"><b>Jumla kuu</b>:</span>
                            </div>
                            <input type="number" readonly="" id="jumla_kuu_denomination" class="form-control form-control-sm" placeholder="0" aria-label="Jumla kuu" aria-describedby="basic-addon1">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="hidden_aina" id="hidden_aina">
                    <input type="hidden" name="hidden_date" id="hidden_date">
                    <button class="btn btn-warning btn-sm mr-2" data-dismiss="modal">Funga <i class="fas fa-fw fa-times-circle"></i></button>
                    <button id="denoBtn" class="btn btn-info btn-sm">Wasilisha <i class="fas fa-fw fa-check-circle"></i></button>
                </div>  
            </form>
        </div>
    </div>
</div>

<script>

    $("document").ready(function(){
        //declaring the value of michango total
        var michango_total_overall = 0;
        var denomination_total_overall = 0;

        //reseting the value of select two
        $('#jumuiya').val('').trigger('change');
        $('#michangoForm')[0].reset();

        //turning on select2
        $('#jumuiya').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya..',
            allowClear: true,
            // width: '100%',
        });

        $('#initialForm,select2').on('change', function(){
            $('#initialForm').submit();
            // $(this).closest('form').submit();
        });

        //handling all the input of michango
        $("#michangoForm,.kiasi").on("input",function() {
            var michango_total =0;
            $(".kiasi").each(function(){
                if(!isNaN(parseInt($(this).val())))
                {
                    michango_total+=parseInt($(this).val());  
                }
            });

            michango_total_overall = michango_total;
        });

        //function to handle the submit click
        $('#wasilishaMichangoBtn').on('click',function(event){
            event.preventDefault();

            //before sending data do this
            $('.modal-title').text('Uthibitishaji wa fedha');
            $('#denominationForm')[0].reset();
            $('#denominationModal').modal('show');

            //tracking the total for denomination
            var denomination_total = 0;

            //monitoring the changes of input
            $("#denominationForm,input").on('change',function(){
                //letting sum of denomination
                denomination_total = parseInt(($('#n10000').val())*10000) + parseInt(($('#n5000').val())*5000) + parseInt(($('#n2000').val())*2000) + 
                parseInt(($('#n1000').val())*1000) + parseInt(($('#n500').val())*500) + parseInt(($('#s500').val())*500) + parseInt(($('#s200').val())*200)
                + parseInt(($('#s100').val())*100) + parseInt(($('#s50').val())*50);

                //assigning the total to denomination
                $('#jumla_kuu_denomination').val(denomination_total);
                denomination_total_overall = denomination_total;
            }) 
        })

        //when user clicks the 
        $('#denoBtn').on('click',function(){

            //user skipped filling the date
            if($('#aina_ya_mchango').val() == ""){
                Toast.fire({
                    title: "Tafadhali chagua aina ya mchango husika..",
                    icon: "info",
                });

                return false;
            }
            
            //user skipped filling the date
            if($('#tarehe').val() == ""){
                Toast.fire({
                    title: "Tafadhali jaza tarehe kwa majitoleo husika..",
                    icon: "info",
                });

                return false;
            }

            
            if(michango_total_overall != denomination_total_overall){
                
                Swal.fire({
                    title: "<b class='text-primary'>Taarifa!</b>",
                    icon: "info",
                    text: "Tafadhali hakiki tena kiasi kilichojazwa kwakua hakiendani na idadi ya noti na sarafu zilizojazwa..",
                    timer: 8000,
                    showConfirmButton: false,
                    showCancelButton: false,
                })
                return false;
            }

            //we are now correct
            else{

            //getting the value of aina ya michango to be in hidden aina
            var aina_data = $('#aina_ya_mchango').val();
            var tarehe_data = $('#tarehe').val();

            //assigning aina ya toleo
            $('#hidden_aina').val(aina_data);

            //assigning tarehe husika
            $('#hidden_date').val(tarehe_data);

            //handling the submission of denomination form
            $('#denominationForm').submit(function(event){
                event.preventDefault();

                    var url_data = "{{route('denomination.store')}}";

                    $.ajax({
                        url: url_data,
                        method: "POST",
                        dataType: "JSON",
                        data: $(this).serialize(),
                        success: function(data){

                            //if we have a successfully request
                            if(data.success){

                                $('#denominationModal').modal('hide');

                                var message = data.success;
                                
                                Toast.fire({
                                    title: message,
                                    icon: "success",
                                })

                                michangoCall();
                            }

                            //if we have a wrong request
                            if(data.errors){
                                var message = data.errors;
                                Toast.fire({
                                    title: message,
                                    icon: "info",
                                })
                            }
                        }
                    })
                })
            }  
        })

    })
</script>

<script>

    //handling the submission of michango form
    function michangoCall(){
        setTimeout(() => {
            $('#michangoForm').trigger('submit');
        },4500);
    }
    
</script>
@endsection