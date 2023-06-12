@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">

                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-info mr-2">CHAPISHA</button>
                        <a href="{{url()->previous()}}"><button class="btn btn-warning btn-sm">Rudi</button></a>
                    </div>
                </div>
                
                <div class="col-md-12">
                    @include('layouts.header')   
                    <hr width="100%"> 
                </div>

                <div class="col-md-12 text-center">
                    <h4>{{strtoupper($kichwa)}}</h4>
                </div>
                
            </div>

            <div class="card-body">
                <table class="table_other table table-bordered">

                    <thead class="bg-light">
                        <th>S/N</th>
                        <th colspan="3">NDOA</th>
                        <th colspan="4">WANAJUMUIYA</th>
                        <th>JUMLA YA KAYA</th>
                        <th colspan="3">NYINGINEZO</th>
                        <th>JUMLA</th>
                    </thead>

                    <tbody>
                        <tr>
                            <td></td>
                            <td>Kikatoliki</td>
                            <td>Mseto</td>
                            <td>Bila Ndoa</td>
                            <td>Wanaume</td>
                            <td>Wanawake</td>
                            <td>Vijana</td>
                            <td>Watoto</td>
                            <td>&nbsp;</td>
                            <td>Wajane</td>
                            <td>Wagane</td>
                            <td>Pekee</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>{{$data_ndoa_kikatoliki}}</td>
                            <td>{{$data_ndoa_mseto}}</td>
                            <td>{{$data_bila_ndoa}}</td>
                            <td>{{number_format($data_wanaume)}}</td>
                            <td>{{number_format($data_wanawake)}}</td>
                            
                            <td>
                                {{number_format($data_vijana)}}
                            </td>

                            <td>
                                {{number_format($data_watoto)}}
                            </td>

                            <td>{{number_format($data_jumla_kaya)}}</td>
                            <td>{{$data_wajane}}</td>
                            <td>{{$data_wagane}}</td>
                            <td>{{$data_pekee}}</td>
                            <td>{{$data_wanajumuiya_wote}}</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection