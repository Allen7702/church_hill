@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card card-stats card-round">
            <div class="card-header">
                <div class="row">
                    {{-- title --}}
                    <div class="col-sm-6 col-md-6">
                        <h4>{{strtoupper($title)}}</h4>
                    </div>
        
                    {{-- exporting --}}
                    <div class="col-sm-6 col-md-6 text-right">
                        <a href="{{url('masakramenti_jumuiya_husika_pdf',['id'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('masakramenti_jumuiya_husika_excel',['id'=>$jumuiya])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                    </div>    
                </div>
            </div>

            <div class="card-body">
                
                <table class="table w-100" id="table">
                    <thead class="bg-light">
                        <th>ID</th>
                        <th>Mwanajumuiya</th>
                        <th>Simu</th>
                        <th>Ndoa</th>
                        <th>Ubatizo</th>
                        <th>Kipaimara</th>
                        <th>Komunio</th>
                        <th>Ekaristi</th>
                    </thead>

                    <tfoot>
                        <th>Jumla</th>
                        <th colspan="2">{{$wanajumuiya_wote}}</th>
                        <th>{{$ndoa}}</th>
                        <th>{{$ubatizo}}</th>
                        <th>{{$kipaimara}}</th>
                        <th>{{$komunio}}</th>
                        <th>{{$ekaristi}}</th>
                    </tfoot>
                    
                    <tbody>
                    
                        @foreach ($takwimu_za_kiroho as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->jina_kamili}}</td>
                                <td>{{$item->mawasiliano}}</td>
                                <td>{{$item->ndoa}}</td>
                                <td>{{$item->ubatizo}}</td>
                                <td>{{$item->kipaimara}}</td>
                                <td>{{$item->komunio}}</td>
                                <td>{{$item->ekaristi}}</td>
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
        //datatables
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
</script>

@endsection