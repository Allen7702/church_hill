@extends('layouts.master')

@section('content')

    <div class="card">

        <div class="card-header">
            <div class="row">

                <div class="col-md-6 mt-2">
                    <h3>{{strtoupper($title)}}</h3>
                </div>

                <div class="col-md-6">

                    <form name="initial_form" id="initialForm" method="GET" action="{{route('mafundisho_enrollments.index',['year'=> $year, 'type' => $type ])}}">

                        @csrf
                        <input hidden type="text" id="type" name="type" value="{{$type}}" readonly required>
                        <div class="form-group">
                            <select name="year" id="year" class="form-control select2" required>
                                <option value="" disabled>Chagua mwaka</option>
                                @foreach (array_combine(range(date('Y'),1990),range(date('Y'),1990)) as $item)

                                    <option value="{{$item}}" @if($item==$year) selected @endif >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                </div>
            </div>

        </div>
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">

                </div>
                <div class="col-md-6 text-right">
                    <button id="studentBtn" class="btn btn-warning btn-sm mr-2">Pakia Wanafunzi</button>
                    <button id="addBtn" class="btn btn-info btn-sm mr-2">Ongeza Mwanafunzi</button>
                    <a href="{{route('mafundisho_enrollments.download_pdf', ['year'=> $year, 'type' => $type ])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                    <a href="{{route('mafundisho_enrollments.download_excel', ['year'=> $year, 'type' => $type ])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                </div>
            </div>
        </div>
        <div class="card-body">

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{session('status')}}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @foreach ($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>
            @endif

            @if(session()->has('failures'))
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
                <th>S/N</th>
                <th>Jina Kamili</th>
                <th>Namba</th>
                <th>Hali</th>
                <th>Alianza</th>
                <th>Akamaliza</th>
                <th>Kitendo</th>
                </thead>
                <tbody>
                @foreach ($enrollements as $item)

                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->mwanafamilia->jina_kamili}}</td>

                        @if($item->mwanafamilia->namba_utambulisho == "")
                            <td>--</td>
                        @else
                            <td>{{$item->mwanafamilia->namba_utambulisho}}</td>
                        @endif

                        @if($item->status == "")
                            <td>--</td>
                        @else
                            <td> <span class="badge badge-circle badge-{{$item->status == 'fresher'?'info':'success'}}">{{$item->status == 'fresher'? 'anasoma': 'kamaliza'}}</span></td>
                        @endif

                        @if($item->started_at == "")
                            <td>--</td>
                        @else
                            <td>{{$item->started_at}}</td>
                        @endif
                        @if($item->ended_at == "")
                            <td>--</td>
                        @else
                            <td>{{$item->ended_at}}</td>
                        @endif

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Vitendo
                                </button>
                                <div class="dropdown-menu">
                                    <a class="edit dropdown-item text-info" data-id="{{$item->id}}"
                                       data-status="{{$item->status}}"
                                       data-started_at="{{$item->started_at}}"
                                       data-ended_at="{{$item->ended_at}}"
                                       data-partner_name="{{$item->partner_name}}"
                                       data-partner_jumuiya="{{$item->partner_jumuiya}}"
                                       data-partner_phone="{{$item->partner_phone}}"
                                       href="#"><i class="fa fa-edit fa-fw"></i> badilisha</a>
                                    <a class="delete dropdown-item text-danger" data-id="{{$item->id}}" href="#"><i class="fa fa-trash fa-fw"></i> futa</a>
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

    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="my-modal-title">Ongeza wanafunzi kwenye mafundishoa ya {{$type}}</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
                </div>
                <form id="mafundisho-add-Form" action="{{route('mafundisho_enrollments.store')}}"  method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Alianza:</label>
                            <input class="form-control" type="date" id="started_at" name="started_at" placeholder="Tarehe waliyoanza" required>
                        </div>

                        <div class="form-group" id="createDiv">
                            <label for="">Hali:</label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="" disabled>Chagua Hali</option>
                                <option value="fresher" >Anasoma</option>
                                <option value="graduated" >Amemaliza</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Alimaliza <small>(jaza kama hali ya mwanafunzi ni "Amemaliza")</small></label>
                            <input class="form-control" type="date" id="ended_at" name="ended_at" placeholder="Tarehe waliyoanza">
                        </div>
                        <div class="form-group">
                            <select name="year_filter" id="year_filter" class="form-control select2" required>
                                <option value="" disabled>Chagua mwaka</option>
                                @foreach (array_combine(range(date('Y'),1990),range(date('Y'),1990)) as $item)

                                    <option value="{{$item}}" @if($item==$year) selected @endif >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="type" id="action" value="{{$type}}">
                        <div class="form-group">
                            <label for="">Wanafunzi: *</label>
                            <select name="namba_utambulisho[]" id="students" @if($type != 'ndoa') multiple="multiple" @endif class="form-control select2">
                                @foreach ($wanafamilia as $item)
                                    <option value="{{$item->namba_utambulisho}}">{{$item->jina_kamili}} ({{$item->jina_la_jumuiya}})</option>
                                @endforeach
                            </select>
                        </div>
                    @if($type == 'ndoa')
                        <div class="form-group">
                            <label for="partner_name">Jina la mchumba wake:</label>
                            <input class="form-control" type="text" id="partner_name1" name="partner_name" placeholder="Jina la mchumba wake">
                        </div>
                        <div class="form-group">
                            <label for="partner_jumuiya">Jina la jumuiya ya mchumba wake:</label>
                            <input class="form-control" type="text" id="partner_jumuiya1" name="partner_jumuiya" placeholder="Jina la jumuiya mchumba wake" >
                        </div>
                        <div class="form-group">
                            <label for="partner_phone">Namba ya simu ya mchumba wake:</label>
                            <input class="form-control" type="text" id="partner_phone1" name="partner_phone" placeholder="Namba ya simu ya mchumba wake"/>
                        </div>
                    @endif
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

    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="my-modal-title">Bidili taarifa za  wanafunzi kwenye mafundishoa ya {{$type}}</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
                </div>
                <form id="mafundisho-edit-Form" action=""  method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Alianza:</label>
                            <input class="form-control" type="date" id="edit_started_at" name="started_at" placeholder="Tarehe waliyoanza" required>
                        </div>

                        <div class="form-group" id="createDiv">
                            <label for="">Hali:</label>
                            <select name="status" id="edit_status" class="form-control select2">
                                <option value="" disabled>Chagua Hali</option>
                                <option value="fresher" >Anasoma</option>
                                <option value="graduated" >Amemaliza</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Alimaliza <small>(jaza kama hali ya mwanafunzi ni "Amemaliza")</small></label>
                            <input class="form-control" type="date" id="edit_ended_at" name="ended_at" placeholder="Tarehe waliyoanza">
                        </div>
                        <div class="form-group">
                            <select name="year_filter" id="edit_year" class="form-control select2" required>
                                <option value="" disabled>Chagua mwaka</option>
                                @foreach (array_combine(range(date('Y'),1990),range(date('Y'),1990)) as $item)

                                    <option value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($type == 'ndoa')
                            <div class="form-group">
                                <label for="partner_name">Jina la mchumba wake:</label>
                                <input class="form-control" type="text" id="partner_name" name="partner_name" placeholder="Jina la mchumba wake">
                            </div>
                            <div class="form-group">
                                <label for="partner_jumuiya">Jina la jumuiya ya mchumba wake:</label>
                                <input class="form-control" type="text" id="partner_jumuiya" name="partner_jumuiya" placeholder="Jina la jumuiya mchumba wake" >
                            </div>
                            <div class="form-group">
                                <label for="partner_phone">Namba ya simu ya mchumba wake:</label>
                                <input class="form-control" type="text" id="partner_phone" name="partner_phone" placeholder="Namba ya simu ya mchumba wake"/>
                            </div>
                        @endif
                        <input type="hidden" name="type" id="action" value="{{$type}}">
                        <input type="hidden" name="_method" value="PUT">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>

                        <input type="hidden" name="id" id="enroll_id">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Pakia wanafunzi kwenye mafundishoa ya {{$type}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="pb-4">
                        <p class="text-sm font-italic font-weight-bold" style="font-size: 10px">
                            Unapaswa kupakua template hii nakujaza taarifa mpya za mwanafunzi. Hakikisha namba ya utambulisho inafanana na ambayo iko kwenye mfumo yenye data zilizokosewa.
                            Baada ya hapo inabidi kuipakia tena kwenye mfumo.
                        </p>
                        <small class="text-success"><a class="text-success" target="_blank" href="{{route('mafundisho_enrollments_template.download')}}">Pakua hapa Templete ya kupakia wanafunzi</a></small>

                    </div>
                    <form action="{{route('mafundisho_enrollments.import')}}" id="upload_student_form" method="POST" enctype="multipart/form-data">
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

    <script>
        $("document").ready(function(){
            //turning on select2
            $('#jumuiya').select2({
                theme: 'bootstrap4',
                placeholder: 'tafuta jumuiya..',
                allowClear: true,
                // width: '100%',
            });

            $('#students').select2({
                dropdownParent: $('#addModal'),
                theme: 'bootstrap4',
                placeholder: 'Wanafunzi...',
                allowClear: true,
                width: '100%',
            });

            $('#initialForm').on('change', function(){
                filter_type = $('#type').val()
                filter_year = $('#year').val()
                url = window.location.origin
                window.location.replace( url+'/mafundisho_enrollments/index/'+ filter_year+'/'+filter_type);
                // $('#initialForm').submit();
                // $(this).closest('form').submit();
            });

            //handling the submission of zaka form
            function ajaxCall1(){
                setTimeout(() => {
                    $('#zakaForm').trigger('submit');
                },4500);
            }

            //loading datatable
            $('#table').DataTable({
                responsive: true,
                stateSave: true,
            })

            $('#studentBtn').on('click', function (){
                $('#upload_student_form')[0].reset();
                $('#studentModal').modal({backdrop: 'static',keyboard: false});
                $('#studentModal').modal('show');
            })

            $('#addBtn').on('click', function (){
                $('#addModal').modal({backdrop: 'static',keyboard: false});
                $('#addModal').modal('show');
            })

            //editing enrollments details
            $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                let update_id = $(this).data('id');
                let status = $(this).data('status');
                let started_at = $(this).data('started_at');
                let ended_at = $(this).data('ended_at');
                let partner_name= $(this).data('partner_name')
                let partner_jumuiya= $(this).data('partner_jumuiya')
                let partner_phone= $(this).data('partner_phone')
                let url = window.location.origin + "/mafundisho_enrollments/"+ update_id +"/update"

                $('#edit_started_at').val(started_at);
                $('#edit_ended_at').val(ended_at);
                $('#edit_status').val(status);
                $('#enroll_id').val(update_id);
                $('#partner_name').val(partner_name);
                $('#partner_jumuiya').val(partner_jumuiya);
                $('#partner_phone').val(partner_phone);
                $('#editModal').modal('show');
                $('#mafundisho-edit-Form').attr('action',url)

            })

            //deleting function
            $(document).on('click', '.delete', function (event) {
                event.preventDefault();

                //getting the id
                var delete_id = $(this).data('id');
                let url = window.location.origin + "/mafundisho_enrollments/"+ delete_id +"/delete"
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
                        $('#delete-form-'+ delete_id).attr('action',url)
                        $('#delete-form-'+ delete_id).submit()

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
