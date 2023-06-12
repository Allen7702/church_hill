@extends('layouts.master') @section('content')
<style>
    .red {
        color: red;
    }
</style>
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h4 class="text-bold">{{$title}} </h4>
            </div>
            <div class="col-md-8 text-right">
                <button id="correctionBtn" class="btn btn-success btn-sm mr-1">Marekebisho mwanafamilia</button>
                <button id="uploadBtn" class="btn btn-warning btn-sm mr-1">Pakia mwanafamilia</button>
                <button id="familiaBtn" class="btn btn-info btn-sm mr-1">Ongeza mwanafamilia</button>
                <a href="{{url('wanafamilia_orodha_pdf')}}"><button class="btn btn-info btn-sm mr-1">PDF</button></a>
                <a href="{{url('wanafamilia_orodha_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>

    <div class="card-body">

        @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> {{session('status')}}
        </div>
        @endif @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> @foreach ($errors->all() as $error) {{$error}} @endforeach
        </div>
        @endif @if(session()->has('failures'))
        <div class="py-4">
            <table class="table w-100 table-striped">
                <thead class="bg-warning">
                    <tr>
                        <th>Row</th>
                        <th>Attribute</th>
                        <th>Errors</th>
                        <th>Value</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (session()->get('failures') as $validation)
                    <tr>
                        <td>{{$validation->row()}}</td>
                        <td>{{$validation->attribute()}}</td>
                        <td>
                            <ul>
                                @foreach ($validation->errors() as $e)
                                <li>{{$e}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            {{$validation->values()[$validation->attribute()]}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif

        <table class="table w-100" id="table">
            <thead class="bg-light">
                {{--
                <th>S/N</th> --}}
                <th>Namba</th>
                <th>Jina kamili</th>
                <th>Mawasiliano</th>
                <th>Cheo</th>
                <th>Jumuiya</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_familia as $item)
                <tr>
                    {{--
                    <td>{{$loop->index+1}}</td> --}} @if($item->namba_utambulisho =="")
                    <td>--</td>
                    @else
                    <td>{{$item->namba_utambulisho}}</td>
                    @endif
                    <td>{{$item->jina_kamili}}</td>
                    @if($item->mawasiliano == "")
                    <td>--</td>
                    @else
                    <td>{{$item->mawasiliano}}</td>
                    @endif @if($item->cheo_familia =="")
                    <td>--</td>
                    @else
                    <td>{{$item->cheo_familia}}</td>
                    @endif
                    <td>{{$item->jina_la_jumuiya}}</td>
                    <td>
                        <a href="{{url('mwanafamilia',['id'=>$item->id])}}"><button class="view btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a> {{-- <button class="view btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button> --}}
                        <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                        <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="wanafamiliaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="wanafamiliaForm">
                @csrf
                <div class="modal-body">

                    <div id="firstDiv">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4 col-sm-12">
                                    <label for="">Jina kamili: <span class="red">*</span></label>
                                    <input type="text" class="form-control" id="jina_kamili" name="jina_kamili" placeholder="Jina kamili">
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <label for="">Namba ya simu: <span class="red">*</span></label>
                                    <input type="text" pattern="[0-9]{10}" id="mawasiliano" class="form-control" name="mawasiliano" placeholder="Mfano 0700000000">
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <label for="">Jinsia: <span class="red">*</span></label>
                                    <select name="jinsia" id="jinsia" class="custom-select">
                                        <option value="">Chagua</option>
                                        <option value="Mwanaume">Mwanaume</option>
                                        <option value="Mwanamke">Mwanamke</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="">Tarehe ya kuzaliwa: </label>
                                    <input type="date" name="dob" id="dob" placeholder="Tarehe ya kuzaliwa" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Taaluma: </label>
                                    <input class="form-control" id="taaluma" type="text" name="taaluma" placeholder="Taaluma">
                                </div>

                                <div class="col-md-4" id="createDiv">
                                    <label for="">Familia: <span class="red">*</span></label>
                                    <select name="familia" id="jina_la_familia" class="form-control select2">
                                        <option value="">Chagua familia</option>
                                        @foreach ($familia as $item)
                                        <option value="{{$item->id}}">{{$item->jina_la_familia}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-12" id="familiaDiv">
                                    <label for="">Familia:</label>
                                    <input type="hidden" id="familia_id" name="familia_id">
                                    <input type="text" name="familia_new" id="familia_new" class="form-control">
                                </div>

                                <div class="col-md-4 col-sm-12" id="updateDiv">
                                    <label for="">Familia: </label>
                                    <select name="familia_update" id="familia_update" class="form-control">
                                        <option value="">Chagua familia</option>
                                        @foreach ($familia as $item)
                                        <option value="{{$item->id}}">{{$item->jina_la_familia}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="">Komunio: </label>
                                    <select name="komunio" id="komunio" class="form-control">
                                        <option value="">Chagua komunio</option>
                                        <option value="tayari">Tayari</option>
                                        <option value="bado">Bado</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="">Ekaristi: </label>
                                    <select name="ekaristi" id="ekaristi" class="form-control">
                                        <option value="">Chagua ekaristi</option>
                                        <option value="anapokea">Anapokea</option>
                                        <option value="hapokei">Hapokei</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="">Cheo cha familia: <span class="red">*</span></label>
                                    <select name="cheo_familia" id="cheo_familia" class="form-control">
                                        <option value="">Chagua cheo</option>
                                        <option value="Baba">Baba</option>
                                        <option value="Mama">Mama</option>
                                        <option value="Mtoto">Mtoto</option>
                                        <option value="Wengineo">Wengineo</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div id="secondDiv">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="">Kipaimara: </label>
                                    <select name="kipaimara" id="kipaimara" class="form-control">
                                        <option value="">Chagua</option>
                                        <option value="tayari">Tayari</option>
                                        <option value="bado">Bado</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="">Hali ya ndoa: </label>
                                    <select name="ndoa" id="ndoa" class="form-control">
                                        <option value="">Chagua hali</option>
                                        <option value="tayari">Tayari</option>
                                        <option value="bado">Bado</option>
                                        <option value="mjane">Mjane</option>
                                        <option value="mgane">Mgane</option>
                                        <option value="pekee">Pekee</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="">Ubatizo: </label>
                                    <select name="ubatizo" id="ubatizo" class="form-control">
                                        <option value="">Chagua ubatizo</option>
                                        <option value="tayari">Tayari</option>
                                        <option value="bado">Bado</option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="">Namba ya utambulisho:</label>
                                    <input type="text" name="namba_utambulisho" placeholder="Itaundwa na mfumo" id="utambulisho" class="form-control" style="padding:8px;">
                                </div>

                                <div class="col-md-8" id="ainaDiv">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="">Aina ya ndoa:</label>
                                            <select name="aina_ya_ndoa" id="aina_ya_ndoa" class="form-control">
                                                <option value="">Chagua</option>
                                                <option value="kikatoliki">Kikatoliki</option>
                                                <option value="mseto">Mseto</option>
                                                <option value="mpagani">Mpagani</option>
                                                <option value="mwislamu">Mwislamu</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="">Dhehebu:</label>
                                            <input type="text" name="dhehebu" id="dhehebu" placeholder="Dhehebu" class="form-control" style="padding:8px;">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group" id="ubatizoDiv">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="">Namba ya cheti:</label>
                                    <input type="text" name="namba_ya_cheti" id="namba_ya_cheti" placeholder="Namba ya cheti" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="">Jimbo la ubatizo:</label>
                                    <input type="text" name="jimbo_la_ubatizo" id="jimbo_la_ubatizo" placeholder="Jimbo la ubatizo" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="">Parokia ya ubatizo:</label>
                                    <input type="text" name="parokia_ya_ubatizo" id="parokia_ya_ubatizo" placeholder="Parokia ya ubatizo" class="form-control">
                                </div>

                            </div>
                        </div>

                        {{--
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">

                                </div>

                                <div class="col-md-4">
                                    <label for="">Namba ya utambulisho:</label>
                                    <input type="text" name="namba_utambulisho" placeholder="Itaundwa na mfumo" id="utambulisho" class="form-control">
                                </div>

                                <div class="col-md-4" id="ainaDiv">
                                    <label for="">Aina ya ndoa:</label>
                                    <select name="aina_ya_ndoa" id="aina_ya_ndoa" class="form-control">
                                        <option value="">Chagua</option>
                                        <option value="kikatoliki">Kikatoliki</option>
                                        <option value="mseto">Mseto</option>
                                        <option value="mpagani">Mpagani</option>
                                        <option value="mwislamu">Mwislamu</option>
                                    </select>
                                </div>

                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="">Maoni:</label>
                            <textarea name="maoni" class="form-control" id="maoni" placeholder="Maoni kuhusu mwanafamilia" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="text-center">
                        <small>Field zenye alama ya <span class="red">*</span> lazima zijazwe</small>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" id="closeBtn" class="btn btn-warning btn-sm mr-1" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="button" class="btn btn-info btn-sm" id="nextBtn">Zinazofuata</button>
                    <button type="button" class="btn btn-warning btn-sm" id="previousBtn">Zilizopita</button>
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{route('wanafamilia.wanafamilia_import')}}" id="upload_form" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Chagua file:</label>
                        <input type="file" accept=".xls,.xlsx,.csv" name="file" id="file" class="form-control-file" required>
                    </div>

                    <small class="text-danger text-bold">Mfumo unapokea aina ya xls, xlsx </small>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm text-white bg-warning" data-dismiss="modal">Funga &nbsp;&nbsp;<i class="fas fa-fw fa-times-circle"></i></button>
                        <button type="submit" class="btn btn-sm text-white bg-info">Pakia sasa &nbsp;&nbsp;<i class="fas fa-fw fa-cloud-upload-alt"></i></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="correctionModal" tabindex="-1" role="dialog" aria-labelledby="correctionModalId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Marekebisho ya taarifa za wanafamilia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pb-4">
                    <p class="text-sm font-italic font-weight-bold">
                        Unapaswa kupakua template hii nakujaza taarifa mpya za mwanafamiala. Hakikisha namba ya utambulisho inafanana na ambayo iko kwenye mfumo yenye data zilizokosewa. Baada ya hapo inabidi kuipakia tena kwenye mfumo.
                    </p>
                    <small class="text-success"><a class="text-success" target="_blank" href="{{route('download_correction_template.index')}}">Pakua hapa Templete ya kupakia marekebisho ya data</a></small>

                </div>
                <form action="{{route('wanafamilia.marekebisho_import')}}" id="upload_marekebisho_form" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Chagua file:</label>
                        <input type="file" accept=".xls,.xlsx,.csv" name="file" id="upload_form_marekebisho" class="form-control-file" required>
                    </div>

                    <small class="text-danger text-bold">Mfumo unapokea aina ya xls, xlsx </small>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm text-white bg-warning" data-dismiss="modal">Funga &nbsp;&nbsp;<i class="fas fa-fw fa-times-circle"></i></button>
                        <button type="submit" class="btn btn-sm text-white bg-info">Pakia sasa &nbsp;&nbsp;<i class="fas fa-fw fa-cloud-upload-alt"></i></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $("document").ready(function() {

        //loading the select2 plugin
        $('#jina_la_familia').select2({
            dropdownParent: $('#wanafamiliaModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta familia...',
            allowClear: true,
            width: '100%',
        });

        // //loading the select2 plugin
        // $('#jina_la_familia').select2({
        //     dropdownParent: $('#wanafamiliaModal'),
        //     theme: 'bootstrap4',
        //     placeholder: 'tafuta familia...',
        //     allowClear: true,
        //     width: '100%',
        // });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#familiaBtn').on('click', function() {
            //preventing resubmission of data
            $("#submitBtn").attr("disabled", false);
            $('#utambulisho').attr('disabled', true);
            $('#jina_la_familia').val('').trigger('change');
            $('#createDiv').show();
            $('#secondDiv').hide();
            $('#firstDiv').show();
            $('#nextBtn').show();
            $('#ainaDiv').hide();
            $('#previousBtn').hide();
            $('#submitBtn').hide();
            $('#closeBtn').show();
            $('#ubatizoDiv').hide();
            $('#updateDiv').hide();
            $('#familiaDiv').hide();
            $('#wanafamiliaForm')[0].reset();
            $('.modal-title').text('Ongeza mwanafamilia');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#wanafamiliaModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#wanafamiliaModal').modal('show');

            $('#nextBtn').on('click', function() {
                $('#secondDiv').show();
                $('#firstDiv').hide();
                $('#submitBtn').show();
                $('#nextBtn').hide();
                $('#closeBtn').hide();
                $('#previousBtn').show();

                //changing the forms value
                $('#ndoa').on('change', function() {
                    if (($(this).val() == "tayari")) {
                        $('#ainaDiv').show();
                    } else {
                        $('#ainaDiv').hide();
                    }
                })

                //handling ubatizo fields
                $('#ubatizo').on('change', function() {
                    if (($(this).val() == "tayari")) {
                        $('#ubatizoDiv').show();
                    } else {
                        $('#ubatizoDiv').hide();
                    }
                })

            })

            $('#previousBtn').on('click', function() {
                $('#secondDiv').hide();
                $('#firstDiv').show();
                $('#submitBtn').hide();
                $('#nextBtn').show();
                $('#closeBtn').show();
                $('#previousBtn').hide();
            })
        })

        //submitting values
        $('#wanafamiliaForm').on('submit', function(event) {
            event.preventDefault();

            //----------------------------checking for empty fields------------------//

            //jina kamili
            if ($('#jina_kamili').val() == "") {
                Toast.fire({
                    icon: 'info',
                    title: 'Tafadhali jaza jina kamili..',
                });

                return false;
            }

            //mawasiliano
            if ($('#mawasiliano').val() == "") {
                Toast.fire({
                    icon: 'info',
                    title: 'Tafadhali jaza namba ya simu..',
                });

                return false;
            }

            //jinsia
            if ($('#jinsia').val() == "") {
                Toast.fire({
                    icon: 'info',
                    title: 'Tafadhali jaza jinsia..',
                });

                return false;
            }

            // if($('#dob').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza tarehe ya kuzaliwa',
            //     });

            //     return false;
            // }

            //taaluma
            // if($('#taaluma').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza taaluma ya muumini..',
            //     });

            //     return false;
            // }

            //for validating familia we first check if the action is creating
            if ($('#action').val() == "Generate") {
                //familia
                if ($('#jina_la_familia').val() == "") {
                    Toast.fire({
                        icon: 'info',
                        title: 'Tafadhali chagua familia husika..',
                    });

                    return false;
                }
            }

            //komunio
            // if($('#komunio').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza komunio..',
            //     });

            //     return false;
            // }

            //ekaristi
            // if($('#ekaristi').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza ekaristi..',
            //     })

            //     return false;
            // }


            //cheo familia
            if ($('#cheo_familia').val() == "") {
                Toast.fire({
                    icon: 'info',
                    title: 'Tafadhali jaza cheo cha familia..',
                });

                return false;
            }

            // //kipaimara
            // if($('#kipaimara').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza kipaimara..',
            //     });

            //     return false;
            // }

            // //komunio
            // if($('#ndoa').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza hali ya ndoa..',
            //     });

            //     return false;
            // }

            // //ubatizo
            // if($('#ubatizo').val() == ""){
            //     Toast.fire({
            //         icon: 'info',
            //         title: 'Tafadhali jaza hali ya ubatizo..',
            //     });

            //     return false;
            // }

            //----------------------------------------------------------------------//


            var data_url = '';

            $("#submitBtn").attr("disabled", true);

            //setting the route to hit
            if ($('#action').val() == "Generate")
                var data_url = "{{route('wanafamilia.store')}}";

            if ($('#action').val() == "Update")
                var data_url = "{{route('wanafamilia.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data) {

                    if (data.success) {

                        //disable the submit button
                        $("#submitBtn").attr("disabled", true);

                        //reseting the value of select2
                        $('#jina_la_familia').val('').trigger('change');

                        //hiding modal
                        $('#wanafamiliaModal').modal('hide');

                        //reseting the form
                        $('#wanafamiliaForm')[0].reset();

                        //grabbing message from controller
                        var message = data.success;

                        //toasting the message
                        Toast.fire({
                            icon: 'success',
                            title: message,
                        })

                        //avoiding error of redirect
                        if ($('#action').val() == "Update") {
                            //refreshing the page
                            setTimeout(function() {
                                window.location.href = "{{url('wanafamilia')}}";
                            }, 4500);
                        } else {
                            //refreshing the page
                            setTimeout(function() {
                                window.location.reload();
                            }, 4500);
                        }
                    }

                    //if we have error show this
                    if (data.errors) {
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

        //editing wanafamilia details
        $(document).on('click', '.edit', function(event) {
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/wanafamilia_edit/" + id,
                dataType: "JSON",
                success: function(data) {
                    $("#submitBtn").attr("disabled", false);
                    $('#updateDiv').hide();
                    $('#createDiv').hide();
                    $('#secondDiv').hide();
                    $('#ubatizoDiv').hide();
                    $('#firstDiv').show();
                    $('#nextBtn').show();
                    $('#ainaDiv').hide();
                    $('#previousBtn').hide();
                    $('#submitBtn').hide();
                    $('#closeBtn').show();
                    $('#familiaDiv').show();
                    $('#submitBtn').hide();
                    $('#hidden_id').val(id);
                    $('#utambulisho').attr('disabled', false);
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#jina_kamili').val(data.result.jina_kamili);
                    $('#cheo_familia').val(data.result.cheo_familia);
                    $('#utambulisho').val(data.result.namba_utambulisho);
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#jinsia').val(data.result.jinsia);
                    $('#dob').val(data.result.dob);
                    $('#taaluma').val(data.result.taaluma);
                    $('#komunio').val(data.result.komunio);
                    $('#kipaimara').val(data.result.kipaimara);
                    $('#ubatizo').val(data.result.ubatizo);
                    $('#ekaristi').val(data.result.ekaristi);
                    $('#dhehebu').val(data.result.dhehebu);
                    $('#maoni').val(data.result.maoni);
                    $('#familia_id').val(data.result.familia_id);
                    $('#familia_new').val(data.result.jina_la_familia);
                    $('#ndoa').val(data.result.ndoa);
                    // $('#cheo').val(data.result.cheo);

                    $('#familiaDiv').on('click', function(event) {
                        event.preventDefault();
                        $('#updateDiv').show();
                        $('#familiaDiv').hide();
                    })

                    //checking the values
                    if (data.result.ndoa == "tayari") {
                        $('#ainaDiv').show();
                        $('#aina_ya_ndoa').val(data.result.aina_ya_ndoa);
                    }

                    //ubatizo
                    if (data.result.ubatizo == "tayari") {
                        $('#ubatizoDiv').show();
                        $('#namba_ya_cheti').val(data.result.namba_ya_cheti);
                        $('#jimbo_la_ubatizo').val(data.result.jimbo_la_ubatizo);
                        $('#parokia_ya_ubatizo').val(data.result.parokia_ya_ubatizo);
                    }

                    //next button
                    $('#nextBtn').on('click', function() {
                        $('#secondDiv').show();
                        $('#firstDiv').hide();
                        $('#submitBtn').show();
                        $('#nextBtn').hide();
                        $('#closeBtn').hide();
                        $('#previousBtn').show();

                        //changing the forms value
                        $('#ndoa').on('change', function() {
                            if (($(this).val() == "tayari")) {
                                $('#ainaDiv').show();
                            } else {
                                $('#ainaDiv').hide();
                            }
                        })

                        //handling ubatizo fields
                        $('#ubatizo').on('change', function() {
                            if (($(this).val() == "tayari")) {
                                $('#ubatizoDiv').show();
                            } else {
                                $('#ubatizoDiv').hide();
                            }
                        })
                    })

                    $('#previousBtn').on('click', function() {
                        $('#secondDiv').hide();
                        $('#firstDiv').show();
                        $('#submitBtn').hide();
                        $('#nextBtn').show();
                        $('#closeBtn').show();
                        $('#previousBtn').hide();
                    })

                    $('.modal-title').text('Badili taarifa za mwanafamilia');
                    $('#wanafamiliaModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#wanafamiliaModal').modal('show');
                }
            })
        })

        //deleting function
        $(document).on('click', '.delete', function(event) {
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
                        url: "/wanafamilia/destroy/" + delete_id,
                        success: function(data) {

                            if (data.success) {

                                //getting the message from response and toast it
                                var message = data.success;
                                Toast.fire({
                                    icon: 'success',
                                    title: message,
                                })

                                //refreshing the page
                                setTimeout(function() {
                                    window.location.href = "{{url('wanafamilia')}}";
                                }, 4500);
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

        //uploading
        $('#uploadBtn').on('click', function() {
            $('.modal-title').text('Pakia wanafamilia');
            $('#upload_form')[0].reset();
            $('#uploadModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#uploadModal').modal('show');
        })
    })

    $('#correctionBtn').on('click', function() {
        $('.modal-title').text('Pakia Marekebisho ya Taarifa za wanafamila');
        $('#upload_marekebisho_form')[0].reset();
        $('#correctionModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#correctionModal').modal('show');
    })
</script>

@endsection