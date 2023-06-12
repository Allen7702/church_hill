@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h4>{{strtoupper($title)}}</h4>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="d-flex flex-row justify-content-end">
                    <div class="d-flex flex-row">
                        <a href="{{url('michango_benki')}}"><button class="btn btn-info btn-sm mr-2">Ona zaidi michango</button></a>
                        <a href="{{url('michango_benki_miezi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{url('michango_benki_miezi_excel')}}"><button class="btn btn-info btn-sm mr-0">EXCEL</button></a>
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
                <th colspan="3">{{number_format($michango_total,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($michango_benki_miezi as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{ date("F", mktime(0, 0, 0, $item->mwezi, 1)) }}</td>
                        <td>
                            <a href="{{url('michango_benki_mwezi',['id'=>$item->mwezi])}}"><button class="btn btn-sm btn-info">Angalia</button></a>
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