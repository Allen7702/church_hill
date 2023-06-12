@extends('layouts.master')
@section('content')
<div class="row">
<div class="col-md-12">
<div class="card">
    <div class="card-header">
        <div class="row text-right">
            <div class="col-md-12">
                <a href="#"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="#"><button class="btn btn-info btn-sm mr-0">EXCEL</button></a>
                
            </div>
        </div>
        
        <div class="col-md-12">
            @include('layouts.header')    
        </div>
    </div>

 

    <div class="card-body">
        
        <table class="table" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jina la jumuiya</th>
                <th>Ahadi</th>
                <th>Mchango</th>
                <th>Asilimia(%)</th>
                <th>Kitendo</th>
            </thead>
            <tfoot>
                <th>Jumla</th>
                <th colspan="4"></th>
            </tfoot>

            <tbody>
                @foreach ($jumuiyas as $jumuiya)

             @php
             $jumuiya_exist=$jumuiya->mchango_gawia->where('ahadi_michango_id',$mchango->ahadi_ya_aina_za_mchango->id)->first();
              $sum_kiasi=App\AwamuMichango::where('jumuiya_id',$jumuiya->id)->sum('kiasi');
             @endphp

                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$jumuiya->jina_la_jumuiya}}</td>
                        <td>
                            @if(is_null($jumuiya_exist))
                              0.00
                            @else
                           {{number_format($jumuiya_exist->kiasi,2)}}
                            @endif
                        </td>
                        <td>
                            {{number_format($sum_kiasi,2)}}
                        </td>
                        <td>
                            
                            @if(is_null($jumuiya_exist))
                            @php  $ahadi=0;@endphp
                           @else
                           @php  $ahadi=$jumuiya_exist->kiasi; @endphp
                             @endif
                             @php
                             $asilimia= $ahadi==0 ?0 : ($sum_kiasi/$ahadi)*100;
                            @endphp
                            {{number_format($asilimia,2)}}
                        </td>
                        <td>
                            <a href="{{route('ahadi_michango_jumuiya_mwanajumuiya',[$mchango->id,$jumuiya->id])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
</script>
@endsection