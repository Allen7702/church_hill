@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{$title}}</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="kiongoziBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>
                <a href="{{url('orodha_viongozi_husika_pdf',['id'=>$cheo])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('orodha_viongozi_husika_excel',['id'=>$cheo])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina kamili</th>
                <th>Barua pepe</th>
                <th>Simu</th>
                @if($cheo == "Halmashauri ya walei")
                <th>Jumuiya</th>
                @endif

                <th>Ruhusa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_viongozi as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_kamili}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->mawasiliano}}</td>
                        @if($cheo == "Halmashauri ya walei")
                        <td>{{$item->jumuiya}}</td>
                        @endif
                        <td>{{$item->ruhusa}}</td>
                        <td>
                            <button class="view btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

<div id="viongoziModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="viongoziForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">
                            
                            <div class="col-sm-12 col-md-4">
                                <label for="">Jina kamili:</label>
                                <input class="form-control" type="text" id="jina_kamili" name="jina_kamili" placeholder="Jina kamili" required>        
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Simu:</label>
                                <input class="form-control" pattern="[0-9]{10}" type="text" id="mawasiliano" name="mawasiliano" placeholder="Simu ya mkononi 0xxxxxxxxx" required>        
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Barua pepe:</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Barua pepe" required>  
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <label for="">Cheo:</label>
                                <select name="cheo" id="cheo" class="form-control" required>
                                    <option value="">Chagua cheo</option>
                                    @foreach ($data_vyeo as $row)
                                        <option value="{{$row->jina_la_cheo}}">{{$row->jina_la_cheo}}</option>
                                    @endforeach
                                </select>                                
                            </div>
                            
                            <div class="col-md-4" id="createDiv">
                                <label for="">Jumuiya:</label>
                                <select name="jumuiya" id="jumuiya" class="form-control select2">
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($data_jumuiya as $row1)
                                        <option value="{{$row1->jina_la_jumuiya}}">{{$row1->jina_la_jumuiya}} ({{$row1->jina_la_kanda}})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="updateDiv">
                                <label for="">Jumuiya:</label>

                                <div id="update">
                                    <select name="jumuiya_update" id="jumuiya_update" class="form-control">
                                        <option value="">Chagua jumuiya</option>
                                        @foreach ($data_jumuiya as $row1)
                                            <option value="{{$row1->jina_la_jumuiya}}">{{$row1->jina_la_jumuiya}} ({{$row1->jina_la_kanda}})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="preview">
                                    <input type="text" name="preview" id="preview_data" class="form-control" placeholder="Jumuiya">
                                </div>
                                
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Ruhusa:</label>
                                <select name="ruhusa" class="form-control" id="ruhusa" required>
                                    <option value="">Chagua ruhusa</option>
                                    <option value="kuangalia">Kuangalia taarifa</option>
                                    <option value="kuongeza">Kuongeza taarifa</option>
                                    <option value="zote">Kila kitu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <label for="">Anwani:</label>
                                <input class="form-control" type="text" id="anwani" name="anwani" placeholder="Anwani" required>
                            </div>

                            <div class="col-md-8" id="passwordDiv">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="">Nywila/neno la siri:</label>
                                        <input class="form-control" type="password" id="password" name="password" placeholder="Neno la siri">
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="">Hakiki Nywila(rudia):</label>
                                        <input class="form-control" type="password" id="password-confirm" name="password-confirm" placeholder="Rudia nywila yako">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    $("document").ready(function(){

        //loading the select2 plugin
        $('#jumuiya').select2({
            dropdownParent: $('#viongoziModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya...',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#kiongoziBtn').on('click', function(){
            $('#jumuiya').val('').trigger('change');
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#preview').hide();
            $('#submitBtn').show();
            $('#jumuiya').attr('disabled',true);
            $('#viongoziForm')[0].reset();
            $('.modal-title').text('Ongeza mtumiaji');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#viongoziModal').modal({backdrop: 'static',keyboard: false});
            $('#viongoziModal').modal('show');

            //changing the forms value
            $('#cheo').on('change',function(){
                if(($(this).val() == "Halmashauri ya walei")){
                    $('#jumuiya').attr('disabled',false);
                }
                else{
                    $('#jumuiya').attr('disabled',true);
                }
            })
        })

        //submitting values
        $('#viongoziForm').on('submit', function(event){
            event.preventDefault();

            var pass1 = $("#password").val();
            var pass2 = $("#password-confirm").val();

            if (pass1 != pass2) {

                Swal.fire({
                    title: "Taarifa!",
                    text: "Nywila hazifanani, badilisha",
                    icon: "info",
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500,
                });

                return false;
            }

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('users.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('users.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //disable the submit button
                    $("#submitBtn").attr("disabled", true);
                    
                    //hiding modal
                    $('#viongoziModal').modal('hide');

                    //reseting the form
                    $('#viongoziForm')[0].reset();

                    //grabbing message from controller
                    var message = data.success;

                    //toasting the message
                    Toast.fire({
                        icon: 'success',
                        title: message,
                    })

                    //refreshing the page
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }

                    //if we have error show this
                    if(data.errors){
                        var message = data.errors;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })
                    }
                }
            })
        })

        //eviewing vyeo kanisa details
        $(document).on('click','.view', function(event){
                $('#viongoziForm')[0].reset();
                $('#update').hide();

                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/users/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').hide();
                    $('#preview').show();
                    $('#hidden_id').val(id);
                    $('#passwordDiv').hide();
                    $('#jina_kamili').val(data.result.jina_kamili);
                    $('#email').val(data.result.email);
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#cheo').val(data.result.cheo);
                    $('#ruhusa').val(data.result.ruhusa);
                    $('#anwani').val(data.result.anwani);
                    $('#preview_data').val(data.result.jumuiya);

                    $('#preview').on('click',function(){
                        $('#update').show();
                        $('#preview').hide();
                        //changing the forms value
                        $('#cheo').on('change',function(){
                            if(($(this).val() == "Halmashauri ya walei")){
                                $('#jumuiya_update').attr('disabled',false);
                            }
                            else{
                                $('#jumuiya_update').attr('disabled',true);
                            }
                        })
                    })
                    
                    $('.modal-title').text('Angalia mtumiaji');
                    $('#viongoziModal').modal({backdrop: 'static',keyboard: false});
                    $('#viongoziModal').modal('show');
                }
            })
        })

        //editing vyeo details
        $(document).on('click','.edit', function(event){
            $('#viongoziForm')[0].reset();
            $('#update').hide();
            
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/users/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#preview').show();
                    $('#hidden_id').val(id);
                    $('#passwordDiv').hide();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#jina_kamili').val(data.result.jina_kamili);
                    $('#email').val(data.result.email);
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#cheo').val(data.result.cheo);
                    $('#ruhusa').val(data.result.ruhusa);
                    $('#anwani').val(data.result.anwani);
                    $('#preview_data').val(data.result.jumuiya);

                    $('#preview').on('click',function(){
                        $('#update').show();
                        $('#preview').hide();
                        //changing the forms value
                        $('#cheo').on('change',function(){
                            if(($(this).val() == "Halmashauri ya walei")){
                                $('#jumuiya_update').attr('disabled',false);
                            }
                            else{
                                $('#jumuiya_update').attr('disabled',true);
                            }
                        })
                    })
                    
                    $('.modal-title').text('Badili taarifa za mtumiaji');
                    $('#viongoziModal').modal({backdrop: 'static',keyboard: false});
                    $('#viongoziModal').modal('show');
                }
            })
        })

        //deleting function
        $(document).on('click', '.delete', function (event) {
            event.preventDefault();

            //getting the id
            var delete_id = $(this).attr('id');

            //getting user confirmation
            Swal.fire({
                title: "Una uhakika?",
                text: "Unakaribia kufuta taarifa!",
                icon: 'warning',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ndio, futa!',
                cancelButtonText: 'Hapana, tunza!',
                allowOutsideClick: false,

            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: "/viongozi/destroy/" + delete_id,
                        success: function (data) {

                            if (data.success) {

                                //getting the message from response and toast it
                                var message = data.success;
                                    Toast.fire({
                                    icon: 'success',
                                    title: message,
                                })

                                //refreshing the page
                                setTimeout(function(){
                                    window.location.reload();
                                },900);
                            }

                            //errors
                            if (data.errors) {
                                var message = data.errors;
                                    //alerting
                                    Toast.fire({
                                    icon: 'info',
                                    title: message,
                                })
                            }
                        }
                    });
                } 
                
                //means user has choose to cancel the action
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    Toast.fire({
                        icon: 'info',
                        title: "Umesitisha!",
                    })
                }
            })
        })
    })
</script>
    
@endsection