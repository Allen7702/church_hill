@extends('layouts.master') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <h4 class="text-bold">{{ucfirst(strtolower($title))}}</h4>
                    </div>
                    <div class="col-md-7">
                        <div class="row">

                            <div class="btn-group">
                    <button class="btn btn-outline-secondary">
                    {{$jumuiya_title}}
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a
                            class="dropdown-item"
                            href="{{ request()->fullUrlWithQuery(['jumuiya'=>'all']) }}"
                            >All</a
                        >
                        @foreach($jumuiyas as $jumuiya)
                        <a
                            class="dropdown-item"
                            href="{{ request()->fullUrlWithQuery(['jumuiya'=>$jumuiya->id]) }}"
                            >{{$jumuiya->jina_la_jumuiya}}</a
                        >
                        @endforeach
                            
            </div>
                </div>
               
                <div class="btn-group">
                    <button class="btn btn-outline-secondary">
                       {{$mchango_title}}
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item"
                            href="{{ request()->fullUrlWithQuery(['mchango'=>'zaka']) }}"
                            >Zaka</a
                        >
                        @foreach($michangos as $michango)
                        <a
                        class="dropdown-item"
                        href="{{ request()->fullUrlWithQuery(['mchango'=>$michango->id]) }}"
                        >{{ucfirst(strtolower($michango->aina_ya_mchango))}}</a>
                       @endforeach
               
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-outline-secondary">
                        {{$wachangiaji_title}}
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                    

                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item"
                            href="{{ request()->fullUrlWithQuery(['wachangiaji'=>'all']) }}"
                            >All</a
                        >
                        <a
                        class="dropdown-item"
                        href="{{ request()->fullUrlWithQuery(['wachangiaji'=>'waliotoa']) }}"
                        >Waliotoa</a
                    >
                    <a
                    class="dropdown-item"
                    href="{{ request()->fullUrlWithQuery(['wachangiaji'=>'wasiotoa']) }}"
                    >Wasiotoa</a
                >
               
                    </div>
                </div>
                            <div class="col-md-2">
                                 <lablel>Tarehe</lablel>
                                <button  class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#tareheModal">Tarehe</button></a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="table">
                   
                    <thead class="bg-light">
                        <th>S/N</th>
                        <th>Jumuiya</th>
                        <th>Mwanajumuiya</th>
                        @if(request()->mchango=='zaka')
                        <th>Zaka</th>
                        @else
                        <th>Mchango</th>
                        @endif
                        <!-- <th>Kitendo</th> -->
                    </thead>
                    <tbody>
                        @foreach($mwanajumuiyas as $mwanajumuiya)
                        <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$mwanajumuiya->jina_la_jumuiya}}</td>
                        <td>{{$mwanajumuiya->jina_kamili}}</td>
                        @if(request()->mchango=='zaka')
                        <td>{{number_format($mwanajumuiya->kiasi,2)}}</td>
                        @else
                        <td>{{number_format(($mwanajumuiya->benki_kiasi+$mwanajumuiya->taslimu_kiasi),2)}}</td>
                        @endif
                        <!-- <td> <a href="{{url('mwanafamilia',['id'=>$mwanajumuiya->mwanafamilia_id])}}"><button class="btn btn-sm btn-info mr-1" >Angalia</button></a></td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tareheModal" tabindex="-1"    data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h4>Chuja kwa tarehe</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="GET">
               @csrf
            <div class="row" style="margin-top:20px;">
                <div class="col-sm-12 col-md-6">
                    <label for="">Kuanzia:</label>
                    <input text="text" class="form-control" id="dateStart" data-provide="datepicker" name="kuanzia" style="padding:7px;"  @if(request()->startDate) value="{{request()->startDate}}" @endif >
                </div>

                <div class="col-sm-12 col-md-6">
                    <label for="">Hadi tarehe:</label>
                    <input type="text" class="form-control" id="dateEnd" data-provide="datepicker" style="padding:7px;" name="mwisho"   @if(request()->endDate) value="{{request()->endDate}}" @endif>
                </div>
            </div>
            <div class="row" style="padding-top:20px;">
                @if(!request()->startDate=="" & !request()->endDate=="")
                <div class="col-md-4"><button   id="resetFilter" class="btn btn-warning">Anza upya </button></div>
                  @else
                  <div class="col-md-4"></div>
                @endif
                <div class="col-md-4"></div>
                <div class="col-md-4"><button   id="dateFilter" class="btn btn-primary">Chuja</button></div>
            </div>
          </form>
        </div>
  
      </div>
    </div>
  </div>
  

  <script>
  $(document).ready(function(){
            //start date
            $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

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

$('#dateFilter').click(function(e){
   e.preventDefault();
   var startDate=$('#dateStart').val();
   var endDate=$('#dateEnd').val();
   if(startDate=='' & endDate==''){
       alert('Tafadhali jaza tarehe ya Kuanzia na ya Mwisho');
   }else{
   let queryString = window.location.search;  // get url parameters
   let params = new URLSearchParams(queryString); 
   params.delete('startDate');  // delete city parameter if it exists, in case you change the dropdown more then once
   params.append('startDate', startDate); 
   params.delete('endDate');  // delete city parameter if it exists, in case you change the dropdown more then once
   params.append('endDate', endDate); 
   document.location.href = "?" + params.toString();
   }
})

 $('#resetFilter').click(function(e){
   e.preventDefault();
   var startDate=$('#dateStart').val("");
   var endDate=$('#dateEnd').val("");
   let queryString = window.location.search;  // get url parameters
   let params = new URLSearchParams(queryString); 
   params.delete('startDate');  // delete city parameter if it exists, in case you change the dropdown more then once
   params.delete('endDate');  // delete city parameter if it exists, in case you change the dropdown more then once
 })

  })

  </script>

@endsection
