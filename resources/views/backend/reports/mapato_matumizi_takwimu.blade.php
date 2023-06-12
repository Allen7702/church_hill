@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>Chanzo</th>
            <th>Kiasi</th>
        </tr>

        <tr>
            <td>1</td>
            <td>Mapato</td>
            <td>{{number_format($mapato_total,2)}}</td>
        </tr>

        <tr>
            <td>2</td>
            <td>Matumizi</td>
            <td>{{number_format($matumizi_total,2)}}</td>
        </tr>

        <tr style="font-weight: bold;">
            <td colspan="2">Salio</td>
            <td>{{number_format($salio,2)}}</td>
        </tr>
        
    </table>

@endsection