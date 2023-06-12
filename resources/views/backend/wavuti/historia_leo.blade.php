@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5>Historia leo</h5>
                </div>
                <div class="col-md-6 text-right">
                    <button id="historiaBtn" class="btn btn-sm btn-info">Ongeza historia</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table w-100" id="table">
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Picha</th>
                    <th>Historia</th>
                    <th>Status</th>
                    <th>Maelezo</th>
                    <th>Imeundwa</th>
                    <th>Kitendo</th>
                </thead>

                <tbody>
                    @foreach ($data_historia as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td><img src="{{url('uploads/images/'.$item->picha)}}" class="img" width="60px" height="50px"></td>
                        <td>{{Str::limit($item->kichwa,'10','...')}}</td>
                        <td>{{$item->uchapishaji}}</td>
                        <td>{{Str::limit($item->maelezo,'15','..')}}</td>
                        <td>{{Carbon::parse($item->tarehe)->format('d-m-Y')}}</td>
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

    <div id="tukioModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
    
                <form id="tukioForm" enctype="multipart/form-data">
                    
                    @csrf
                    <div class="modal-body">
    
                        <div class="form-group">
                            <label for="">Kichwa cha historia:</label>
                            <input class="form-control" type="text" id="kichwa" name="kichwa" placeholder="Kichwa cha historia" required>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Tarehe:</label>
                                    <input type="date" id="tarehe" name="tarehe" placeholder="Tarehe ya tukio" class="form-control"  required>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Uchapishaji:</label>
                                    <select name="uchapishaji" id="uchapishaji" class="form-control" required>
                                        <option value="">Chagua</option>
                                        <option value="imechapishwa">Imechapishwa</option>
                                        <option value="haijachapishwa">Haijachapishwa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Picha:</label>
                            <input type="file" class="form-control" name="picha" id="picha">
                        </div>
    
                        <div class="form-group">
                            <label for="">Maelezo:</label>
                            <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu historia" rows="2" required></textarea>
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
            $('#table').DataTable({
                stateSave: true,
                responsive: true,
            })

        //turning on the modal
        $('#historiaBtn').on('click', function(){
            $('#tukioForm')[0].reset();
            $('.modal-title').text('Ongeza historia');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#submitBtn').show();
            $('#tukioModal').modal({backdrop: 'static',keyboard: false});
            $('#tukioModal').modal('show');
        })

        //submitting values
        $('#tukioForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('historia.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('historia.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                
                if(data.success){

                    //preventing resubmission of data
                    $('#submitBtn').attr('disabled',true);

                    //hiding modal
                    $('#tukioModal').modal('hide');

                    //reseting the form
                    $('#tukioForm')[0].reset();

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

        //viewing historia  details
        $(document).on('click','.view', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/historia/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#kichwa').val(data.result.kichwa);
                    $('#tarehe').val(data.result.tarehe);
                    $('#uchapishaji').val(data.result.uchapishaji);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Angalia taarifa za historia');
                    $('#tukioModal').modal({backdrop: 'static',keyboard: false});
                    $('#tukioModal').modal('show');
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
                url: "/historia/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#kichwa').val(data.result.kichwa);
                    $('#tarehe').val(data.result.tarehe);
                    $('#uchapishaji').val(data.result.uchapishaji);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Badili taarifa za historia');
                    $('#tukioModal').modal({backdrop: 'static',keyboard: false});
                    $('#tukioModal').modal('show');
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
                        url: "/historia/destroy/" + delete_id,
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