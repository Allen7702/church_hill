@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-5">
                <h4>{{$title}}</h4>
            </div>
            <div class="col-sm-12 col-md-7">
                <div class="d-flex flex-row justify-content-end">
                    <div class="d-flex flex-row">
                        <a href="{{url('matumizi_benki')}}"><button class="btn btn-info btn-sm mr-2">Ona zaidi matumizi benki</button></a>
                        <a href="{{url('')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('')}}"><button class="btn btn-info btn-sm mr-0">EXCEL</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        
        <table class="table" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>KIASI (TZS)</th>
                <th>MWEZI</th>
                <th>KITENDO</th>
            </thead>
            <tfoot>
                <th>Jumla</th>
                <th colspan="3">{{number_format($matumizi_benki_total,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($matumizi_benki_data as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
                        <td>
                            <a href="{{url('matumizi_benki_mwezi',['id'=>$item->mwezi])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
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