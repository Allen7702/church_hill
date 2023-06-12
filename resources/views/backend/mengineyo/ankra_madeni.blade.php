@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">ORODHA YA ANKRA ZA HUDUMA</h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{url('watoa_huduma')}}"><button class="btn btn-info btn-sm mr-2">Watoa huduma</button></a>
                <button id="ankraBtn" class="btn btn-info btn-sm mr-2">Ongeza ankra</button>
                <button class="btn btn-info btn-sm mr-2">PDF</button>
                <button class="btn btn-info btn-sm mr-0">Excel</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Mtoa huduma</th>
                <th>Huduma</th>
                <th>Kiasi (TZS)</th>
                <th>Deni</th>
                {{-- <th>Kumbu #</th> --}}
                <th>Tarehe</th>
                <th>Kitendo</th>
            </thead>
            <tfoot>
                <th colspan="3">Jumla</th>
                <th>{{number_format($madeni_total,2)}}</th>
                <th colspan="3">{{number_format($salio,2)}}</th>
            </tfoot>
            <tbody>
                @foreach ($data_ankra as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td title="{{$item->jina_kamili}}">{{Str::limit($item->jina_kamili,'15','..')}}</td>
                        <td title="{{$item->aina_ya_huduma}}">{{Str::limit($item->aina_ya_huduma,'15','..')}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{number_format($item->deni,2)}}</td>
                        {{-- <td>{{$item->namba_ya_ankra}}</td> --}}
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

<div id="ankraModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="ankraForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group" id="createDiv">
                        <label for="">Mtoa huduma:</label>
                        <select name="mtoa_huduma" id="mtoa_huduma" class="form-control select2">
                            <option value="">Chagua mtoa huduma</option>
                            @foreach ($data_watoahuduma as $item)
                                <option value="{{$item->id}}">{{$item->jina_kamili}} {{$item->aina_ya_huduma}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="updateDiv">
                        <label for="">Mtoa huduma:</label>
                        <select name="mtoa_huduma_update" id="mtoa_huduma_update" class="form-control">
                            <option value="">Chagua mtoa huduma</option>
                            @foreach ($data_watoahuduma as $item)
                                <option value="{{$item->id}}">{{$item->jina_kamili}} ({{$item->aina_ya_huduma}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Kiasi:</label>
                                <input type="number" name="kiasi" id="kiasi" placeholder="Kiasi" class="form-control" step="any" min="0.0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Tarehe:</label>
                                <input type="date" id="tarehe" name="tarehe" class="form-control" placeholder="Tarehe" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Namba ya ankra:</label>
                        <input type="text" name="namba_ya_ankra" id="namba_ya_ankra" placeholder="Namba ya ankra" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu ankra" rows="2"></textarea>
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
        $('#mtoa_huduma').select2({
            dropdownParent: $('#ankraModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta mtoa huduma...',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#ankraBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#mtoa_huduma').val('').trigger('change');
            $('#ankraForm')[0].reset();
            $('.modal-title').text('Ongeza ankra');
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#submitBtn').show();
            $('#ankraModal').modal({backdrop: 'static',keyboard: false});
            $('#ankraModal').modal('show');
        })

        //submitting values
        $('#ankraForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',false);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('ankra_za_madeni.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('ankra_za_madeni.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //prevent resubmission of data
                    $('#submitBtn').attr('disabled', true);
            
                    //hiding modal
                    $('#ankraModal').modal('hide');

                    //reseting the form
                    $('#ankraForm')[0].reset();

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

        //eviewing vyeo kanisa details
        $(document).on('click','.view', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/ankra_za_madeni/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#updateDiv').show();
                    $('#createDiv').hide();
                    $('#mtoa_huduma_update').val(data.result.mtoa_huduma);
                    $('#kiasi').val(data.result.kiasi);
                    $('#namba_ya_ankra').val(data.result.namba_ya_ankra);
                    $('#tarehe').val(data.result.tarehe);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Angalia taarifa za ankra');
                    $('#ankraModal').modal({backdrop: 'static',keyboard: false});
                    $('#ankraModal').modal('show');
                }
            })
        })

        //editing madeni
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/ankra_za_madeni/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').attr('disabled',false);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#updateDiv').show();
                    $('#createDiv').hide();
                    $('#mtoa_huduma_update').val(data.result.mtoa_huduma);
                    $('#kiasi').val(data.result.kiasi);
                    $('#namba_ya_ankra').val(data.result.namba_ya_ankra);
                    $('#tarehe').val(data.result.tarehe);
                    $('#maelezo').val(data.result.maelezo);
                    $('.modal-title').text('Badili taarifa za ankra');
                    $('#ankraModal').modal({backdrop: 'static',keyboard: false});
                    $('#ankraModal').modal('show');
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
                        url: "/ankra_za_madeni/destroy/" + delete_id,
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