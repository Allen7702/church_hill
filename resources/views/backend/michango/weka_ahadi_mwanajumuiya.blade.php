
@extends('layouts.master')

@section('content')

<div class="card">

    <div class="card-header">
        <div class="row">

            <div class="col-md-6 mt-2">
                <h3>{{strtoupper($title)}}</h3>
            </div>
            
            <div class="col-md-6">

                <form name="initial_form" id="initialForm" method="POST" action="{{route('ahadi_mkupuo_vuta_wanajumuiya')}}">
                    @csrf
                     <input type="hidden"  value="{{$mchango->id}}" name="mchango_id" />
                    <div class="form-group">
                        <select name="jumuiya" id="jumuiya" class="form-control select2" required>
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiya as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}-{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

            </div>
        </div>
    
    </div>

    <div class="card-body">
         
            
            <p class="text-primary">Hakiki taarifa zilizojazwa kabla ya kuwasilisha matoleo</p>
            
            <form id="ahadiAinaForm"  >
                
                @csrf

                @if($action == "mkupuo")

                    <div class="form-group">
                        
                        <div class="row">

                            <div class="col-md-6">
                                <label>Jina la jumuiya</label>
                                <input type="text" name="jumuiya" class="form-control" value="{{$selected_jumuiya}}">
                            </div>
                            
                            <div class="col-md-6">
                                @php
                                $jumuiya=App\Jumuiya::where('jina_la_jumuiya',$selected_jumuiya)->first();
                                $jumuiya_exist=$jumuiya->mchango_gawia->where('ahadi_michango_id',$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id)->first();
                                @endphp
                                <label>Ahadi</label>
                                <input type="text" name="jumuiya_gawia"  id="jumuiya_gawia"class="form-control" value="@if(is_null($jumuiya_exist)) 0.00 @else {{number_format($jumuiya_exist->kiasi,2)}} @endif">
                            </div>
                        </div>   
                    </div>
                    <input type="hidden" value="{{$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id}}" name="ahadi_michango_id" />
                    <div class="form-group">
                        <div class="row">
                            @foreach ($members as $row)

                            @php
                            $mwanajumuiya=App\Mwanafamilia::find($row->mwanajumuiya_id);
                            $mwanafamilia_exist=$mwanajumuiya->mchango_gawia->where('ahadi_michango_id',$mchango->ahadi_ya_aina_za_mchango->aina_ya_michango_id)->first();
                            @endphp
                            <div class="col-md-4 mb-3">
                                <label>{{$row->jina_kamili}}</label>
                                <input type="hidden" name="record[{{$loop->index+0}}][mwanajumuiya]" value="{{$row->mwanajumuiya_id}}">
                                <input type="text" name="record[{{$loop->index+0}}][kiasi]" class="kiasi form-control" step="any" min="0.0"
                                
                                @if(is_null($mwanafamilia_exist))
                                value=""
                                @else
                                value="{{number_format($mwanafamilia_exist->kiasi)}}"
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
                @endif

                <div class="form-group">
                    <button type="reset" class="btn btn-warning mr-2">Anza upya</button>
                    <button type="submit" class="btn btn-info">Wasilisha</button>
                </div>
            </form>
    </div>

</div>


<script>
    $("document").ready(function(){

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

            var url_data = "{{route('ahadi_michango_gawia_wanafamilia')}}";

          
        
            var sum_gawio = 0;
            $('.kiasi').each(function () {
                sum_gawio += parseFloat($(this).val().replace(/,/g, ''));
            });

            $.ajax({
                url: url_data,
                method: "GET",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function (data) {
                    //if we have a successfully request
                    if (data.success) {

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
               
                       
                    }
                }
            })
        
        })

        //declaring the value of zaka total
        var zaka_total_overall = 0;
        var denomination_total_overall = 0;

        //reseting the value of select two
        $('#jumuiya').val('').trigger('change');
        $('#ahadiAinaForm')[0].reset();

        //turning on select2
        $('#jumuiya').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya..',
            allowClear: true,
            // width: '100%',
        });

        $('#initialForm,select').on('change', function(){
            $('#initialForm').submit();
            // $(this).closest('form').submit();
        });     

    })
</script>

<script>

//handling the submission of zaka form
function ajaxCall1(){
    setTimeout(() => {
        $('#ahadiAinaForm').trigger('submit');
    },4500);
}

</script>
@endsection