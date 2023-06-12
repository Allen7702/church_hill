@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">ORODHA YA {{strtoupper($sahihisha_kanda->name)}}</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="uploadBtn" class="btn btn-warning btn-sm mr-1">Pakia {{$sahihisha_kanda->name}}</button>
                <button id="kandaBtn" class="btn btn-info btn-sm mr-1">Ongeza {{$sahihisha_kanda->name}}</button>
                <a href="{{url('kanda_orodha_pdf')}}"><button class="btn btn-info btn-sm mr-1">PDF</button></a>
                <a href="{{url('kanda_orodha_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
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

        <table class="table_other table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina la {{$sahihisha_kanda->name}}</th>
                <th>Ufupisho</th>
                <th>Idadi ya jumuiya</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_kanda as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_kanda}}</td>
                        <td>{{$item->herufi_ufupisho}}</td>
                        <td>{{$item->idadi_ya_jumuiya}}</td>
                        <td>{{Carbon::parse($item->created_at)->format('d/M/Y')}}</td>
                        <td>
                            <a href="{{url('kanda',['id'=>$item->jina_la_kanda])}}"><button class="btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

<div id="kandaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="kandaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Jina la {{$sahihisha_kanda->name}}:</label>
                        <input class="form-control" type="text" id="jina_la_kanda" name="jina_la_kanda" placeholder="Jina {{$sahihisha_kanda->name}}" required="">
                    </div>

                    <div class="form-group">
                        <label for="">Ufupisho:</label>
                        <input class="form-control" type="text" id="herufi_ufupisho" name="herufi_ufupisho" placeholder="Herufi za ufupisho" required="">
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="comment" class="form-control" id="comment" placeholder="Maelezo kuhusu {{$sahihisha_kanda->name}}" rows="2"></textarea>
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

<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{route('kanda.kanda_import')}}" id="upload_form" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Chagua file:</label>
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


<script type="text/javascript">
    $("document").ready(function(){

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#kandaBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#kandaForm')[0].reset();
            $('.modal-title').text('Ongeza {{$sahihisha_kanda->name}}');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#kandaModal').modal({backdrop: 'static',keyboard: false});
            $('#kandaModal').modal('show');
        })

        //submitting values
        $('#kandaForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('kanda.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('kanda.update')}}";

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
                    $('#kandaModal').modal('hide');

                    //reseting the form
                    $('#kandaForm')[0].reset();

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
                    },4500);
                }

                    //if we have error show this
                    if(data.errors){
                        $("#submitBtn").attr("disabled", false);
                        var message = data.errors;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })
                    }
                }
            })
        })

        // //viewing details about kanda
        // $(document).on('click','.view', function(){
        //     //turn on modal

        // //getting the id from button
        //     var id = $(this).attr('id');
        //         $.ajax({
        //         url: "kanda/" + id + "/edit",
        //         dataType: "JSON",
        //         success: function(data){
        //             $('.modal-title').text("Angalia kanda");
        //             $('#jina_la_kanda').val(data.result.jina_la_kanda);
        //             $('#herufi_ufupisho').val(data.result.herufi_ufupisho);
        //             $('#comment').val(data.result.comment);
        //             $('#submitBtn').hide();
        //             $('#kandaModal').modal('show');
        //         }
        //     })
        // })

        //editing kanda details
        $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/kanda/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $("#submitBtn").attr("disabled", false);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#jina_la_kanda').val(data.result.jina_la_kanda);
                    $('#herufi_ufupisho').val(data.result.herufi_ufupisho);
                    $('#comment').val(data.result.comment);
                    $('.modal-title').text('Badili taarifa za kanda');
                    $('#kandaModal').modal('show');
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
                        url: "/kanda/destroy/" + delete_id,
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

        //uploading
        $('#uploadBtn').on('click',function(){
            $('.modal-title').text('Pakia {{$sahihisha_kanda->name}}');
            $('#upload_form')[0].reset();
            $('#uploadModal').modal({backdrop: 'static',keyboard: false});
            $('#uploadModal').modal('show');
        })

    })
</script>
    
@endsection