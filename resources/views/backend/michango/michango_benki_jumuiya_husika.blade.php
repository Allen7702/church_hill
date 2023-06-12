@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                
                <div class="col-md-6 col-sm-12">
                    <h4 class="text-bold">{{strtoupper($title)}}</h4>
                </div>
    
                <div class="col-md-6 text-right">
                    <a href="{{url('michango_benki')}}"><button class="btn btn-info btn-sm mr-1">Michango yote</button></a>
                    <button id="mchangoBtn" class="btn btn-info btn-sm mr-1">Ongeza mchango</i></button>
                    <a href="{{url('michango_benki_jumuiya_husika_pdf',['jumuiya'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-1">PDF</button></a>
                    <a href="{{url('michango_benki_jumuiya_husika_excel',['jumuiya'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                </div>
    
            </div>
        </div>

        <div class="card-body">
            <table class="table w-100" id="table">
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Aina ya mchango</th>
                    <th>Kiasi (TZS)</th>
                    <th>Nafasi</th>
                    <th>Kitendo</th>
                </thead>

                <tfoot>
                    <th colspan="2">Jumla</th>
                    <th colspan="3">{{number_format($michango_total,2)}}</th>
                </tfoot>

                <tbody>
                    
                    @foreach ($jumuiya_michango as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->aina_ya_mchango}}</td>
                            <td>{{number_format($item->kiasi,2)}}</td>
                            <td>{{$loop->index+1}}</td>
                            <td>
                                <a href="{{url('michango_benki_jumuiya_mchango_husika',['id'=>$item->aina_ya_mchango,'jumuiya'=>$jumuiya])}}"><button class="btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a> 
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div id="mchangoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
    
                <form id="mchangoForm">
                    @csrf
                    <div class="modal-body">
    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6" id="createDiv">
                                    <label for="">Mwanajumuiya:</label>
                                    <select class="form-control select2" id="mwanafamilia" name="mwanafamilia">
                                        <option value="">Chagua mwanajumuiya</option>
                                        @foreach ($wanajumuiya as $item)
                                            <option value="{{$item->mwanafamilia_id}}">{{$item->jina_kamili}} ({{$item->jina_la_jumuiya}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6" id="updateDiv">
                                    <label for="">Mwanajumuiya:</label>
                                    <select class="form-control" id="mwanafamilia_update" name="mwanafamilia_update">
                                        <option value="">Chagua mwanajumuiya</option>
                                        @foreach ($wanajumuiya as $item)
                                            <option value="{{$item->mwanafamilia_id}}">{{$item->jina_kamili}} ({{$item->jina_la_jumuiya}})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Kiasi:</label>
                                    <input type="number" id="kiasi" name="kiasi" placeholder="Kiasi" step="any" min="0.0" class="form-control">
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Aina ya mchango:</label>
                                    <select name="aina_ya_mchango" id="aina_ya_mchango" class="form-control">
                                        <option value="">Chagua aina ya mchango</option>
                                        @foreach ($michangos as $row)
                                            <option value="{{$row->aina_ya_mchango}}">{{$row->aina_ya_mchango}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Akaunti:</label>
                                    <select name="akaunti_namba" id="akaunti_namba" class="form-control" required>
                                        <option value="">Chagua akaunti</option>
                                        @foreach ($akaunti as $akaunt)
                                            <option value="{{$akaunt->akaunti_namba}}">{{$akaunt->jina_la_benki}}-{{$akaunt->akaunti_namba}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Nukushi:</label>
                                    <input type="text" name="nambari_ya_nukushi" id="nambari_ya_nukushi" class="form-control" placeholder="Ingiza nambari ya nukushi">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Tarehe:</label>
                                    <input type="date" name="tarehe" class="form-control" id="tarehe">
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="">Maelezo:</label>
                            <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu mchango" rows="2"></textarea>
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
        $('#mwanafamilia').select2({
            dropdownParent: $('#mchangoModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta ..',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#mchangoBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#mwanafamilia').val('').trigger('change');
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#mchangoForm')[0].reset();
            $('.modal-title').text('Ongeza mchango');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#mchangoModal').modal({backdrop: 'static',keyboard: false});
            $('#mchangoModal').modal('show');
        })

        //submitting values
        $('#mchangoForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('michango_benki.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('michango_benki.update')}}";

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
                    $('#mchangoModal').modal('hide');

                    //reseting the form
                    $('#mchangoForm')[0].reset();

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

        //eviewing vyeo kanisa details
        $(document).on('click','.view', function(event){
                event.preventDefault();

                //getting the id from button
                var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/michango_benki/edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#mwanafamilia_update').val(data.result.mwanafamilia);
                    $('#maelezo').val(data.result.maelezo);
                    $('#kiasi').val(data.result.kiasi);
                    $('#tarehe').val(data.result.tarehe);
                    $('#nambari_ya_nukushi').val(data.result.nambari_ya_nukushi);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#aina_ya_mchango').val(data.result.aina_ya_mchango);
                    $('.modal-title').text('Angalia taarifa za mchango');
                    $('#mchangoModal').modal({backdrop: 'static',keyboard: false});
                    $('#mchangoModal').modal('show');
                }
            })
        })

        //editing vyeo details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/michango_benki/edit/" + id,
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').attr('disabled',false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#nambari_ya_nukushi').val(data.result.nambari_ya_nukushi);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#mwanafamilia_update').val(data.result.mwanafamilia);
                    $('#maelezo').val(data.result.maelezo);
                    $('#kiasi').val(data.result.kiasi);
                    $('#tarehe').val(data.result.tarehe);
                    $('#aina_ya_mchango').val(data.result.aina_ya_mchango);
                    $('.modal-title').text('Badili taarifa za mchango');
                    $('#mchangoModal').modal({backdrop: 'static',keyboard: false});
                    $('#mchangoModal').modal('show');
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
                        url: "/michango_benki/destroy/" + delete_id,
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