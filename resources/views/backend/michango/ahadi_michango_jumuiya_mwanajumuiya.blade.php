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
                <th>Manajumuiya</th>
                <th>Ahadi</th>
                <th>Mchango</th>
                <th>Asilimia(%)</th>
            </thead>
            <tfoot>
                <th>Jumla</th>
                <th colspan="4"></th>
            </tfoot>

            <tbody>
               
                @foreach($mwanafamilias as $mwanafamilia)

             @php    $mwanajumuiya=App\Mwanafamilia::find($mwanafamilia->mwanajumuiya_id);
             $mwanafamilia_exist=$mwanajumuiya->mchango_gawia->where('ahadi_michango_id',$mchango->ahadi_ya_aina_za_mchango->id)->first();

             $michango_taslimu=App\MichangoTaslimu::where('aina_ya_mchango',$mchango->aina_ya_mchango)
              ->where('mwanafamilia',$mwanafamilia->mwanajumuiya_id)->sum('kiasi');
              $michango_benki=App\MichangoBenki::where('aina_ya_mchango',$mchango->aina_ya_mchango)
              ->where('mwanafamilia',$mwanafamilia->mwanajumuiya_id)->sum('kiasi');

              $sum_michango=$michango_benki+$michango_taslimu;
             @endphp
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$mwanafamilia->jina_kamili}}</td>
                    <td>@if(is_null($mwanafamilia_exist)) 0.00 @else {{number_format($mwanafamilia_exist->kiasi,2)}} @endif</td>
                    <td>{{number_format($sum_michango,2)}}</td>
                    <td>
                        @if(is_null($mwanafamilia_exist)) @php $ahadi=0; @endphp @else @php $ahadi=$mwanafamilia_exist->kiasi; @endphp @endif
                        @php
                        $ahadi==0?$asilimia=0: $asilimia=($sum_michango/$ahadi)*100;
                        @endphp
                        {{number_format($asilimia,2)}}
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