@extends('layouts.master')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h4>{{strtoupper($title)}}</h4>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="d-flex flex-row justify-content-end">
                        <div class="d-flex flex-row">
                            <button id="jumuiyaBtn" class="btn btn-info btn-sm mr-3">Ongeza salio la Meseji</button>
{{--                            <a href="{{url('zaka_takwimu_pdf')}}"><button class="btn btn-info btn-sm mr-3">PDF</button></a>--}}
{{--                            <a href="{{url('zaka_takwimu_excel')}}"><button class="btn btn-info btn-sm mr-3">EXCEL</button></a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <table class="table" id="table">
                <thead class="bg-light" style="font-size: 9px">
                <th style="font-size: 9px">S/N</th>
                <th style="font-size: 9px">Invoice Number</th>
                <th style="font-size: 9px">Payment Ref </th>
                <th style="font-size: 9px">Status</th>
                <th style="font-size: 9px">Recharge SMS</th>
                <th style="font-size: 9px">Payment Channel</th>
                <th style="font-size: 9px">Issued On</th>
                <th style="font-size: 9px">Total Amount</th>
                <th style="font-size: 9px">Action</th>
                </thead>

                <tbody>
                @foreach ($invoices as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->invoice_number}}</td>
                        <td>{{$item->payment_ref}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->recharge_sms}}</td>
                        <td>{{$item->channel}}</td>
                        <td>{{$item->created_at->diffForHumans()}}</td>
                        <td>{{number_format($item->amount,2)}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Vitendo
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('zaka_kwa_miezi_jumuiya',['jumuiya'=> $item->jumuiya, 'mwaka' => $item->mwaka] )  }}">malipo ya miezi ya jumuiya</a>
                                    <a class="dropdown-item" href="{{ route('zaka_kwa_miezi_wanajumuiya',['jumuiya'=> $item->jumuiya, 'mwaka' => $item->mwaka] )  }}">malipo ya miezi ya wanajumuiya</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="jumuiyaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Title</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>

                <form id="jumuiyaForm">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Namba ya simu:</label>
                            <input class="form-control" type="text" id="phone_number" name="phone_number" placeholder="Namba ya simu" required>
                        </div>

                        <div class="form-group" id="createDiv">
                            <label for="">Njia ya malipo:</label>
                            <select name="channel" id="channel" class="form-control select2">
                                <option value="" disabled>Chagua njia ya malipo</option>
                                <option value="tigo_pesa">TIGO PESA</option>
                                <option value="m_pesa">M PESA</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="">Kiasi:</label>
                            <input name="amount" class="form-control" type="number" id="number" placeholder="Kiasi" />
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


    <script>
        $("document").ready(function(){
            //loading datatable
            $('#table').DataTable({
                responsive: true,
                stateSave: true,
            })
        })

        //turning on the modal
        $('#jumuiyaBtn').on('click', function(){
            $("#submitBtn").attr("disabled", false);
            $('#createDiv').show();
            $('#updateDiv').hide();
            $('#jumuiyaForm')[0].reset();
            $('.modal-title').text('Lipa salio la Meseji');
            $('#action').val('Generate');
            $('#submitBtn').show();
            $('#submitBtn').html('Wasilisha');
            $('#jumuiyaModal').modal({backdrop: 'static',keyboard: false});
            $('#jumuiyaModal').modal('show');
        })

        //submitting values
        $('#jumuiyaForm').on('submit', function(event){
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = '';

            //setting the route to hit
            if($('#action').val() == "Generate")
                var data_url = "{{route('jumuiya.store')}}";

            if($('#action').val() == "Update")
                var data_url = "{{route('jumuiya.update')}}";

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

                        //reseting the value of select2
                        $('#jina_la_kanda').val('').trigger('change');

                        //hiding modal
                        $('#jumuiyaModal').modal('hide');

                        //reseting the form
                        $('#jumuiyaForm')[0].reset();

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
                            // $("#myCard").load(location.href + " #myCard");

                        },4500);
                    }

                    //if we have error show this
                    if(data.errors){

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
    </script>

@endsection
