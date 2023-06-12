@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4 class="text-bold">{{strtoupper($title)}}</h4>
                </div>
                <div class="col-md-6 text-right">
                    <button id="ankraBtn" class="btn btn-info btn-sm mr-2">Matumizi ankra</button>
                    <button id="mengineyoBtn" class="btn btn-info btn-sm mr-2">Matumizi mengineyo</button>
                    <button class="btn btn-info btn-sm mr-2">PDF</button>
                    <button class="btn btn-info btn-sm mr-0">Excel</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table w-100" id="table">
                <thead class="bg-light">
                    <th>Matumizi</th>
                    <th>Kiasi (TZS)</th>
                    <th>Tarehe</th>
                    <th>Akaunti</th>
                    <th>Kitendo</th>
                </thead>
                <tfoot>
                    <th colspan="1">Jumla</th>
                    <th colspan="4">{{number_format($total_matumizi_benki,2)}}</th>
                </tfoot>
                <tbody>
                    @foreach ($data_benki as $item)
                        <tr>
                            @if($item->aina_ya_ulipaji === "Ankra")
                            <td>
                                {{App\AnkraMadeni::where('ankra_madenis.namba_ya_ankra',$item->namba_ya_ankra)
                                ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                                ->value('watoa_hudumas.aina_ya_huduma')}}
                            </td>
                            @else                                
                            <td>{{$item->aina_ya_matumizi}}</td>
                            @endif
                            <td>{{number_format($item->kiasi,2)}}</td>
                            <td>{{Carbon::parse($item->tarehe)->format('d-m-Y')}}</td>
                            <td>{{$item->jina_la_benki}}-{{$item->akaunti_namba}}</td>
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

    <div id="mengineyoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
    
                <form id="mengineyoForm">
                    @csrf
                    <div class="modal-body">
    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Aina ya matumizi:</label>
                                    <select name="aina_matumizi" id="aina_matumizi" class="form-control" required>
                                        <option value="">Chagua matumizi</option>
                                        @foreach ($aina_matumizi as $row)
                                            <option value="{{$row->aina_ya_matumizi}}">{{$row->aina_ya_matumizi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kundi la matumizi:</label>
                                    <select name="kundi" id="kundi_m" class="form-control" required>
                                        <option value="">Chagua kundi</option>
                                        <option value="kawaida">Kawaida</option>
                                        <option value="maendeleo">Maendeleo</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Tarehe:</label>
                                    <input type="date" name="tarehe" class="form-control" id="tarehe" placeholder="Chagua tarehe" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kiasi:</label>
                                    <input type="number" min="0.0" step="any" name="kiasi" id="kiasi" class="form-control" placeholder="Jaza kiasi" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Akaunti:</label>
                                    <select name="akaunti_namba" id="akaunti_namba" class="form-control" required>
                                        <option value="">Chagua akaunti</option>
                                        @foreach ($akauntis as $akaunti)
                                            <option value="{{$akaunti->akaunti_namba}}">{{$akaunti->jina_la_benki}} - ({{$akaunti->akaunti_namba}} {{$akaunti->jina_la_akaunti}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Nambari ya nukushi:</label>
                                    <input type="text" name="namba_nukushi" id="namba_nukushi" placeholder="Nambari ya nukushi" class="form-control" required>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="">Maelezo:</label>
                            <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu matumizi" rows="2"></textarea>
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                        <input type="hidden" name="action" id="action" value="">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="hidden" name="aina" id="aina">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div id="ankraModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">

                                    <div id="createDiv">
                                        <label for="">Ankra:</label>
                                        <select name="ankra" id="ankra" class="form-control select2">
                                            <option value="">Chagua ankra</option>
                                            @foreach ($ankra_madeni as $madeni)
                                                <option value="{{$madeni->madeni_id}}">{{$madeni->jina_kamili}} ({{number_format($madeni->deni,2)}} - {{$madeni->aina_ya_huduma}})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="updateDiv">
                                        <label for="">Ankra:</label>
                                        <select name="ankra_update" id="ankra_update" class="form-control">
                                            <option value="">Chagua ankra</option>
                                            @foreach ($ankra_madeni as $madeni)
                                                <option value="{{$madeni->madeni_id}}">{{$madeni->jina_kamili}} ({{number_format($madeni->deni,2)}} - {{$madeni->aina_ya_huduma}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kundi la matumizi:</label>
                                    <select name="kundi" id="kundi_a" class="form-control" required>
                                        <option value="">Chagua kundi</option>
                                        <option value="kawaida">Kawaida</option>
                                        <option value="maendeleo">Maendeleo</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Akaunti:</label>
                                    <select name="akaunti_namba" id="akaunti_namba_ankra" class="form-control" required>
                                        <option value="">Chagua akaunti</option>
                                        @foreach ($akauntis as $akaunti)
                                            <option value="{{$akaunti->akaunti_namba}}">{{$akaunti->jina_la_benki}} - ({{$akaunti->akaunti_namba}} {{$akaunti->jina_la_akaunti}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Nambari ya nukushi:</label>
                                    <input type="text" name="namba_nukushi" id="namba_nukushi_ankra" placeholder="Nambari ya nukushi" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="">Tarehe:</label>
                                    <input type="date" id="tarehe_ankra" name="tarehe" class="form-control" placeholder="Chagua tarehe" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Kiasi:</label>
                                    <input type="number" min="0.0" step="any" id="kiasi_ankra" name="kiasi" class="form-control" placeholder="Jaza kiasi" required>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="">Maelezo:</label>
                            <textarea name="maelezo" class="form-control" id="maelezo_ankra" placeholder="Maelezo kuhusu matumizi" rows="2"></textarea>
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                        <input type="hidden" name="action" id="action_ankra" value="">
                        <input type="hidden" name="hidden_id" id="hidden_id_ankra">
                        <input type="hidden" name="aina" id="aina_ankra">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn_ankra">Wasilisha</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
<script>
    $("document").ready(function(){

        //loading the select2 plugin
        $('#ankra').select2({
            dropdownParent: $('#ankraModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta deni...',
            allowClear: true,
            width: '100%',
        });

        //datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#mengineyoBtn').on('click', function(){
            $('#mengineyoForm')[0].reset();
            $('.modal-title').text('Lipia matumizi mengineyo');
            $('#action').val('Generate');
            $('#aina').val('Mengineyo');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#mengineyoModal').modal({backdrop: 'static',keyboard: false});
            $('#mengineyoModal').modal('show');
        })

        //turning on the modal
        $('#ankraBtn').on('click', function(){
            $('#ankra').val('').trigger('change');
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#ankraForm')[0].reset();
            $('.modal-title').text('Lipia matumizi kwa ankra');
            $('#action_ankra').val('Generate');
            $('#submitBtn_ankra').show();
            $('#submitBtn_ankra').html('Wasilisha');
            $('#aina_ankra').val('Ankra');
            $('#ankraModal').modal({backdrop: 'static',keyboard: false});
            $('#ankraModal').modal('show');
        })

        //submitting values
        $('#mengineyoForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('matumizi_benki.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('matumizi_benki.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //disable the resubmission of data
                    $('#submitBtn').attr('disabled',true);

                    //hiding modal
                    $('#mengineyoModal').modal('hide');

                    //reseting the form
                    $('#mengineyoForm')[0].reset();

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

    //submitting values
    $('#ankraForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action_ankra').val() == "Generate")
            var data_url = "{{route('matumizi_benki.store')}}";

            if($('#action_ankra').val() == "Update")
            var data_url = "{{route('matumizi_benki.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){
                
                if(data.success){

                    //disabling the resubmission of data
                    $('#submitBtn_ankra').attr('disabled',true);

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

    //editing madeni
    $(document).on('click','.edit', function(event){
        event.preventDefault();

        //getting the id from button
        var id = $(this).attr('id');

        //getting data from url
        $.ajax({
            url: "/matumizi_benki/edit/" + id,
            dataType: "JSON",
            success: function(data){

                if(data.result.aina_ya_ulipaji == "Ankra"){
                    $('#hidden_id_ankra').val(id);
                    $('#submitBtn_ankra').show();
                    $('#submitBtn_ankra').html('Badilisha');
                    $('#action_ankra').val('Update');
                    $('#aina_ankra').val('Ankra');
                    $('#updateDiv').show();
                    $('#createDiv').hide();
                    $('#kiasi_ankra').val(data.result.kiasi);
                    $('#ankra_update').val(data.result.madeni_id);
                    $('#tarehe_ankra').val(data.result.tarehe);
                    $('#namba_nukushi_ankra').val(data.result.namba_nukushi);
                    $('#akaunti_namba_ankra').val(data.result.akaunti_namba);
                    $('#kundi_a').val(data.result.kundi);
                    $('#maelezo_ankra').val(data.result.maelezo);
                    $('.modal-title').text('Badili taarifa za matumizi');
                    $('#ankraModal').modal({backdrop: 'static',keyboard: false});
                    $('#ankraModal').modal('show');
                }
                else{
                    $('#hidden_id').val(id);
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#tarehe').val(data.result.tarehe);
                    $('#aina_matumizi').val(data.result.aina_ya_matumizi);
                    $('#maelezo').val(data.result.maelezo);
                    $('#kiasi').val(data.result.kiasi);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#namba_nukushi').val(data.result.namba_nukushi);
                    $('#kundi_m').val(data.result.kundi);
                    $('#aina').val('Mengineyo');
                    $('.modal-title').text('Badili taarifa za matumizi');
                    $('#mengineyoModal').modal({backdrop: 'static',keyboard: false});
                    $('#mengineyoModal').modal('show');
                }                
            }
        })
    })

    //viewing madeni
    $(document).on('click','.view', function(event){
        event.preventDefault();

        //getting the id from button
        var id = $(this).attr('id');

        //getting data from url
        $.ajax({
            url: "/matumizi_benki/edit/" + id,
            dataType: "JSON",
            success: function(data){

                if(data.result.aina_ya_ulipaji == "Ankra"){
                    $('#hidden_id_ankra').val(id);
                    $('#submitBtn_ankra').hide();
                    $('#aina_ankra').val('Ankra');
                    $('#updateDiv').show();
                    $('#createDiv').hide();
                    $('#kiasi_ankra').val(data.result.kiasi);
                    $('#ankra_update').val(data.result.madeni_id);
                    $('#kundi_a').val(data.result.kundi);
                    $('#tarehe_ankra').val(data.result.tarehe);
                    $('#maelezo_ankra').val(data.result.maelezo);
                    $('#namba_nukushi_ankra').val(data.result.namba_nukushi);
                    $('#akaunti_namba_ankra').val(data.result.akaunti_namba);
                    $('.modal-title').text('Angalia taarifa za matumizi');
                    $('#ankraModal').modal({backdrop: 'static',keyboard: false});
                    $('#ankraModal').modal('show');
                }
                else{
                    $('#hidden_id').val(id);
                    $('#submitBtn').hide();
                    $('#tarehe').val(data.result.tarehe);
                    $('#aina_matumizi').val(data.result.aina_ya_matumizi);
                    $('#maelezo').val(data.result.maelezo);
                    $('#kiasi').val(data.result.kiasi);
                    $('.modal-title').text('Angalia taarifa za matumizi');
                    $('#kundi_m').val(data.result.kundi);
                    $('#akaunti_namba').val(data.result.akaunti_namba);
                    $('#namba_nukushi').val(data.result.namba_nukushi);
                    $('#mengineyoModal').modal({backdrop: 'static',keyboard: false});
                    $('#mengineyoModal').modal('show');
                }                
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
                    url: "/matumizi_benki/destroy/" + delete_id,
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