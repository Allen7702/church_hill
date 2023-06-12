@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>
            <div class="col-md-6 text-right">
                <!-- <button id="kiongoziBtn" class="btn btn-info btn-sm mr-2">Ongeza </button> -->
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
                <th>Jumuiya</th>
                <th>Simu</th>
                <th>Cheo</th>
                @if($title=='Orodha ya kamati tendaji vyama vya kitume')
                <th>Chama</th>
               @endif
                <th>Ruhusa</th>
                <th>Kitendo</th>
            </thead>
            <tbody>
                @foreach ($data_kamati as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_kamili}}</td>
                        <td>{{$item->jumuiya}}</td>
                        <td>{{$item->mawasiliano}}</td>
                        <td>{{$item->cheo}}</td>
                        @if($title=='Orodha ya kamati tendaji vyama vya kitume')
                        <td> @foreach($item->kikundi as $kikundi)
                               {{$kikundi->jina_la_kikundi}}
                            @endforeach
                        </td>
                        @endif
                        <td>{{$item->ruhusa}}</td>
                        <td>
                            <a href="{{url('mwanafamilia',['id'=>$item->mwanajumuiya_id])}}"><button class="btn btn-sm btn-info mr-1">Angalia</button></a>
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

            <form id="viongoziForm">
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
                                </select>
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

                            <div class="col-md-4">
                                <label for="">Jumuiya:</label>

                                <select name="jumuiya" id="jumuiya" class="form-control" readonly>
                                    <option value="">Chagua jumuiya</option>
                                    @foreach ($data_jumuiya as $row1)
                                        <option value="{{$row1->jina_la_jumuiya}}">{{$row1->jina_la_jumuiya}} ({{$row1->jina_la_kanda}})</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <label for="">Anwani:</label>
                                <input class="form-control" type="text" id="anwani" name="anwani" placeholder="Anwani" required>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Barua pepe:</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Barua pepe">
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label for="">Simu:</label>
                                <input class="form-control" pattern="[0-9]{10}" type="text" id="mawasiliano" name="mawasiliano" placeholder="Simu ya mkononi 0xxxxxxxxx" readonly required>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script type="text/javascript">
    $("document").ready(function(){

        //loading the select2 plugin
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
        $('#kiongoziBtn').on('click', function(){
            $('#mwanajumuiya_id').val('').trigger('change');
            $('#submitBtn').show();
            $('#updateDiv').hide();
            $('#createDiv').show();
            $('#viongoziForm')[0].reset();
            $('.modal-title').text('Ongeza mtumiaji');
            $('#action').val('Generate');
            $('#submitBtn').html('Wasilisha');
            $('#viongoziModal').modal({backdrop: 'static',keyboard: false});
            $('#viongoziModal').modal('show');
        })

        //dealing with generating data for other fields
        //dynamic changes of mwanajumuiya
        $('#mwanajumuiya_id,#mwanajumuiya_id_u').change(function () {
            var mwanajumuiya_data = $(this).val();
            $.ajax({
                url: "{{route('users.getMwanajumuiyaData')}}",
                method: "POST",
                data: {
                    mwanajumuiya: mwanajumuiya_data
                },
                dataType: "json",
                success: function (data) {
                    $('#mawasiliano').val(data.result.mawasiliano);
                    $('#jumuiya').val(data.result.jina_la_jumuiya);
                }
            })
        });

        //submitting values
        $('#viongoziForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('users.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('users.update')}}";

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
                    $('#viongoziModal').modal('hide');

                    //reseting the form
                    $('#viongoziForm')[0].reset();

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
    })
</script>

@endsection
