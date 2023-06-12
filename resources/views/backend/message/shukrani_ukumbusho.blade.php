@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row mb-0">
                        <div class="col-md-7">
                            <h4>UJUMBE WA SHUKRANI NA UKUMBUSHO</h4>
                        </div>
                        <div class="col-md-5 text-right">
                            <a href="{{url('shukrani_ukumbusho_sample')}}"><button class="btn btn-sm btn-info">Jumbe zilizopo &nbsp;&nbsp;<i class="fas fa-fw fa-envelope"></i></button></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-0">
                        <div class="col-md-12">
                        
                               <form id="ujumbeForm">
                                @csrf
  
                                <input type="hidden" name="aina_ya_toleo" id="aina_ya_toleo" >
                                <input type="hidden" name="kundi" id="kundi" >

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Aina ya ujumbe:</label>
                                            <select class="form-control" name="kichwa" id="kichwa" required>
                                                <option value="">Chagua ujumbe</option>
                                                @foreach ($data_sample as $item)
                                                    <option value="{{$item->id}}">{{$item->kichwa}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                     
                                    </div>
                                    
                                    <div class="row" style="margin-top:20px;">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Kuanzia:</label>
                                            <input text="text" class="form-control" id="dateStart" data-provide="datepicker" name="kuanzia" style="padding:7px;" >
                                        </div>
            
                                        <div class="col-sm-12 col-md-6">
                                            <label for="">Hadi tarehe:</label>
                                            <input type="text" class="form-control" id="dateEnd" data-provide="datepicker" style="padding:7px;" name="mwisho">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <table id="table" class="table">

                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="">Ujumbe:</label>
                                    <textarea name="ujumbe" id="ujumbe" rows="4" class="form-control" required></textarea>
                                    <span class="ml-1" id="typed_chars"></span> Meseji: <span class="ml-4" id="total_chars"> </span> &nbsp;Herufi
                                </div>

                                <div class="form-group">
                                    <button id="submitBtn"  type="submit" class="btn btn-info btn-sm">Tuma ujumbe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 pr-md-0">
            <div class="card card-pricing">
                <div class="card-header">
                    <h4 class="card-title">Takwimu za Meseji</h4>
                    <div class="card-price">
                        <span class="price">{{number_format($today_message)}}</span>
                        <span class="text">Siku ya leo</span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="specification-list">

                        <li>
                            <span class="name-specification">Wiki hii</span>
                            <span class="status-specification">{{number_format($this_week)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwezi huu</span>
                            <span class="status-specification">{{number_format($this_month)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwaka huu</span>
                            <span class="status-specification">{{number_format($this_year)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Mwaka jana</span>
                            <span class="status-specification">{{number_format($last_year)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Jumla ya meseji</span>
                            <span class="status-specification">{{number_format($total_messages)}}</span>
                        </li>

                        <li>
                            <span class="name-specification">Salio la meseji</span>
                            <span class="status-specification">{{number_format($sms_balance)}}</span>
                        </li>

                    </ul>
                </div>

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
        $(document).ready(function(){ 
            //start date
            $("#dateStart").datepicker( {
    //settings
    startDate: new Date('2022-01-01'),
    autoclose: true
}).on('changeDate', function(ev) {
    //Get actual date objects from both
    var dt1 = $('#dateStart').data('datepicker').viewDate;
    var dt2 = $('#dateEnd').data('datepicker').viewDate;
    if (dt2 < dt1) {
        //If #dateEnd is before #dateStart, set #dateEnd to #dateStart
        $('#dateEnd').datepicker('update', $('#dateStart').val());
    }
    //Always limit #dateEnd's starting date to #dateStart's value
    $('#dateEnd').datepicker('setStartDate', $('#dateStart').val());
});

$("#dateEnd").datepicker( {
    //settings
    startDate: $('#dateStart').val(),
    autoclose: true,
   
    datesDisabled:[
        "01","02","03","04","05","06","07","08","09","10","11","12","13","14","15",
        "16","17","18","19","20","21","22","23","24","25"
]
})
            //picking wasiotoa table base on date choosing
            $('#dateEnd,#dateStart,#kichwa').change(function()
         {
            var dateEnd=$('#dateEnd').val();
            var dateStart=$('#dateStart').val();
            var kichwa = $('#kichwa').val();

          

            if(!dateEnd==""){
          if(kichwa==""){
                         Toast.fire({
                        icon: 'error',
                        title: "Tafadhali changua aina ya ujumbe",
                    })
            }else{

                var dataString = 'kuanzia='+dateStart+'&mwisho='+dateEnd+'&kichwa='+kichwa;
      $.ajax
     ({      
url:"{{route('ajax_get_number_wasiotoa')}}",
type:"GET",
dataType: 'json',
data: dataString,
cache: true,
success: function(data)
{
$("#table").html(data.table);
}

});

      }
    }
   
});
            

            //reseting the form
            $('#ujumbeForm')[0].reset();

            //dynamic changes of message
            $('#kichwa').change(function () {
                var kichwa_id = $(this).val();
                $.ajax({
                    url: "{{route('shukrani_ukumbusho.get_message')}}",
                    method: "POST",
                    data: {
                        kichwa_id: kichwa_id,
                    },
                    dataType: "json",
                    success: function (data) {
                        var len = data.length;
                        $('#ujumbe').empty();
                        $('#ujumbe').val(data.result.ujumbe);
                        $('#aina_ya_toleo').val(data.result.aina_ya_toleo);
                        $('#kundi').val(data.result.kundi);
                    }
                })
            });

            //handling the submission of data
            $('#ujumbeForm').on('submit',function(event){
                event.preventDefault();

               $("#submitBtn").attr("disabled", true);

                $.ajax({
                    url: "{{route('shukrani_ukumbusho.post_message')}}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "JSON",

                    beforeSend:function(){
                        return confirm("Unakaribia kutuma meseji endelea?");
                    },

                    success: function(data){
                        //successfully sent message
                        if(data.success){

                            $("#submitBtn").attr("disabled", true);

                            var message = data.success;

                            Swal.fire({
                                title: "<b class='text-primary'>Taarifa!</b>",
                                text: message,
                                timer: 4500,
                                showConfirmButton: false,
                                showCancelButton: false,
                                icon: 'success',
                            });

                            setTimeout(function(){
                                window.location.reload();
                            },3600);
                        }

                        //unable to send the message
                        if(data.errors){
                            $("#submitBtn").attr("disabled", false);

                            var message = data.errors;

                            Swal.fire({
                                title: "<b class='text-primary'>Taarifa!</b>",
                                text: message,
                                timer: 4500,
                                showConfirmButton: false,
                                showCancelButton: false,
                                icon: 'info',
                            });
                        }

                        //some message sent to send the message
                        if(data.info){

                            $("#submitBtn").attr("disabled", false);

                            var message = data.info;

                            Swal.fire({
                                title: "<b class='text-primary'>Taarifa!</b>",
                                text: message,
                                timer: 4500,
                                showConfirmButton: false,
                                showCancelButton: false,
                                icon: 'info',
                            });
                        }
                    },
                    error:function(data){

                        console.log(data.error);

                    }
                    
                })
            })

            //dealing with counting the message length
            //handling messages
            $('textarea').mouseover(function(){
                var initial = 0;
                var message_count = Math.ceil($(this).val().length/160);
                var total_typed = initial + $(this).val().length;
                $('#typed_chars').text(message_count);
                $('#total_chars').text(total_typed);
                $('#number_of_message').val(message_count);
            })

        })
    </script>
@endsection
