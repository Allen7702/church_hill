@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">UJUMBE ULIOHIFADHIWA</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="ujumbeBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>
            </div>
        </div>
        <h4 class="text-bold"></h4>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">

            <thead class="bg-light">
                <th>S/N</th>
                <th>Kichwa</th>
                <th>Kundi</th>
                <th>Mchango</th>
                <th>Kitendo</th>
            </thead>

            <tbody>
                @foreach ($data_ujumbe as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{Str::limit($item->kichwa,'20',$end="..")}}</td>
                        <td>{{ucfirst($item->kundi)}}</td>
                        <td>{{ucfirst(Str::limit($item->aina_ya_toleo,'20',$end='..'))}}</td>
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

<div id="ujumbeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="ujumbeForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Kichwa cha ujumbe:</label>
                        <input class="form-control" type="text" id="kichwa" name="kichwa" placeholder="Kichwa cha ujumbe" required>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Kundi:</label>
                                <select name="kundi" class="form-control" id="kundi" required>
                                    <option value="">Chagua kundi</option>
                                    <option value="shukrani">Shukrani</option>
                                    <option value="ukumbusho">Ukumbusho</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Toleo:</label>
                                <select name="aina_ya_toleo" class="form-control" id="aina_ya_toleo" required>
                                    <option value="">Chagua aina</option>
                                    <option value="all">All</option>
                                    <option value="zaka">Zaka</option>
                                    @foreach ($data_aina as $item)
                                        <option value="{{$item->aina_ya_mchango}}">{{$item->aina_ya_mchango}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Ujumbe:</label>
                        <textarea name="ujumbe" id="ujumbe" class="form-control" placeholder="Andika ujumbe wako" rows="2"></textarea>
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
        $('#ujumbeBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#ujumbeForm')[0].reset();
            $('.modal-title').text('Ongeza ujumbe');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#ujumbeModal').modal({backdrop: 'static',keyboard: false});
            $('#ujumbeModal').modal('show');
        })

        //submitting values
        $('#ujumbeForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',false);
                    
            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('shukrani_ukumbusho_save')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('shukrani_ukumbusho_update')}}";

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
                    $('#ujumbeModal').modal('hide');

                    //reseting the form
                    $('#ujumbeForm')[0].reset();

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
                        Swal.fire({
                            text: message,
                            title: "Taarifa!",
                            timer: 12000,
                            showCancelButton: false,
                            showConfirmButton: false,
                            icon: "info",
                        })
                    }
                }
            })
        })

        //editing ujumbe details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/shukrani_ukumbusho_sample/" + id,
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#kichwa').val(data.result.kichwa);
                    $('#ujumbe').val(data.result.ujumbe);
                    $('#kundi').val(data.result.kundi);
                    $('#aina_ya_toleo').val(data.result.aina_ya_toleo);
                    $('.modal-title').text('Badili ujumbe');
                    $('#ujumbeModal').modal({backdrop: 'static',keyboard: false});
                    $('#ujumbeModal').modal('show');
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
                        url: "/shukrani_ukumbusho_sample/destroy/" + delete_id,
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
