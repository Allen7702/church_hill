@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-12 text-center">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 col-md-12 text-center">
                <a href="{{url('mapato_zaidi')}}"><button class="btn btn-info btn-sm mr-2">Mapato</button></a>
                <a href="{{url('matumizi_taslimu')}}"><button class="btn btn-info btn-sm mr-2">Matumizi taslimu</button></a>
                <a href="{{url('matumizi_benki')}}"><button class="btn btn-info btn-sm mr-2">Matumizi Benki</button></a>
                <a href="{{url('aina_za_matumizi')}}"><button class="btn btn-info btn-sm mr-2">Aina za matumizi</button></a>
                <a href="{{url('ankra_za_madeni')}}"><button class="btn btn-info btn-sm mr-2">Ankra za madeni</button></a>
                <a href="{{url('mapato_matumizi_takwimu_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('mapato_matumizi_takwimu_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
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
                    <td colspan="2">Salio</td>
                    <td colspan="2">{{number_format($salio,2)}}</td>
                </tr>
            </tfoot>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Mapato</td>
                    <td>{{number_format($mapato_total,2)}}</td>
                    <td><a href="{{url('mapato_zaidi')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Matumizi</td>
                    <td>{{number_format($matumizi_total,2)}}</td>
                    <td><a href="{{url('matumizi_zaidi')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
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