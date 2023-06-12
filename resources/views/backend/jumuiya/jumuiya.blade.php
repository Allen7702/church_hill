@extends('layouts.master')
@section('content')
<div class="card" id="myCard">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>
            <div class="col-md-6 text-right">
                @if(Auth::user()->ngazi=='Parokia' || Auth::user()->ngazi=='Kanda' || Auth::user()->ngazi=='administrator')
                <button id="uploadBtn" class="btn btn-warning btn-sm mr-2">Pakia jumuiya</button>
                <button id="jumuiyaBtn" class="btn btn-info btn-sm mr-2">Ongeza jumuiya</button>
                 @endif
                <a href="{{url('jumuiya_orodha_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('jumuiya_orodha_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
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
                <th>Jina la jumuiya</th>
                <th>Kanda</th>
                <th>Idadi ya wanajumuiya</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_jumuiya as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_jumuiya}}</td>
                        <td>{{$item->jina_la_kanda}}</td>

                          @php
                            $jina_la_jumuiya=trim(str_replace("\\t",'',$item->jina_la_jumuiya));
                            $familias = App\Familia::where('jina_la_jumuiya',$jina_la_jumuiya)->get();
                          @endphp
                        <td>
                                @php  $count=0; @endphp
                            @foreach($familias as $familia)
                                 @php $count +=App\Mwanafamilia::where('familia',$familia->id)->count(); @endphp
                            @endforeach
                           
                            {{$count}}
                        </td>
                        <td>{{Carbon::parse($item->created_at)->format('d/M/Y')}}</td>
                        <td>
                            <a href="{{url('jumuiya',['id'=>$item->jina_la_jumuiya])}}"><button class="btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
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

            <form id="jumuiyaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Jina la jumuiya:</label>
                        <input class="form-control" type="text" id="jina_la_jumuiya" name="jina_la_jumuiya" placeholder="Jina la jumuiya" required>
                    </div>

                    <div class="form-group" id="createDiv">
                        <label for="">Kanda:</label>
                        <select name="jina_la_kanda" id="jina_la_kanda" class="form-control select2">
                            <option value="">Chagua {{$sahihisha_kanda->name}}</option>
                            @foreach ($kanda as $item)
                            <option value="{{$item->jina_la_kanda}}">{{$item->jina_la_kanda}}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="updateDiv">
                        <label for="">Kanda:</label>
                        <select name="kanda_update" id="jina_la_kanda_u" class="form-control">
                            <option value="">Chagua kanda</option>
                            @foreach ($kanda as $item)
                            <option value="{{$item->jina_la_kanda}}">{{$item->jina_la_kanda}}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="comment" class="form-control" id="comment" placeholder="Maelezo kuhusu jumuiya" rows="2"></textarea>
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
                <form action="{{route('jumuiya.jumuiya_import')}}" id="upload_form" method="POST" enctype="multipart/form-data">
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

        //loading the select2 plugin
        $('#jina_la_kanda').select2({
            dropdownParent: $('#jumuiyaModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta kanda...',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#jumuiyaBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#jumuiyaForm')[0].reset();
            $('.modal-title').text('Ongeza jumuiya');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#jumuiyaModal').modal({backdrop: 'static',keyboard: false});
            $('#jumuiyaModal').modal('show');
        })

        //submitting values
        $('#jumuiyaForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('jumuiya.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('jumuiya.update')}}";

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
                    
                    //reseting the value of select2
                    $('#jina_la_kanda').val('').trigger('change');

                    //hiding modal
                    $('#jumuiyaModal').modal('hide');

                    //reseting the form
                    $('#jumuiyaForm')[0].reset();

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
                        // $("#myCard").load(location.href + " #myCard");

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

        // //viewing details about jumuiya
        // $(document).on('click','.view', function(){
        
        // //getting the id from button
        //     var id = $(this).attr('id');
        //         $.ajax({
        //         url: "jumuiya/" + id + "/edit",
        //         dataType: "JSON",
        //         success: function(data){
        //             $('#createDiv').hide();
        //             $('#updateDiv').show();
        //             $('.modal-title').text("Angalia jumuiya");
        //             $('#jina_la_kanda_u').val(data.result.jina_la_kanda);
        //             $('#jina_la_jumuiya').val(data.result.jina_la_jumuiya);
        //             $('#comment').val(data.result.comment);
        //             $('#submitBtn').hide();
        //             $('#jumuiyaModal').modal('show');
        //         }
        //     })
        // })

        //editing jumuiya details
        $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/jumuiya/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $("#submitBtn").attr("disabled", false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#jina_la_jumuiya').val(data.result.jina_la_jumuiya);
                    $('#jina_la_kanda_u').val(data.result.jina_la_kanda);
                    $('#comment').val(data.result.comment);
                    $('.modal-title').text('Badili taarifa za jumuiya');
                    $('#jumuiyaModal').modal('show');
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
                        url: "/jumuiya/destroy/" + delete_id,
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
            $('.modal-title').text('Pakia jumuiya');
            $('#upload_form')[0].reset();
            $('#uploadModal').modal({backdrop: 'static',keyboard: false});
            $('#uploadModal').modal('show');
        })

    })
</script>
    
@endsection