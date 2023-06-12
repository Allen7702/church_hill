@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">MALI ZA KANISA</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="maliBtn" class="btn btn-info btn-sm mr-2">Ongeza</button>
                <a href="{{url('aina_za_mali')}}"><button class="btn btn-info btn-sm mr-2">Aina ya mali</button></a>
                <a href="{{url('mali_za_kanisa_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('mali_za_kanisa_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina la mali</th>
                <th>Aina</th>
                <th>Thamani (TZS)</th>
                <th>Usajili</th>
                <th>Kitendo</th>
            </thead>
            <tfoot>
                <th colspan="3">Jumla</th>
                <th colspan="3">{{number_format($mali_total,2)}}</th>
            </tfoot>
            <tbody>
                @foreach ($data_mali as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_mali}}</td>
                        <td>{{$item->aina_ya_mali}}</td>
                        <td>{{number_format($item->thamani,2)}}</td>
                        <td>{{$item->usajili}}</td>
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

<div id="maliModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="maliForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Aina ya mali:</label>
                                <select name="aina_ya_mali" id="aina_ya_mali" class="form-control" required>
                                    <option value="">Chagua aina</option>
                                    @foreach ($aina_za_mali as $row)
                                        <option value="{{$row->aina_ya_mali}}">{{$row->aina_ya_mali}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Jina la mali:</label>
                                <input class="form-control" type="text" id="jina_la_mali" name="jina_la_mali" placeholder="Jina la mali" required>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Thamani ya mali:</label>
                                <input class="form-control" type="number" min="0" step="any" id="thamani" name="thamani" placeholder="Thamani ya mali" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Namba ya usajili:</label>
                                <input class="form-control" type="text" id="usajili" name="usajili" placeholder="Usajili wa mali" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Maelezo:</label>
                                <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu mali" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Hali ya sasa:</label>
                                <textarea name="hali_yake" class="form-control" id="hali_yake" placeholder="Maelezo kuhusu hali ya mali" rows="2"></textarea>
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

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#maliBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#maliForm')[0].reset();
            $('.modal-title').text('Sajili mali');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#submitBtn').show();
            $('#maliModal').modal({backdrop: 'static',keyboard: false});
            $('#maliModal').modal('show');
        })

        //submitting values
        $('#maliForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('mali_za_kanisa.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('mali_za_kanisa.update')}}";

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
                    $('#maliModal').modal('hide');

                    //reseting the form
                    $('#maliForm')[0].reset();

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
                url: "/mali_za_kanisa/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#jina_la_mali').val(data.result.jina_la_mali);
                    $('#aina_ya_mali').val(data.result.aina_ya_mali);
                    $('#maelezo').val(data.result.maelezo);
                    $('#thamani').val(data.result.thamani);
                    $('#usajili').val(data.result.usajili);
                    $('#hali_yake').val(data.result.hali_yake);
                    $('.modal-title').text('Angalia mali ya kanisa');
                    $('#maliModal').modal({backdrop: 'static',keyboard: false});
                    $('#maliModal').modal('show');
                }
            })
        })

        //editing mali za details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/mali_za_kanisa/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').attr('disabled',false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#jina_la_mali').val(data.result.jina_la_mali);
                    $('#maelezo').val(data.result.maelezo);
                    $('#thamani').val(data.result.thamani);
                    $('#aina_ya_mali').val(data.result.aina_ya_mali);
                    $('#usajili').val(data.result.usajili);
                    $('#hali_yake').val(data.result.hali_yake);
                    $('.modal-title').text('Badili mali ya kanisa');
                    $('#maliModal').modal({backdrop: 'static',keyboard: false});
                    $('#maliModal').modal('show');
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
                        url: "/mali_za_kanisa/destroy/" + delete_id,
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