@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                
                <div class="card-header">
                    <div class="row">

                        <div class="col-md-5">
                            VIONGOZI WA FAMILIA
                        </div>

                        <div class="col-md-7">
                            <div class="d-flex justify-content-end">
                                <button id="chaguaBtn" class="btn btn-sm btn-info mr-2">Chagua majina</button>
                                <button id="tengenezaBtn" class="btn btn-sm btn-primary mr-2">Tengeneza</button>
                                <button id="truncateBtn" class="btn btn-sm btn-warning mr-2">Futa familia zote</button>
                                <a href="{{url()->previous()}}"><button class="btn btn-sm btn-primary"><i class="fas fa-fw fa-angle-double-left"></i></button></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <table class="table_other  table" id="table" style="width:100%;">
                        
                        <thead class="bg-light">
                            <tr>
                                <th>S/N</th>
                                <th>Jina kamili</th>
                                <th>Jinsia</th>
                                <th>Cheo</th>
                                <th>Mawasiliano</th>
                                <th>Jumuiya</th>
                                <th>Kitendo</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data_familia as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->jina_la_familia}}</td>
                                    <td>{{$item->jinsia}}</td>
                                    <td>{{$item->cheo_familia}}</td>
                                    <td>{{$item->mawasiliano}}</td>
                                    <td>{{$item->jina_la_jumuiya}}</td>
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

    <div id="chaguaModal" class="modal fade" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>

                <form id="chaguaForm">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Jina la familia:</label>
                            <select name="jina_la_familia[]" id="jina_la_familia" multiple="multiple" class="form-control select2" required>
                                @foreach ($data_majina as $item)
                                    <option value="{{$item->id}}">{{$item->jina_kamili}} - ({{$item->jumuiya}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Maoni:</label>
                            <textarea name="maoni" id="maoni" class="form-control" placeholder="Maoni.." rows="1"></textarea>
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

    <div id="familiaModal" class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>

                <form id="familiaForm">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Jina la familia:</label>
                            <input type="text" name="jina_familia" id="jina_familia" class="form-control" placeholder="Jina la familia" required>
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
                        <input type="hidden" name="hidden_id_update" id="hidden_id_update">
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

            //loading model for tengeneza families
            $('#chaguaBtn').on('click',function(){
                $('#chaguaForm')[0].reset();
                $('#action').val('Create');
                $('#jina_la_familia').val('').trigger('change');
                $('.modal-title').text('Uundaji wa familia');
                $('#chaguaModal').modal('show');
            })

            //loading the select2 plugin
            $('#jina_la_familia').select2({
                dropdownParent: $('#chaguaModal'),
                theme: 'bootstrap4',
                placeholder: 'jina la familia...',
                allowClear: true,
                width: '100%',
            });

            //function to handle the submission of familia
            $('#chaguaForm').on('submit', function(event){
                event.preventDefault();

                var data_url = '';

                //setting the route to hit
                if($('#action').val() == "Create")
                var data_url = "{{route('orodha_familia.orodha_familia_store')}}";

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
                        $('#chaguaModal').modal('hide');

                        //reseting the form
                        $('#chaguaForm')[0].reset();

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
                        },4000);
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

            //function to handle the updating
            $('#familiaForm').on('submit', function(event){
                event.preventDefault();

                var data_url = '';

                //setting the route to hit
                if($('#action').val() == "Update")
                var data_url = "{{route('orodha_familia.orodha_familia_update')}}";

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
                        $('#familiaModal').modal('hide');

                        //reseting the form
                        $('#familiaForm')[0].reset();

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

            //function to handle the edition of familia update
            $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

                //getting data from url
                $.ajax({
                    url: "/orodha_familia/edit/" + id,
                    dataType: "JSON",
                    success: function(data){
                        $('#submitBtn').show();
                        $('#submitBtn').html('Badilisha');
                        $('#action').val('Update');
                        $('#hidden_id_update').val(id);
                        $('#jina_familia').val(data.result.jina_la_familia);
                        $('#cheo_familia').val(data.result.cheo_familia);
                        $('#jinsia').val(data.result.jinsia);
                        $('#mawasiliano').val(data.result.mawasiliano);
                        $('#jumuiya').val(data.result.jina_la_jumuiya);
                        $('.modal-title').text('Badili taarifa..');
                        $('#familiaModal').modal({backdrop: 'static',keyboard: false});
                        $('#familiaModal').modal('show');
                    }
                })
            })

            //reseting function function
            $('#truncateBtn').on('click', function (event) {
                event.preventDefault();

                //getting user confirmation
                Swal.fire({
                    title: "Una uhakika?",
                    text: "Unakaribia kufuta familia zote! Majina ya viongozi wa familia yatarudishwa kwenye orodha ya majina..",
                    icon: 'warning',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ndio, futa!',
                    cancelButtonText: 'Hapana, tunza!',
                    allowOutsideClick: false,

                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: "/orodha_familia/truncate/",
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

                                //information
                                if (data.info) {

                                    //getting the message from response and toast it
                                    var message = data.info;
                                        Toast.fire({
                                        icon: 'info',
                                        title: message,
                                    })
                        
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
                    text: "Unakaribia kufuta taarifa! Kiongozi wa familia atarudishwa kwenye orodha ya majina..",
                    icon: 'warning',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ndio, futa!',
                    cancelButtonText: 'Hapana, tunza!',
                    allowOutsideClick: false,

                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "/orodha_familia/destroy/" + delete_id,
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

            //tengeneza function
            $('#tengenezaBtn').on('click', function (event) {
                event.preventDefault();

                //getting user confirmation
                Swal.fire({
                    title: "Una uhakika na zoezi hili?",
                    text: "Unakaribia kuwasilisha rasmi familia kwenye kanzidata ya mfumo! Mara baada ya zoezi familia zitakua rasmi kwenye kanzidata..",
                    icon: 'warning',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ndio, wasilisha!',
                    cancelButtonText: 'Hapana, zuia!',
                    allowOutsideClick: false,

                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "/orodha_familia/tengeneza/",
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

                                //information
                                if (data.info) {
                                    var message = data.info;
                                        //alerting
                                        Swal.fire({
                                        icon: 'info',
                                        title: "<h1 class='text-primary'><b>Taarifa!</b></h1>",
                                        text: message,
                                        timer: 4500,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                    })
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