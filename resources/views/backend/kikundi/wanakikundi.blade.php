@extends('layouts.master')
@section('content')
<div class="card">
    
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                {{strtoupper($title)}}
            </div>
            <div class="col-md-6 text-right">
                <a href="{{url('chama_kitume_pdf',['id'=>$chama])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('chama_kitume_excel',['id'=>$chama])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table" id="table">
            
            <thead class="bg-light">
                <th>S/N</th>
                <th>Mwanachama</th>
                <th>Mawasiliano</th>
                <th>Jumuiya</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>

            <tbody>
                @foreach ($data_wanakikundi as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_kamili}}</td>
                        <td>{{$item->mawasiliano}}</td>
                        <td>{{$item->jina_la_jumuiya}}</td>
                        <td>{{Carbon::parse($item->created_at)->format('d-M-Y')}}</td>
                        <td>
                            <a href="{{url('mwanafamilia',['id'=>$item->mwanakikundi])}}"><button class="btn btn-sm btn-info mr-1">Angalia</button></a>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        
    </div>
</div>

<div id="mwanakikundiModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="mwanakikundiForm">
                @csrf
            
                <div class="modal-body">
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label for="">Mwanachama:</label>
                                    <input type="text" id="mwanakikundi" readonly name="mwanakikundi" class="form-control">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="">Kikundi cha sasa:</label>
                                    <input type="text" readonly class="form-control" id="jina_la_kikundi">
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="">Chama kipya:</label>
                            <select name="kikundi" class="form-control" required>
                                <option value="">Chagua chama</option>
                                @foreach ($data_vikundi as $row)
                                    <option value="{{$row->id}}">{{$row->jina_la_kikundi}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Maoni:</label>
                            <textarea name="maoni" class="form-control" id="maoni" placeholder="Maoni kuhusu mwanachama" rows="2"></textarea>
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
        
        //datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //submitting the form values
        $('#mwanakikundiForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = '';

            if($('#action').val() == "Update")
            var data_url = "{{route('mwanakikundi_update.update')}}";

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
                    $('#mwanakikundiModal').modal('hide');

                    //reseting the form
                    $('#mwanakikundiForm')[0].reset();

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

        //editing function for mwanakikundi
        $(document).on('click', '.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');
             //getting data from url
            $.ajax({
                url: "/kikundi_husika/edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $("#submitBtn").attr("disabled", false);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#maoni').val(data.result.maoni);
                    $('#jina_la_kikundi').val(data.result.jina_la_kikundi);
                    $('#mwanakikundi').val(data.result.jina_kamili);
                    $('.modal-title').text('Badili taarifa za mwanachama');
                    $('#mwanakikundiModal').modal({backdrop: 'static',keyboard: false});
                    $('#mwanakikundiModal').modal('show');
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
                        url: "/mwanakikundi_husika/destroy/" + delete_id,
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