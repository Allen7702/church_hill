@extends('layouts.master')
@section('content')
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-md-5 col-sm-12">
                <h4 class="text-bold">VYAMA VYA KITUME</h4>
            </div>
            <div class="col-md-7 col-sm-12 text-right">
                <button id="kikundiBtn" class="btn btn-info btn-sm mr-2">Ongeza chama</button>
                <button id="wanakikundiBtn" class="btn btn-info btn-sm mr-2">Ongeza wanachama</button>
                <a href="{{url('vyama_kitume_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('vyama_kitume_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table_other table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina la mwanachama</th>
                <th>Waumini</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_kikundi as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_kikundi}}</td>
                        <td>{{$item->idadi_ya_waumini}}</td>
                        <td>
                            <a href="{{url('kikundi_husika',['id'=>$item->id])}}"><button class="btn btn-sm btn-info mr-1">Angalia</button></a>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="kikundiModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="kikundiForm" enctype='multipart/form-data'>
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Jina la chama:</label>
                        <input class="form-control" type="text" id="jina_la_kikundi" name="jina_la_kikundi" placeholder="Jina la chama" required>
                    </div>

                    <div class="form-group">
                        <label for="">Maoni:</label>
                        <textarea name="maoni" class="form-control" id="maoni" placeholder="Maoni kuhusu chama" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Picha (Logo):</label>
                                <input id="photo" type="file" accept="images/*.png,.png,.gif,.jpg,.jpeg,.tiff" class="form-control" name="photo" placeholder="Picha ya Shirika">
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

<div id="wanakikundiModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="wanakikundiForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Chama: *</label>
                        <select name="kikundi" id="kikundi_husika" class="form-control select2">
                            <option value="">Chagua chama</option>
                            @foreach ($data_kikundi as $item)
                            <option value="{{$item->id}}">{{$item->jina_la_kikundi}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Mwanachama: *</label>
                        <select name="wanafamilia[]" id="wanafamilia" multiple="multiple" class="form-control select2">
                            <option value="">Chagua mwanachama</option>
                            @foreach ($wanafamilia as $item)
                                <option value="{{$item->id}}">{{$item->jina_kamili}} ({{$item->jina_la_jumuiya}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Maoni:</label>
                        <textarea name="maoni" class="form-control" id="maoni_new" placeholder="Maoni kuhusu mwanachama" rows="2"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="action_new" value="">
                    <button type="submit" id="submitWanaBtn" class="btn btn-info btn-sm">Wasilisha</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $("document").ready(function(){

        //loading the select2 plugin
        $('#kikundi_husika').select2({
            dropdownParent: $('#wanakikundiModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta chama...',
            allowClear: true,
            width: '100%',
        });

        //loading the select2 plugin
        $('#wanafamilia').select2({
            dropdownParent: $('#wanakikundiModal'),
            theme: 'bootstrap4',
            placeholder: 'wanafamilia...',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#kikundiBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#kikundiForm')[0].reset();
            $('.modal-title').text('Ongeza chama');
            $('#kikundi_husika').val('').trigger('change');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#kikundiModal').modal({backdrop: 'static',keyboard: false});
            $('#kikundiModal').modal('show');
        })

        //turning on modal for wanakikundi
        $('#wanakikundiBtn').on('click', function(){
            $('#kikundi_husika').val('').trigger('change');
            $('#wanakikundiForm')[0].reset();
            $('#wanafamilia').val('').trigger('change');
            $('.modal-title').text('Ongeza wanachama');
            $('#action_new').val('Generate');
            $('#wanakikundiModal').modal({backdrop: 'static',keyboard: false});
            $('#wanakikundiModal').modal('show');
        })

        var logo_photo = '';

        $('#photo').change(function(){
            var reader = new FileReader();
            reader.onloadend = () => {
                logo_photo = reader.result;
                // document.write('RESULT: ', reader.result);
                console.log(reader.result)
            }
            reader.readAsDataURL($('#photo')[0].files[0]);
        });
        //submitting values
        $('#kikundiForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('kikundi.kikundi_store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('kikundi_update.kikundi_update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize()+ " &photo="+ logo_photo,
                success: function(data){

                if(data.success){

                    //disable the submit button
                    $("#submitBtn").attr("disabled", true);

                    //hiding modal
                    $('#kikundiModal').modal('hide');

                    //reseting the form
                    $('#kikundiForm')[0].reset();

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

        //submitting wanakikundi values
        $('#wanakikundiForm').on('submit', function(event){
            event.preventDefault();

            $("#submitWanaBtn").attr("disabled", true);

            var data_url = '';

            //setting the route to hit
            if($('#action_new').val() == "Generate")
            var data_url = "{{route('wanakikundi.store')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){

                if(data.success){

                    $("#submitWanaBtn").attr("disabled", true);

                    //hiding modal
                    $('#wanakikundiModal').modal('hide');

                    //reseting the form
                    $('#wanakikundiForm')[0].reset();

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

                        $("#submitWanaBtn").attr("disabled", false);

                        var message = data.errors;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })
                    }

                    //if we have info show this
                    if(data.info){

                        $("#submitWanaBtn").attr("disabled", false);

                        var message = data.info;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })

                        //refreshing the page
                        setTimeout(function(){
                            window.location.reload();
                        },4500);
                    }
                }
            })
        })


        //eviewing kikundi details
        $(document).on('click','.view', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/kikundi/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#jina_la_kikundi').val(data.result.jina_la_kikundi);
                    $('#maoni').val(data.result.maoni);
                    $('.modal-title').text('Angalia taarifa za chama');
                    $('#kikundiModal').modal({backdrop: 'static',keyboard: false});
                    $('#kikundiModal').modal('show');
                }
            })
        })

        //editing kikundi details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/kikundi_edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $("#submitBtn").attr("disabled", false);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#maoni').val(data.result.maoni);
                    $('#jina_la_kikundi').val(data.result.jina_la_kikundi);
                    $('.modal-title').text('Badili taarifa za chama');
                    $('#kikundiModal').modal({backdrop: 'static',keyboard: false});
                    $('#kikundiModal').modal('show');
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
                        url: "/kikundi_destroy/destroy/" + delete_id,
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
