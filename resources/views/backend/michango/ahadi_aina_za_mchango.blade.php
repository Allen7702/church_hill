@extends('layouts.master') @section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($mchango->aina_ya_mchango)}}

                    <span class="badge badge-success">{{strtoupper($mchango->ahadi_ya_aina_za_mchango->status)}}</span>
                </h4>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{route('weka_ahadi_mwanajumuiya',$mchango->id)}}"><button class="btn btn-sm btn-info mr-1">Weka ahadi wanajumuiya</button></a>
                </div>
            <div class="col-md-2 text-right">
            <a href="{{route('ahadi_michango_jumuiya_jumla',$mchango->id)}}"><button class="btn btn-sm btn-info mr-1">Angalia ulipaji</button></a>
            </div>

        </div>
        <br />
        <div class="row">
            <div class="col-md-3">
                <label>Namba ya mchango:</label>
                <input  class="form-control" type="text"
                    value="MCH00{{$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id}}" />
            </div>
            <div class="col-md-3">
                <label>Kiasi kinachoitajika:</label>
                <input  class="form-control" type="text"
                    value="{{number_format($mchango->ahadi_ya_aina_za_mchango->kiasi,2)}}" />
            </div>
            <div class="col-md-3">
            
                <label>Mchango:</label>
                <input  class="form-control" type="text" value="{{number_format($sum_michango_iliyorecodiwa,2)}}" />
            </div>
            <div class="col-md-3">
                <label>Tarehe ya mwisho:</label>
                <input  class="form-control" type="text" value="{{\Carbon\carbon::parse($mchango->ahadi_ya_aina_za_mchango->tarehe_ya_mwisho)->format('d/m/Y')}}" />
            </div>
        </div>

    </div>

    <div class="card-body">
        <form id="ahadiAinaForm">
            @csrf
            <input type="hidden" value="{{$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id}}" name="ahadi_michango_id" />
            <div class="form-group">
                <div class="row">
                    @foreach ($jumuiyas as $row)
                    @php
                    $jumuiya_exist=$row->mchango_gawia->where('ahadi_michango_id',$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id)->first();
                    @endphp
                    <div class="col-md-4 mb-3">
                        <label>{{$row->jina_la_jumuiya}}</label>
                        <input type="hidden" name="record[{{$loop->index+0}}][jumuiya]" value="{{$row->id}}">
                        <input type="text" name="record[{{$loop->index+0}}][kiasi]" class="kiasi form-control" 
                            @if(is_null($jumuiya_exist))
                            value=""
                            @else
                            value="{{number_format($jumuiya_exist->kiasi)}}"
                            @endif
                           
                            @if($mchango->ahadi_ya_aina_za_mchango->status=='aujaanza')
                           
                            @else
                            readonly
                            @endif
                            >
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <button type="reset" class="btn btn-warning mr-2">Anza upya</button>
                @if($mchango->ahadi_ya_aina_za_mchango->status=='aujaanza')
                <button type="submit" class="btn btn-info">Gawia mchango</button>
                @endif
            </div>
    </div>
</div>
</form>



<script type="text/javascript">
    $("document").ready(function () {

        $(".kiasi").on('keyup', function (evt) {

            var input = $(this);

            if (input.val().length > 0 && input.val() != "") {
                var catestedNumber = input.val().replace(/,/g, '')
            } else {
                var catestedNumber = input.val();
            }

            if (catestedNumber == "") {

            } else if (isNaN(catestedNumber)) {

            } else {
                if (evt.which != 110) {//not a fullstop
                    var n = parseFloat($(this).val().replace(/\,/g, ''), 10);
                    $(this).val(n.toLocaleString());
                }
            }
        });

        $('#ahadiAinaForm').submit(function (event) {
            event.preventDefault();


         

            var url_data = "{{route('ahadi_michango_gawia_jumuiya')}}";

            var total_mchango = "{{$mchango->ahadi_ya_aina_za_mchango->kiasi}}";
        
            var sum_gawio = 0;
            $('.kiasi').each(function () {
                sum_gawio += parseFloat($(this).val().replace(/,/g, ''));
            });
  
            if (sum_gawio>parseFloat(total_mchango)) {

                Swal.fire({
                    title: "<b class='text-primary'>Taarifa!</b>",
                    icon: "info",
                    text: "Tafadhali hakiki tena kiasi kilichojazwa  kimezidi  jumla ya gawio ..",
                    timer: 8000,
                    showConfirmButton: false,
                   showCancelButton: false,
                }) 
                return false;
            }else{


            $.ajax({
                url: url_data,
                method: "GET",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function (data) {

                    //if we have a successfully request
                    if (data.success) {

                      //  $('#denominationModal').modal('hide');

                        var message = data.success;

                        Toast.fire({
                            title: message,
                            icon: "success",
                        })

                        location.reload();
                      //  ajaxCall1();
                    }

                    //if we have a wrong request
                    if (data.errors) {
                        Swal.fire({
                    title: "<b class='text-primary'>Taarifa!</b>",
                    icon: "info",
                    text: "Tafadhali hakiki tena kiasi kilichojazwa  kimezidi na jumla ya gawio..",
                    timer: 8000,
                    showConfirmButton: false,
                   showCancelButton: false,
                }) 
                return false;
                    }
                }
            })
        }
        })
    
    });
</script>



@endsection