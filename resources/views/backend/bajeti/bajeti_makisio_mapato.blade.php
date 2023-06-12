@extends('layouts.master')
@section('content')
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">MAKISIO BAJETI YA MAPATO</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="bajetiBtn" class="btn btn-info btn-sm mr-2">Ongeza bajeti </button>
                <a href="{{url('home')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('home')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table w-100" id="table">
            
            <thead class="bg-light">
                <th>S/N</th>
                <th>Aina ya mapato</th>
                <th>Kiasi</th>
                <th>Mwaka</th>
                {{-- <th>Imeundwa</th> --}}
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="3">{{number_format($data_bajeti_total,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($data_bajeti as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->aina_ya_mapato}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{$item->mwaka}}</td>
                        {{-- <td>{{Carbon::parse($item->created_at)->format('d-M-Y')}}</td> --}}
                        <td>
                            {{-- <button class="view btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button> --}}
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

<div id="bajetiModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="bajetiForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">
                            
                            <div class="col-md-6">
                                <label for="">Aina ya mapato</label>
                                <select name="aina_ya_mapato" id="aina_ya_mapato" class="form-control">
                                    <option value="">Chagua mapato</option>
                                    <option value="sadaka_jumuiya">Sadaka za jumuiya</option>
                                    <option value="zaka">Zaka</option>
                                    @foreach ($data_misa as $item)
                                        <option value="{{$item->jina_la_misa}}">{{$item->jina_la_misa}}</option>
                                    @endforeach
                                    @foreach ($data_michango as $item)
                                        <option value="{{$item->aina_ya_mchango}}">{{$item->aina_ya_mchango}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="">Kundi la mapato:</label>
                                <select name="kundi" id="kundi" class="form-control" required>
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
                                <label for="">Kiasi:</label>
                                <input class="form-control" min="0" type="number" id="kiasi" name="kiasi" placeholder="Kiasi" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="">Mwaka:</label>
                                <input class="form-control" min="1970" type="number" id="mwaka" name="mwaka" placeholder="Bajeti ya Mwaka" required>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea name="maelezo" class="form-control" id="maelezo" placeholder="Maelezo kuhusu misa" rows="2"></textarea>
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
        $('#bajetiBtn').on('click', function(){
            $('#bajetiForm')[0].reset();
            $('.modal-title').text('Ongeza bajeti');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#bajetiModal').modal({backdrop: 'static',keyboard: false});
            $('#bajetiModal').modal('show');
        })

        //submitting values
        $('#bajetiForm').on('submit', function(event){
            event.preventDefault();

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
            var data_url = "{{route('bajeti_makisio_mapato.store')}}";

            if($('#action').val() == "Update")
            var data_url = "{{route('bajeti_makisio_mapato.update')}}";

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
                    $('#bajetiModal').modal('hide');

                    //reseting the form
                    $('#bajetiForm')[0].reset();

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

        //editing aina za misa details
        $(document).on('click','.edit', function(event){
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr('id');

            //getting data from url
            $.ajax({
                url: "/bajeti_makisio_mapato/" + id + "/edit",
                dataType: "JSON",
                success: function(data){
                    $('#createDiv').hide();
                    $('#updateDiv').show();
                    $('#submitBtn').show();
                    $('#submitBtn').html('Badili Makisio');
                    $('#action').val('Update');
                    $('#hidden_id').val(id);
                    $('#aina_ya_mapato').val(data.result.aina_ya_mapato);
                    $('#maelezo').val(data.result.maelezo);
                    $('#kiasi').val(data.result.kiasi);
                    $('#mwaka').val(data.result.mwaka);
                    $('#kundi').val(data.result.kundi);
                    $('.modal-title').text('Badili bajeti');
                    $('#bajetiModal').modal({backdrop: 'static',keyboard: false});
                    $('#bajetiModal').modal('show');
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
                        url: "/bajeti_makisio_mapato/destroy/" + delete_id,
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