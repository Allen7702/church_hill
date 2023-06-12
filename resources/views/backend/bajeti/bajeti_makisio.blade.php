@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>{{strtoupper($title)}}</h4>
            </div>
            <div class="card-body">
                <table class="table" style="width:100%;">
                    <thead class="bg-light">
                        <th>S/N</th>
                        <th>Aina ya bajeti</th>
                        <th>Kiasi (TZS)</th>
                        <th>Kitendo</th>
                    </thead>

                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>Makisio bajeti ya mapato</td>
                            <td>{{number_format($data_bajeti_mapato,2)}}</td>
                            <td><a href="{{url('bajeti_makisio_mapato')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Makisio bajeti ya matumizi</td>
                            <td>{{number_format($data_bajeti_matumizi,2)}}</td>
                            <td><a href="{{url('bajeti_makisio_matumizi')}}"><button class="btn btn-info btn-sm">Angalia</button></a></td>
                        </tr>

                        <tr style="font-weight:bold;">
                            <td colspan="2">Jumla</td>
                            <td colspan="2">{{number_format($data_bajeti_total,2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    
@endsection