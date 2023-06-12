@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">

                <div class="col-md-7 col-sm-12">
                    <h4 class="text-bold">{{strtoupper($title)}}</h4>
                </div>

                <div class="col-md-5 text-right">
                    {{-- <a href="{{url('zaka_mkupuo')}}"><button class="btn btn-sm btn-info mr-1">Zaka mkupuo</button></a> --}}
                    <a href="{{url('zaka_zote')}}"><button class="btn btn-info btn-sm mr-1">Zaka zote</button></a>
                    <button id="zakaBtn" class="btn btn-info btn-sm mr-1">Weka zaka</button>
                    <a href="{{url('zaka_jumuiya_husika_pdf',['id'=>$jina_jumuiya])}}"><button class="btn btn-info btn-sm mr-1">PDF</button></a>
                    <a href="{{url('zaka_jumuiya_husika_excel',['id'=>$jina_jumuiya])}}"><button class="btn btn-info btn-sm mr-1">Excel</button></a>
                </div>

            </div>
        </div>

        <div class="card-body">
            <table class="table_other table w-100" id="table">
                <thead class="bg-light">
                    <th>S/N</th>
                    <th>Mwanajumuiya</th>
                    <th>Namba</th> 
                    <th>Kiasi</th>
                    <th>Nafasi</th>
                    <th>Kitendo</th>
                </thead>
                <tfoot>
                    <th colspan="3">Jumla</th>
                    <th colspan="3">{{number_format($zaka_total,2)}}</th>
                </tfoot>
                <tbody>
                    @foreach ($data_zaka as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->jina_kamili}}</td>
                            <td>{{$item->namba_utambulisho}}</td>
                            <td>{{number_format($item->kiasi,2)}}</td>
                            <td>{{$loop->index+1}}</td>
                            <td>
                                <a href="{{url('mwanafamilia',['id'=>$item->mwanajumuiya_id])}}"><button class="btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

<div id="zakaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="zakaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group" id="createJumuiyaDiv">
                        <label for="">Jumuiya:</label>
                        <select name="jumuiya" id="jumuiya" class="form-control select2">
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiya as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}-{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="updateJumuiyaDiv">
                        <label for="">Jumuiya:</label>
                        <select name="jumuiya_update" id="jumuiya_update" class="form-control">
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiya as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}-{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="createMwanajumuiyaDiv">
                        <label for="">Mwanajumuiya:</label>
                        <select name="mwanajumuiya" class="form-control select2" id="mwanajumuiya">
                            <option value="">Chagua mwanajumuiya</option>
                            @foreach ($data_wanajumuiya as $row)
                                <option value="{{$row->mwanajumuiya_id}}">{{$row->jina_kamili}}-{{$row->jina_la_jumuiya}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="updateMwanajumuiyaDiv">
                        <label for="">Mwanajumuiya:</label>
                        <select name="mwanajumuiya_update" class="form-control" id="mwanajumuiya_update">
                            <option value="">Chagua mwanajumuiya</option>
                            @foreach ($data_wanajumuiya as $row)
                                <option value="{{$row->mwanajumuiya_id}}">{{$row->jina_kamili}}-{{$row->jina_la_jumuiya}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Kiasi cha zaka:</label>
                        <input type="number" min="0.0" step="any" id="kiasi" name="kiasi" class="form-control" placeholder="Jaza kiasi" required>
                    </div>

                    <div class="form-group">
                        <label for="">Tarehe:</label>
                        <input type="date" class="form-control" id="tarehe" name="tarehe" placeholder="chagua tarehe" required>
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

<script>
$("document").ready(function(){

        //loading the select2 plugin
        $('#jumuiya,#mwanajumuiya').select2({
            dropdownParent: $('#zakaModal'),
            theme: 'bootstrap4',
            placeholder: 'tafuta ..',
            allowClear: true,
            width: '100%',
        });

        //datatables
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

        //turning on the modal
        $('#zakaBtn').on('click', function(){
            $('#submitBtn').attr('disabled',false);
            $('#mwanajumuiya,#jumuiya').val('').trigger('change');
            $('#createMwanajumuiyaDiv').show();
            $('#updateMwanajumuiyaDiv').hide();
            $('#createJumuiyaDiv').show();
            $('#updateJumuiyaDiv').hide();
            $('#zakaForm')[0].reset();
            $('.modal-title').text('Weka zaka');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#zakaModal').modal({backdrop: 'static',keyboard: false});
            $('#zakaModal').modal('show');
        })

        //dynamic changes of mwanajumuiya
        $('#jumuiya').change(function () {
            var jumuiya_kanda = $(this).val();
            $.ajax({
                url: "{{route('zaka.get_mwanajumuiya')}}",
                method: "POST",
                data: {
                    jumuiya: jumuiya_kanda
                },
                dataType: "json",
                success: function (data) {
                    var len = data.length;
                    $('#mwanajumuiya').empty();
                    for (var i = 0; i < len; i++) {
                        var mwanajumuiya = data[i]['id'];
                        var mwanajumuiya_jina = data[i]['jina_kamili']
                        $('#mwanajumuiya').append("<option value='" + mwanajumuiya + "'>" +
                            mwanajumuiya_jina + "</option>");
                    }
                }
            })
        });

        //submitting values
        $('#zakaForm').on('submit', function(event){
            event.preventDefault();

            $('#submitBtn').attr('disabled',true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('zaka.store')}}";

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
                    $('#zakaModal').modal('hide');

                    //reseting the form
                    $('#zakaForm')[0].reset();

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

    })
</script>
    
@endsection