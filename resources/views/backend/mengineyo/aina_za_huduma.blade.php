@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">AINA ZA HUDUMA</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="hudumaBtn" class="btn btn-info btn-sm mr-2">Ongeza huduma</button>
                <a href="{{url('aina_za_huduma_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('aina_za_huduma_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Aina ya huduma</th>
                <th>Maelezo</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_huduma as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->aina_ya_huduma}}</td>
                        <td>{{Str::limit($item->maelezo,'20','...')}}</td>
                        <td>{{Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
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

<div id="hudumaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="hudumaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Aina ya huduma:</label>
                        <input class="form-control" type="text" id="aina_ya_huduma" name="aina_ya_huduma" placeholder="Aina ya huduma" required>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu huduma" rows="2"></textarea>
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
        $('#hudumaBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#hudumaForm')[0].reset();
            $('.modal-title').text('Sajili huduma');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#hudumaModal').modal({backdrop: 'static',keyboard: false});
            $('#hudumaModal').modal('show');
        })

        //submitting values
        $('#hudumaForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('aina_za_huduma.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('aina_za_huduma.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //prevent resubmission of data
                    $('#submitBtn').attr('disabled',true);

                    //hiding modal
                    $('#hudumaModal').modal('hide');

                    //reseting the form
                    $('#hudumaForm')[0].reset();

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
                url: "/aina_za_huduma/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#aina_ya_huduma').val(data.result.aina_ya_huduma);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Angalia taarifa za huduma');
                    $('#hudumaModal').modal({backdrop: 'static',keyboard: false});
                    $('#hudumaModal').modal('show');
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
                url: "/aina_za_huduma/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').attr('disabled',false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#aina_ya_huduma').val(data.result.aina_ya_huduma);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Badili taarifa za huduma');
                    $('#hudumaModal').modal({backdrop: 'static',keyboard: false});
                    $('#hudumaModal').modal('show');
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
                        url: "/aina_za_huduma/destroy/" + delete_id,
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