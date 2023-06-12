@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="sadakaBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>
                <a href="{{url('sadaka_jumuiya_husika_pdf',['id'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('sadaka_jumuiya_husika_excel',['id'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Tarehe</th>
                <th>Sadaka (TZS)</th>
                <th>Imewekwa</th>
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="3">{{number_format($data_sadaka_total,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($data_sadaka as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{Carbon::parse($item->tarehe)->format('d-M-Y')}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{$item->imewekwa_na}}</td>
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

<div id="sadakaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="sadakaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group" id="createDiv">
                        <label for="">Jumuiya:</label>
                        <select class="form-control select2" id="jina_la_jumuiya" name="jina_la_jumuiya">
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiyas as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}} ({{$item->jina_la_kanda}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="updateDiv">
                        <label for="">Jumuiya:</label>
                        <select class="form-control" id="jina_la_jumuiya_update" name="jina_la_jumuiya_update">
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiyas as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}} ({{$item->jina_la_kanda}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Kiasi:</label>
                                <input type="number" min="0" step="any" id="kiasi" name="kiasi" placeholder="Kiasi" step="any" min="0.0" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Tarehe:</label>
                                <input type="date" name="tarehe" class="form-control" id="tarehe">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu sadaka" rows="2"></textarea>
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

        //loading the select2 plugin
        $('#jina_la_jumuiya').select2({
            dropdownParent: $('#sadakaModal'),
            theme: 'bootstrap4',
            placeholder: 'chagua jumuiya ..',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#sadakaBtn').on('click', function(){
            $("#submitBtn").attr("disabled",false);
            $('#jina_la_jumuiya').val('').trigger('change');
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#sadakaForm')[0].reset();
            $('.modal-title').text('Ongeza sadaka');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#sadakaModal').modal({backdrop: 'static',keyboard: false});
            $('#sadakaModal').modal('show');
        })

        //submitting values
        $('#sadakaForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled",true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('sadaka_jumuiya.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('sadaka_jumuiya.update')}}";

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
                    $('#sadakaModal').modal('hide');

                    //reseting the form
                    $('#sadakaForm')[0].reset();

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
                        $("#submitBtn").attr("disabled",false);

                        var message = data.errors;
                        Toast.fire({
                            icon: 'info',
                            title: message,
                        })
                    }
                }
            })
        })

        //viewing sadaka  details
        $(document).on('click','.view', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/sadaka_jumuiya/edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#hidden_id').val(id);
                    $('#jina_la_jumuiya_update').val(data.result.jina_la_jumuiya);
                    $('#maelezo').val(data.result.maelezo);
                    $('#tarehe').val(data.result.tarehe);
                    $('#kiasi').val(data.result.kiasi);
                    $('.modal-title').text('Angalia taarifa za sadaka');
                    $('#sadakaModal').modal({backdrop: 'static',keyboard: false});
                    $('#sadakaModal').modal('show');
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
                url: "/sadaka_jumuiya/edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $("#submitBtn").attr("disabled",false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#hidden_id').val(id);
                    $('#jina_la_jumuiya_update').val(data.result.jina_la_jumuiya);
                    $('#maelezo').val(data.result.maelezo);
                    $('#tarehe').val(data.result.tarehe);
                    $('#kiasi').val(data.result.kiasi);
                    $('.modal-title').text('Badili taarifa za sadaka');
                    $('#sadakaModal').modal({backdrop: 'static',keyboard: false});
                    $('#sadakaModal').modal('show');
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
                        url: "/sadaka_jumuiya/destroy/" + delete_id,
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