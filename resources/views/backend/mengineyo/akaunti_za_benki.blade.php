@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">AKAUNTI ZA BENKI</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="akauntiBtn" class="btn btn-info btn-sm mr-2">Ongeza akaunti</button>
                <a href="{{url('akaunti_za_benki_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('akaunti_za_benki_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina la benki</th>
                <th>Jina la akaunti</th>
                <th>Akaunti namba</th>
                <th>Tawi</th>
                <th>Hali yake</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_akaunti as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_benki}}</td>
                        <td>{{$item->jina_la_akaunti}}</td>
                        <td>{{$item->akaunti_namba}}</td>
                        <td>{{$item->tawi}}</td>
                        <td>{{$item->hali_ya_akaunti}}</td>
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

<div id="akauntiModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="akauntiForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Jina la benki:</label>
                        <input class="form-control" type="text" id="jina_la_benki" name="jina_la_benki" placeholder="Jina la benki" required>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Jina la akaunti:</label>
                                <input class="form-control" type="text" id="jina_la_akaunti" name="jina_la_akaunti" placeholder="Jina la akaunti" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Akaunti namba:</label>
                                <input class="form-control" type="text" id="akaunti_namba" name="akaunti_namba" placeholder="Akaunti namba" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6">
                                <label for="">Tawi:</label>
                                <input class="form-control" type="text" id="tawi" name="tawi" placeholder="Tawi la benki" required>
                            </div>

                            <div class="col-md-6">
                                <label for="">Hali ya akaunti:</label>
                                <select name="hali_ya_akaunti" class="form-control" id="hali_ya_akaunti" class="form-control" required>
                                    <option value="">Chagua hali</option>
                                    <option value="imelala">Imelala</option>
                                    <option value="hai">Hai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu akaunti" rows="2"></textarea>
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

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#akauntiBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#akauntiForm')[0].reset();
            $('.modal-title').text('Ongeza akaunti');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#submitBtn').show();
            $('#akauntiModal').modal({backdrop: 'static',keyboard: false});
            $('#akauntiModal').modal('show');
        })

        //submitting values
        $('#akauntiForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('akaunti_za_benki.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('akaunti_za_benki.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //preventing resubmission of data
                    $('#submitBtn').attr('disabled',true);

                    //hiding modal
                    $('#akauntiModal').modal('hide');

                    //reseting the form
                    $('#akauntiForm')[0].reset();

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
                        $('#submitBtn').attr('disabled',false);

                        var message = data.errors;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })
                    }
                }
            })
        })

        //viewing aina za misa  details
        $(document).on('click','.view', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/akaunti_za_benki/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#jina_la_benki').val(data.result.jina_la_benki);
                    $('#jina_la_akaunti').val(data.result.jina_la_akaunti);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#tawi').val(data.result.tawi);
                    $('#hali_ya_akaunti').val(data.result.hali_ya_akaunti);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Angalia taarifa za akaunti');
                    $('#akauntiModal').modal({backdrop: 'static',keyboard: false});
                    $('#akauntiModal').modal('show');
                }
            })
        })

        //editing aina za misa details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/akaunti_za_benki/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').attr('disabled',false);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#jina_la_benki').val(data.result.jina_la_benki);
                    $('#jina_la_akaunti').val(data.result.jina_la_akaunti);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#tawi').val(data.result.tawi);
                    $('#hali_ya_akaunti').val(data.result.hali_ya_akaunti);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Badili taarifa za akaunti');
                    $('#akauntiModal').modal({backdrop: 'static',keyboard: false});
                    $('#akauntiModal').modal('show');
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
                        url: "/akaunti_za_benki/destroy/" + delete_id,
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