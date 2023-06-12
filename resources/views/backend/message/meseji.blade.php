@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row mb-0">
                        <div class="col-md-6">
                            <h4>UKURASA WA MESEJI</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            {{-- <h4>{{Carbon::now()->format('d-m-Y')}}</h4> --}}
                            <p id="clock" style="font-size: 15px;"></p>
                        </div>
                    </div>
                </div>
        
                <div class="card-body">
                    <div class="col-md-12 m-0">
                        <form id="message_form">
        
                            @csrf
        
                            <div class="form-group">
                                <label for="">Mpokeaji:</label>
                                <select name="mpokeaji" id="mpokeaji" class="form-control" required>
                                    <option value="">Chagua mpokeaji (wapokeaji)</option>
                                    <option value="chama_cha_kitume">Chama cha kitume</option>
                                    <option value="viongozi">Viongozi</option>
                                    <option value="familia">Familia</option>
                                    <option value="jumuiya">Jumuiya</option>
                                    <option value="mawasiliano">Mawasiliano</option>
                                </select>
                            </div>

                            <div class="form-group" id="chamaDiv">
                                <label for="">Chama cha kitume:</label>
                                <select name="chama[]" multiple="multiple" id="chama" class="select2">
                                    <option value="vyama_vyote">Vyama vyote</option>
                                    
                                    @foreach ($data_vyama as $chama)
                                        <option value="{{$chama->id}}">{{$chama->jina_la_kikundi}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="viongoziDiv">
                                <label for="">Viongozi:</label>
                                <select name="viongozi[]" multiple="multiple" id="viongozi" class="form-control select2">
                                    <option value="viongozi_wote">Viongozi wote</option>
                                    @foreach ($data_viongozi as $viongozi)
                                        <option value="{{$viongozi->jina_la_cheo}}">{{$viongozi->jina_la_cheo}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="familiaDiv">
                                <label for="">Familia:</label>
                                <select name="familia[]" id="familia" multiple="multiple" class="form-control select2">
                                    <option value="familia_zote">Familia zote</option>
                                    @foreach ($data_familia as $familia)
                                        <option value="{{$familia->id}}">{{$familia->jina_la_familia}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="jumuiyaDiv">
                                <label for="">Jumuiya:</label>
                                <select name="jumuiya[]" id="jumuiya" multiple="multiple" class="form-control select2">
                                    <option value="jumuiya_zote">Jumuiya zote</option>
                                    @foreach ($data_jumuiya as $jumuiya)
                                        <option value="{{$jumuiya->id}}">{{$jumuiya->jina_la_jumuiya}} ({{$jumuiya->jina_la_kanda}})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="mawasilianoDiv">
                                <label for="">Mawasiliano:</label>
                                <input type="text" name="mawasiliano" class="form-control" id="mawasiliano" placeholder="0*********" pattern="[0-9]{10}">
                            </div>
        
                            <div class="form-group">
                                <label for="">Ujumbe:</label>
                                <textarea name="ujumbe" id="ujumbe" class="form-control" rows="5" placeholder="Andika ujumbe wako hapa" required></textarea>
                            </div>
        
                            <div class="form-group">
                                <button type="reset" class="btn btn-warning mr-2 btn-sm">Futa ujumbe &nbsp;&nbsp;<i class="fas fa-fw fa-times-circle"></i></button>
                                <button type="submit" class="btn btn-info btn-sm">Tuma ujumbe &nbsp;&nbsp;<i class="fas fa-fw fa-envelope"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 pr-md-0">
            <div class="card card-pricing">
                <div class="card-header">
                    <h4 class="card-title">Takwimu za Meseji</h4>
                    <div class="card-price">
                        <span class="price">{{number_format($today_message)}}</span>
                        <span class="text">Siku ya leo</span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="specification-list">

                        <li>
                            <span class="name-specification">Wiki hii</span>
                            <span class="status-specification">{{number_format($this_week)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwezi huu</span>
                            <span class="status-specification">{{number_format($this_month)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwaka huu</span>
                            <span class="status-specification">{{number_format($this_year)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwaka jana</span>
                            <span class="status-specification">{{number_format($last_year)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Jumla ya meseji</span>
                            <span class="status-specification">{{number_format($total_messages)}}</span>
                        </li>
                        <li>
                            <span class="name-specification">Salio la meseji</span>
                            <span class="status-specification">{{number_format($sms_balance)}}</span>
                        </li>
                        
                    </ul>
                </div>
                
            </div>
        </div>
        
    </div>

    <script src="https://momentjs.com/downloads/moment.js"></script>

    <script>
        $("document").ready(function(){

            $('#chama').val(null).trigger('change');

            //loading the select2 plugin
            $('#chama,#viongozi,#familia,#jumuiya').select2({
                // dropdownParent: $('#chamaDiv'),
                theme: 'bootstrap4',
                // placeholder: 'chagua...',
                // allowClear: true,
                width: '100%',
            });

            //hiding divs
            $('#chamaDiv').hide();
            $('#viongoziDiv').hide();
            $('#familiaDiv').hide();
            $('#jumuiyaDiv').hide();

            //reseting the form
            $('#message_form')[0].reset();

            // the function to display time
            window.setInterval(function () {
            $('#clock').html(moment().format('ddd D/MM/Y H:mm:ss'))
            }, 1000);

            //dealing with characters
            var min = 0;

            //changing the mpokeaji value
            $('#mpokeaji').on('change',function(){
                //if the value is chama cha kitume
                if($(this).val() == "chama_cha_kitume"){
                    $('#viongoziDiv').hide();
                    $('#chamaDiv').show()
                    $('#mawasilianoDiv').hide();
                    $('#familiaDiv').hide();
                    $('#jumuiyaDiv').hide();
                }
                //case viongozi
                else if($(this).val() == "viongozi"){
                    $('#viongoziDiv').show();
                    $('#chamaDiv').hide()
                    $('#mawasilianoDiv').hide();
                    $('#familiaDiv').hide();
                    $('#jumuiyaDiv').hide();
                }

                //case familia
                else if($(this).val() == "familia"){
                    $('#familiaDiv').show();
                    $('#viongoziDiv').hide();
                    $('#chamaDiv').hide()
                    $('#mawasilianoDiv').hide();
                    $('#jumuiyaDiv').hide();
                }

                //case jumuiya
                else if($(this).val() == "jumuiya"){
                    $('#jumuiyaDiv').show();
                    $('#familiaDiv').hide();
                    $('#viongoziDiv').hide();
                    $('#chamaDiv').hide()
                    $('#mawasilianoDiv').hide();
                }

                else{
                    $('#jumuiyaDiv').hide();
                    $('#familiaDiv').hide();
                    $('#viongoziDiv').hide();
                    $('#chamaDiv').hide()
                    $('#mawasilianoDiv').show();
                }

            })

            //submitting data
            $('#message_form').on('submit',function(event){
                event.preventDefault();

                var action_url = "{{route('ukurasa_meseji.send_message')}}";
                $.ajax({
                    url: action_url,
                    data: $(this).serialize(),
                    method: "POST",
                    dataType: "JSON",
                    beforeSend:function(){
                        return confirm("Unakaribia kutuma meseji endelea?");
                    },
                    success: function(data){
                        
                        //successfully sent
                        if(data.success){
                            
                            //getting the message from server
                            var message = data.success;

                            //toasting the message
                            Toast.fire({
                                icon: 'success',
                                title: message,
                            })

                            //refreshing the page
                            setTimeout(function(){
                                window.location.reload();
                            },3600);
                        }

                        //we are having errors
                        if(data.errors){

                            var message = data.errors;

                            Swal.fire({
                                icon: 'info',
                                title: message,
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 4500,
                            })
                        }
                    }
                })
            })
            
        })
    </script>
    
@endsection