@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            ORODHA YA MAJINA
                        </div>
                        <div class="col-xs-12 col-md-8">
                            <div class="d-flex justify-content-end">
                                <button id="uploadBtn" class="btn btn-sm btn-info mr-2">Pakia majina</button>
                                <a href="{{url('orodha_familia')}}"><button class="btn btn-sm btn-primary mr-2">Familia</button></a>
                                <button class="truncate btn btn-sm btn-warning">Anzisha majina</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{session('status')}}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @foreach ($errors->all() as $error)
                        {{$error}}
                        @endforeach
                    </div>
                @endif

                @if(session()->has('failures'))
                    <div class="py-4">
                        <table class="table w-100 table-striped">
                            <thead class="bg-warning">
                                <tr>
                                    <th>Row</th>
                                    <th>Attribute</th>
                                    <th>Errors</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach (session()->get('failures') as $validation)
                                <tr>
                                    <td>{{$validation->row()}}</td>
                                    <td>{{$validation->attribute()}}</td>
                                    <td>
                                        <ul>
                                            @foreach ($validation->errors() as $e)
                                                <li>{{$e}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        {{$validation->values()[$validation->attribute()]}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                        
                @endif

                    <table class="table_other table" id="table" style="width:100%;">
                        <thead class="bg-light">
                            <tr>
                                <th>S/N</th>
                                <th>Jina kamili</th>
                                <th>Jinsia</th>
                                <th>Cheo familia</th>
                                <th>Mawasiliano</th>
                                <th>Jumuiya</th>
                                <th>Kitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_majina as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->jina_kamili}}</td>
                                    <td>{{$item->jinsia}}</td>
                                    <td>{{$item->cheo_familia}}</td>
                                    <td>{{$item->mawasiliano}}</td>
                                    <td>{{$item->jumuiya}}</td>
                                    <td>
                                        <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                                        <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="uploadModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{route('orodha_majina.orodha_majina_import')}}" id="upload_form" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Chagua faili:</label>
                        <input type="file" accept=".xls,.xlsx,.csv" name="file" id="file" class="form-control-file" required>
                    </div>

                    <small class="text-danger text-bold">Mfumo unapokea aina ya xls, xlsx </small>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm text-white bg-warning" data-dismiss="modal">Funga &nbsp;&nbsp;<i class="fas fa-fw fa-times-circle"></i></button>
                        <button type="submit" class="btn btn-sm text-white bg-info">Pakia sasa &nbsp;&nbsp;<i class="fas fa-fw fa-cloud-upload-alt"></i></button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

    <div id="majinaModal" class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>

                <form id="majinaForm">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Jina kamili:</label>
                            <input type="text" name="jina_kamili" id="jina_kamili" class="form-control" id="jina_kamili" placeholder="Jina kamili" required>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Cheo familia:</label>
                                    <select name="cheo_familia" id="cheo_familia" class="form-control" required>
                                        <option value="Baba">Baba</option>
                                        <option value="Mama">Mama</option>
                                        <option value="Mtoto">Mtoto</option>
                                        <option value="Wengineo">Wengineo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Jinsia:</label>
                                    <select name="jinsia" id="jinsia" class="form-control" required>
                                        <option value="Mwanaume">Mwanaume</option>
                                        <option value="Mwanamke">Mwanamke</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Mawasiliano:</label>
                                    <input type="text" class="form-control" id="mawasiliano" name="mawasiliano" placeholder="Mawasiliano" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Jumuiya:</label>
                                    <input type="text" class="form-control" id="jumuiya" name="jumuiya" placeholder="Jumuiya" required>
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

    <script>
        $("document").ready(function(){

            //loading datatables
            $('#table').DataTable({
                stateSave: true,
                responsive: true,
                processing: true,
            })

            //loading model for uploading
            $('#uploadBtn').on('click',function(){
                $('#upload_form')[0].reset();
                $('.modal-title').text('Upakiaji wa majina');
                $('#uploadModal').modal('show');
            })

            //function to handle the updating
            $('#majinaForm').on('submit', function(event){
                event.preventDefault();

                var data_url = '';

                //setting the route to hit
                if($('#action').val() == "Update")
                var data_url = "{{route('orodha_majina.orodha_majina_update')}}";

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
                        $('#majinaModal').modal('hide');

                        //reseting the form
                        $('#majinaForm')[0].reset();

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
                        },3500);
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

            //function to handle the edition of majina
            $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

                //getting data from url
                $.ajax({
                    url: "/orodha_majina/edit/" + id,
                    dataType: "JSON",
                    success: function(data){
                        $('#submitBtn').show();
                        $('#submitBtn').html('Badilisha');
                        $('#action').val('Update');
                        $('#hidden_id').val(id);
                        $('#jina_kamili').val(data.result.jina_kamili);
                        $('#cheo_familia').val(data.result.cheo_familia);
                        $('#jinsia').val(data.result.jinsia);
                        $('#mawasiliano').val(data.result.mawasiliano);
                        $('#jumuiya').val(data.result.jumuiya);
                        $('.modal-title').text('Badili taarifa..');
                        $('#majinaModal').modal({backdrop: 'static',keyboard: false});
                        $('#majinaModal').modal('show');
                    }
                })
            })

            //reseting function function
            $(document).on('click', '.truncate', function (event) {
                event.preventDefault();

                //getting user confirmation
                Swal.fire({
                    title: "Una uhakika?",
                    text: "Unakaribia kufuta taarifa zote!",
                    icon: 'warning',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ndio, futa!',
                    cancelButtonText: 'Hapana, tunza!',
                    allowOutsideClick: false,

                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: "/orodha_majina/truncate/",
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
                                    },4500);
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
                            url: "/orodha_majina/destroy/" + delete_id,
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
                                    },4500);
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