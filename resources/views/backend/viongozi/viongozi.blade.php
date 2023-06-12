@extends('layouts.master') @section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">VIONGOZI WOTE</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="kiongoziBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>
                <a href="{{url('orodha_viongozi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('orodha_viongozi_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina kamili</th>
                <th>Barua pepe</th>
                <th>Simu</th>
                <th>Cheo</th>
                <th>Ruhusa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_viongozi as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->jina_kamili}}</td>
                    @if($item->email == "")
                    <td>--</td>
                    @else
                    <td>{{$item->email}}</td>
                    @endif
                    <td>{{$item->mawasiliano}}</td>
                    <td>{{$item->cheo}}</td>
                    <td>{{$item->ruhusa}}</td>
                    <td>
                        <a href="{{url('mwanafamilia',['id'=>$item->mwanajumuiya_id])}}"><button class="btn btn-sm btn-info mr-1">Angalia</button></a>
                        <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                        <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="viongoziModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="viongoziForm" method="POST" action="{{route('users.update')}}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">

                            <div class="col-sm-12 col-md-6">

                                <div id="createDiv">

                                    <label for="">Jina kamili:</label>
                                    <select name="mwanajumuiya_id" id="mwanajumuiya_id" class="form-control select2">
                                        <option value="">Chagua mwanajumuiya</option>
                                        @foreach ($wanajumuiya as $item)
                                            <option value="{{$item->mwanajumuiya_id}}">{{$item->jina_kamili}} - {{$item->namba_utambulisho}} ({{$item->jina_la_jumuiya}})</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div id="updateDiv">
                                    <label for="">Jina kamili:</label>
                                    <select name="mwanajumuiya_id_u" id="mwanajumuiya_id_u" class="form-control">
                                        <option value="">Chagua mwanajumuiya</option>
                                        @foreach ($wanajumuiya as $item)
                                            <option value="{{$item->mwanajumuiya_id}}">{{$item->jina_kamili}} - {{$item->namba_utambulisho}} ({{$item->jina_la_jumuiya}})</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label for="">Cheo:</label>
                                <select name="cheo" id="cheo" class="form-control" required>
                                    <option value="">Chagua cheo</option>
                                    @foreach ($data_vyeo as $row)
                                        <option value="{{$row->jina_la_cheo}}">{{$row->jina_la_cheo}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <label for="">Ngazi:</label>
                                <select name="ngazi" class="form-control" id="ngazi" required>
                                    <option value="">Chagua ngazi</option>
                                    <option value="Parokia">Parokia</option>
                                    <option value="Jumuiya">Jumuiya</option>
                                    <option value="Kanda">{{$sahihisha_kanda->name}}</option>
                                    <option value="vyama_kitume">Vyama vya kitume</option>
                                    <option value="wengineo">Wengineo</option>
                                </select>
                            </div>


                            <div class="col-sm-12 col-md-4" id="vyama_div" style="display:none">
                                <label for="">Vyama vya kitume:</label>
                                <select name="vyama" class="form-control" id="vyama">
                                    <option value="">Chagua chama</option>
                                    @foreach($data_vyama as $vyama)
                                    <option value="{{$vyama->id}}">{{$vyama->jina_la_kikundi}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-4">
                                <label for="">Jumuiya:</label>

                                <select name="jumuiya" id="jumuiya" class="form-control" readonly>
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($data_jumuiya as $row1)
                                        <option value="{{$row1->jina_la_jumuiya}}">{{$row1->jina_la_jumuiya}} ({{$row1->jina_la_kanda}})</option>
                                    @endforeach
                                </select>

                            </div>



                            <div class="col-sm-12 col-md-4">
                                <label for="">Anwani:</label>
                                <input class="form-control" type="text" id="anwani" name="anwani" placeholder="Anwani">
                            </div>


                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label for="">Simu:</label>
                                <input class="form-control" pattern="[0-9]{10}" type="text" id="mawasiliano" name="mawasiliano" placeholder="Simu ya mkononi 0xxxxxxxxx" readonly required>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Barua pepe:</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Barua pepe">
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Ruhusa:</label>
                                <select name="ruhusa" class="form-control" id="ruhusa" required>
                                    <option value="">Chagua ruhusa</option>
                                    <option value="kuangalia">Kuangalia taarifa</option>
                                    <option value="kuongeza">Kuongeza taarifa</option>
                                    <option value="zote">Kila kitu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 px-4">
                        <h4>Roles</h4>
                        <hr/> @foreach($roles as $role)
                        <div class="form-group">
                            <input class="form-check-inline" id="role-{{$role->id}}" type="checkbox" name="roles[]" value="{{$role->id}}" />
                            <label for="role-{{$role->id}}">{{ucfirst($role->name)}}</label>
                        </div>

                        @endforeach
                    </div>

                    <div class="pt-3 px-4">
                        <h4>Permissions</h4>
                        <hr/>
                        <div class="row">
                            @foreach($permissions as $permission) @if(fmod($loop->index, 4) == 0)
                            <div class="col-lg-3">
                                <h6 class="text-uppercase strong">{{str_replace('_',' ',explode('.',$permission->name)[0])}}</h6>
                                @endif

                                <div class="form-group">
                                    <input class="form-check-inline" id="permission-{{$permission->id}}" type="checkbox" name="permissions[]" value="{{$permission->id}}" />
                                    <label for="permission-{{$permission->id}}">{{explode('.',$permission->name)[1]}}</label>
                                </div>

                                @if((($loop->index+1) - 4 >=0) && fmod(($loop->index+1) - 4, 4) == 0)
                            </div>
                            @endif @endforeach
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script type="text/javascript">
    $("document").ready(function() {

        //loading the select2 plugin

        $('#ngazi').change(function() {

            var value = $('#ngazi').val()

            if (value == 'vyama_kitume') {
                $('#vyama_div').css({
                    "display": "block"
                })
                $('#vyama').attr('required', 'true')
            } else {
                $('#vyama_div').css({
                        "display": "none"
                    })
                    // $('#vyama').attr('required','false')
            }
        });
        $('#mwanajumuiya_id').select2({
            dropdownParent: $('#viongoziModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta mwanajumuiya...',
            allowClear: true,
            width: '100%',
        });

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#kiongoziBtn').on('click', function() {
            $('#mwanajumuiya_id').val('').trigger('change');
            $('#submitBtn').show();
            $('#updateDiv').hide();
            $('#createDiv').show();
            $('#viongoziForm')[0].reset();
            $('.modal-title').text('Ongeza mtumiaji');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#viongoziModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#viongoziModal').modal('show');
        })

        //dealing with generating data for other fields
        //dynamic changes of mwanajumuiya
        $('#mwanajumuiya_id,#mwanajumuiya_id_u').change(function() {
            var mwanajumuiya_data = $(this).val();
            $.ajax({
                url: "{{route('users.getMwanajumuiyaData')}}",
                method: "POST",
                data: {
                    mwanajumuiya: mwanajumuiya_data
                },
                dataType: "json",
                success: function(data) {
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#jumuiya').val(data.result.jina_la_jumuiya);
                }
            })
        });

        //submitting values
        // $('#viongoziForm').on('submit', function(event) {
        //     event.preventDefault();

        //     alert('submiting');

        //     var data_url = '';



        //     //setting the route to hit
        //     if ($('#action').val() == "Generate")
        //         var data_url = "{{route('users.store')}}";

        //     if ($('#action').val() == "Update")
        //         var data_url = "{{route('users.update')}}";

        //     //submitting the values
        //     $.ajax({
        //         url: data_url,
        //         method: "POST",
        //         dataType: "JSON",
        //         data: $(this).serialize(),
        //         success: function(data) {

        //             if (data.success) {

        //                 //disable the submit button
        //                 $("#submitBtn").attr("disabled", true);

        //                 //hiding modal
        //                 $('#viongoziModal').modal('hide');

        //                 //reseting the form
        //                 $('#viongoziForm')[0].reset();

        //                 //grabbing message from controller
        //                 var message = data.success;

        //                 //toasting the message
        //                 Toast.fire({
        //                     icon: 'success',
        //                     title: message,
        //                 })

        //                 //refreshing the page
        //                 setTimeout(function() {
        //                     window.location.reload();
        //                 }, 2000);
        //             }

        //             //if we have error show this
        //             if (data.errors) {
        //                 var message = data.errors;
        //                 Toast.fire({
        //                     icon: 'info',
        //                     title: message,
        //                 })
        //             }
        //         }
        //     })
        // })

        //eviewing vyeo kanisa details

        //editing vyeo details
        $(document).on('click', '.edit', function(event) {
            $('#viongoziForm')[0].reset();

            event.preventDefault();
            alert('editing first');

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/users/" + id + "/edit",
                dataType: "JSON",
                success: function(data) {
                    console.log(data.roles)
                    $('#submitBtn').show();
                    $('#hidden_id').val(id);
                    $('#submitBtn').html('Badilisha');
                    $('#action').val('Update');
                    $('#updateDiv').show();
                    $('#createDiv').hide();
                    $('#mwanajumuiya_id_u').val(data.result.mwanajumuiya_id);
                    $('#email').val(data.result.email);
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#cheo').val(data.result.cheo);
                    $('#ruhusa').val(data.result.ruhusa);
                    $('#ngazi').val(data.result.ngazi);
                    $('#jumuiya').val(data.result.jumuiya);
                    $('#anwani').val(data.result.anwani);

                    data.roles.forEach(function(role) {
                        $('#role-' + role.id).prop('checked', true);
                    })

                    data.permissions.forEach(function(permission) {
                        $('#permission-' + permission.id).prop('checked', true);
                    })

                    $('.modal-title').text('Badili taarifa za mtumiaji');
                    $('#viongoziModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#viongoziModal').modal('show');
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
                        url: "/viongozi/destroy/" + delete_id,
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
                                    window.location.reload();
                                }, 900);
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