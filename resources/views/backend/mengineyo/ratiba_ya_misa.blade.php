@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">RATIBA YA MISA</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="sadakaBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>
                <a href="{{url('aina_za_sadaka_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('aina_za_sadaka_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">

            <thead class="bg-light">
                <th>S/N</th>
                <th>Dominika</th>
                <th>Tarehe</th>
                <th>Misa</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>

            <tbody>
                @foreach ($ratiba as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->date}}</td>
                        <td>{{$item->misa->jina_la_misa}}</td>
                        <td>{{Carbon::parse($item->created_at)->format('d-M-Y')}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Vitendo
                                </button>
                                <div class="dropdown-menu">
                                    <a class="view dropdown-item text-info" data-id="{{$item->id}}" id="{{$item->id}}"
                                       href="#"><i class="fa fa-eye fa-fw"></i> Onyesha</a>
                                    <a class="wahudumu dropdown-item text-info" data-id="{{$item->id}}" id="{{$item->id}}"
                                       href="#"><i class="fa fa-edit fa-fw"></i> Weka wahudumu</a>
                                    <a class="edit dropdown-item text-info" data-id="{{$item->id}}" id="{{$item->id}}"
                                       href="#"><i class="fa fa-edit fa-fw"></i> badilisha</a>
                                    <a class="delete dropdown-item text-danger" id="{{$item->id}}" data-id="{{$item->id}}" href="#"><i class="fa fa-trash fa-fw"></i> futa</a>
                                    <form id="delete-form-{{$item->id}}" action="" method="POST" style="display: none;">
                                        <input hidden type="text" name="_method" value="DELETE"/>
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="ShowRatiba" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>
            <div id="data-import" class="px-4 pb-4">

            </div>

        </div>
    </div>
</div>

<div id="wahudumuModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="wahudumuForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Aina:</label>
                            <select name="type" id="type" class="form-control select2">
                                <option value="" disabled selected>Chagua Aina</option>
                                <option value="Msimamizi" >Msimamizi/Wasimamizi</option>
                                <option value="Msoma Somo" >Msoma Somo</option>
                                <option value="Msoma Matangazo" >Msoma Matangazo</option>
                                <option value="Msoma Matangazo" >Mtumishi/Watumishi</option>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="description" class="form-control" id="wahudumu-description" placeholder="Maelezo kuhusu misa" rows="2"></textarea>
                    </div>
                    <div class="form-group" id="eneo-div">
                        <label for="">Watoahumuma:</label>
                        <select name="wahudumu-type" id="wahudumu-type" class="form-control select2">
                            <option value="" disabled selected>Chagua Watoahumuma</option>
                            <option value="mwanajumuiya" >Mwanajumuiya</option>
                            <option value="pandre" >Pandre</option>
                            <option value="shirika" >Shirika</option>
                            <option value="jumuiya" >Jumuiya</option>
                            <option value="kanda" >Kanda</option>
                        </select>
                    </div>

                    <div class="form-group" id="jumuiya-div" style="display: none">
                        <label for="">Chagua jumuiya:</label>
                        <select name="jumuiya" id="jumuiya" class="form-control select2 " required>
                            <option value="" disabled>Chagua jumuiya</option>
                            @foreach (\App\Jumuiya::all(['id', 'jina_la_jumuiya']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_jumuiya}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="familia-div" style="display: none">
                        <label for="">Chagua Mwanafamilia:</label>
                            <select name="mwanafamila" id="mwanafamila" class="form-control select2">
                                @foreach ($wanafamilia as $item)
                                    <option value="{{$item->namba_utambulisho}}">{{$item->jina_kamili}} ({{$item->jina_la_jumuiya}})</option>
                                @endforeach
                            </select>
                    </div>

                    <div class="form-group" id="shirika-div" style="display: none">
                        <label for="">Chagua Shirika:</label>
                        <select name="shirika" id="shirika" class="form-control select2 " required>
                            <option value="" disabled>Chagua Shirika</option>
                            @foreach (\App\Kikundi::all(['id', 'jina_la_kikundi']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_kikundi}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group " id="kanda-div" style="display: none">
                        <label for="">Chagua kanda:</label>
                        <select name="kanda" id="kanda" class="form-control select2 " required>
                            <option value="" disabled>Chagua kanda</option>
                            @foreach (\App\Kanda::all(['id', 'jina_la_kanda']) as $item)

                                <option value="{{$item->id}}" >{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="actionW" value="">
                    <input type="hidden" name="hidden_id" id="hidden_w_id">
                    <input type="hidden" name="ratiba_ya_misa_id" id="ratiba_ya_misa_id">
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtnW">Wasilisha</button>
                </div>
            </form>

        </div>
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

                    <div class="form-group">
                        <label for="">Jina la Dominika:</label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="Dominika" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tarehe Inayofanyika:</label>
                        <input class="form-control" type="datetime-local" value="2022-04-01T08:30" id="date" name="date" placeholder="Tarehe inayofanyika" required>
                    </div>
                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="description" class="form-control" id="description" placeholder="Maelezo kuhusu misa" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Chagua aina ya sadaka:</label>
                        <select name="aina_za_misa_id" id="filter_sadaka" class="form-control select2 " required>
                            <option value="" disabled>Chagua aina ya sadaka</option>
                            @foreach (\App\AinaZaSadaka::all(['id','jina_la_sadaka']) as $item)

                                <option value="{{$item->id}}" @if(!empty($sadaka) && $item->id ==$sadaka) selected @endif >{{$item->jina_la_sadaka}}</option>
                            @endforeach
                        </select>
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
        $('#sadakaBtn').on('click', function(){
            //preventing resubmission of data
            $('#submitBtn').attr('disabled',false);
            $('#sadakaForm')[0].reset();
            $('.modal-title').text('Ongeza Ratiba ya misa');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#sadakaModal').modal({backdrop: 'static',keyboard: false});
            $('#sadakaModal').modal('show');
        })

        //submitting values
        $('#sadakaForm').on('submit', function(event){
            event.preventDefault();

            //preventing resubmission of data
            $('#submitBtn').attr('disabled',true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate") {
                var method = 'POST'
                var data_url = "{{route('ratiba-ya-misa.store')}}";
            }


            if($('#action').val() == "Update") {
                var id = $('#hidden_id').val();
                var method = 'PUT'
                var data_url = "/ratiba-ya-misa/" + id
            }


            //submitting the values
            $.ajax({
                url: data_url,
                method: method,
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){

                if(data.success){

                    //preventing resubmission of data
                    $('#submitBtn').attr('disabled',true);

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

                        //preventing resubmission of data
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

        $('#wahudumuForm').on('submit', function(event){
            event.preventDefault();

            //preventing resubmission of data
            $('#submitBtnW').attr('disabled',true);
            console.log('omakei')
            var data_url = '';

            //setting the route to hit
            if($('#actionW').val() == "Generate") {
                var method_wahudumu = 'POST'
                var data_url_wahudumu = "{{route('wahudumu.store')}}";
            }

            if($('#actionW').val() == "Update") {
                var id = $('#hidden_w_id').val();
                var method = 'PUT'
                var data_url = "/wahudumu/" + id
            }



            //submitting the values
            $.ajax({
                url: data_url_wahudumu,
                method: method_wahudumu,
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data){

                    if(data.success){

                        //preventing resubmission of data
                        $('#submitBtnW').attr('disabled',true);

                        //hiding modal
                        $('#wahudumuModal').modal('hide');

                        //reseting the form
                        $('#wahudumuForm')[0].reset();

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

                        //preventing resubmission of data
                        $('#submitBtnW').attr('disabled',false);
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
                url: "/ratiba-ya-misa/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#data-import').append(`
                <h4 class="px-4 pt-4 text-uppercase font-weight-bold"> Taarifa za Misa</h4>
                <hr/>
                <table border="1" cellspacing="0" cellpadding="10" width="100%">
                <tr>
                    <td><strong>Dominika</strong></td>
                    <td>${data.result.title}</td>
                </tr>
                <tr>
                    <td><strong>Misa</strong></td>
                    <td>${data.result.misa.jina_la_misa}</td>
                </tr>
                <tr>
                    <td><strong>Muda na Saa</strong></td>
                    <td>${data.result.date}</td>
                </tr>
                <tr>
                    <td><strong>Maelezo</strong></td>
                    <td>${data.result.description}</td>
                </tr>
            </table>
            <br/>
            <h4 class="px-4 py-0 text-uppercase font-weight-bold" > Wahudumu</h4>
            <hr/>
            <table border="1" cellspacing="0" cellpadding="10"  width="100%">
            ${data.result.wahudumus.map(wahudumu =>(

                    `<tr>
                        <td><strong>Muhudumu</strong></td>
                        <td>${wahudumu.description}</td>
                        <td><strong>Huduma</strong></td>
                        <td>${wahudumu.type}</td>
                        <td><strong>Maelezo</strong></td>
                        <td>${wahudumu.description}</td>
                    </tr>`



                    ))}
            </table>
`)
                    console.log(data.result)
                    $('#title').val(data.result.title);
                    $('#description').val(data.result.description);
                    $('#date').val(data.result.date);
                    $('#filter_sadaka').val(data.result.aina_ya_misa_id);
                    $('.modal-title').text('Angalia taarifa za Ratiba ya misa');
                    $('#ShowRatiba').modal({backdrop: 'static',keyboard: false});
                    $('#ShowRatiba').modal('show');
                }
            })
        })

        $(document).on('click','.wahudumu', function(event){
            event.preventDefault();
            var id = $(this).attr('id');
            $('#submitBtnW').attr('disabled',false);
            $('#wahudumuModal').modal('show');
            $('#actionW').val('Generate');
            $('#ratiba_ya_misa_id').val(id);
            $('#wahudumuModal').modal({backdrop: 'static',keyboard: false});

            $('#wahudumu-type').on('change', function (){
                filter_eneo = $('#wahudumu-type').val()

                if(filter_eneo == 'jumuiya') {
                    $('#kanda-div').css('display', 'none')
                    $('#shirika-div').css('display', 'none')
                    $('#jumuiya-div').css('display', 'block')
                    $('#familia-div').css('display', 'none')
                }

                if (filter_eneo == 'shirika') {
                    $('#shirika-div').css('display', 'block')
                    $('#kanda-div').css('display', 'none')
                    $('#jumuiya-div').css('display', 'none')
                    $('#familia-div').css('display', 'none')
                }

                if(filter_eneo == 'kanda') {
                    $('#jumuiya-div').css('display', 'none')
                    $('#shirika-div').css('display', 'none')
                    $('#kanda-div').css('display', 'block')
                    $('#familia-div').css('display', 'none')
                }

                if(filter_eneo == 'mwanajumuiya') {
                    $('#jumuiya-div').css('display', 'none')
                    $('#shirika-div').css('display', 'none')
                    $('#kanda-div').css('display', 'none')
                    $('#familia-div').css('display', 'block')
                }
            })
            //getting the id from button
            var id = $(this).attr('id');


        })

        //editing aina za misa details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //preventing resubmission of data
            $('#submitBtn').attr('disabled',false);

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/ratiba-ya-misa/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    //preventing resubmission of data
                    $('#submitBtn').attr('disabled',false);
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#title').val(data.result.title);
                    $('#description').val(data.result.description);
                    $('#date').val(data.result.date);
                    $('#filter_sadaka').val(data.result.aina_za_misa_id);
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
                        url: "/ratiba-ya-misa/destroy/" + delete_id,
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
