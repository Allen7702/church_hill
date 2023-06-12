@extends('layouts.master')
@section('content')
<div class="card">
    
    <div class="card-header">
        <div class="row">
            
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>

            <div class="col-md-6 text-right">
                <a href="{{url('sadaka_kuu')}}"><button class="btn btn-info btn-sm mr-2">Weka sadaka</button></a>
                <a href="{{url('sadaka_kuu_zaidi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('sadaka_kuu_zaidi_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>

        </div>
    </div>

    <div class="card-body">
        <table class="table" id="table">
            
            <thead class="bg-light">
                <th>S/N</th>
                <th>SADAKA (TZS)</th>
                <th>MWEZI</th>
                <th>KITENDO</th>
            </thead>

            <tfoot>
                <th>Jumla</th>
                <th colspan="3">{{number_format($sadaka_kuu,2)}}</th>
            </tfoot>

            <tbody>

                @foreach ($data_sadaka as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
                        <td>
                            <a href="{{url('sadaka_kuu_mwezi',['id'=>$item->mwezi])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        
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