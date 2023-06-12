@extends('layouts.master')
@section('content')
<div class="card">

    <div class="card-header">
        <div class="row">

            <div class="col-md-7">
                <h4>{{strtoupper($title)}}</h4>
            </div>

            <div class="col-md-5 text-right">
                <a href="{{url('mapato_zaidi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('mapato_zaidi_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>
            
        </div>
    </div>

    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Chanzo</th>
                <th>Kiasi</th>
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="2">Jumla</td>
                    <td colspan="2">{{number_format($mapato_total,2)}}</td>
                </tr>
            </tfoot>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Sadaka</td>
                    <td>{{number_format($sadaka_total,2)}}</td>
                    <td><a href="{{url('sadaka_kuu_takwimu')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Zaka</td>
                    <td>{{number_format($zaka_total,2)}}</td>
                    <td><a href="{{url('zaka_takwimu')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Michango</td>
                    <td>{{number_format($michango_total,2)}}</td>
                    <td><a href="{{url('michango_takwimu')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script>
    $("document").ready(function(){
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })
    })
</script>

@endsection