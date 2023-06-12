@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{$title}}</h4>
            </div>

            <div class="col-md-6 text-right">
                <a href="{{url('sadaka_jumuiya')}}"><button class="btn btn-info btn-sm mr-2">Weka sadaka jumuiya</button></a>
                <a href="{{url('sadaka_jumuiya_mwezi_pdf',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('sadaka_jumuiya_mwezi_excel',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>

        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            
            <thead class="bg-light">
                <th>S/N</th>
                <th>Jumuiya</th>
                <th>Kiasi</th>
                <th>Mwezi</th>
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="3">{{number_format($sadaka_jumuiya_total,2)}}</th>
            </tfoot>
            
            <tbody>
                @foreach ($data_sadaka_jumuiya as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_la_jumuiya}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
                        <td>
                            <a href="{{url('sadaka_jumuiya_husika_mwezi',['id'=>$item->mwezi,'jumuiya'=>$item->jina_la_jumuiya])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
                        </td>
                    </tr>
                @endforeach
            
            </tbody>
            
        </table>
        
    </div>
</div>

<script type="text/javascript">
    $("document").ready(function(){

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

    })
</script>
@endsection