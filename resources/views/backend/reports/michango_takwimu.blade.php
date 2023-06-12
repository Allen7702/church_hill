@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>CHANZO</th>
            <th>KIASI (TZS)</th>
        </tr>

        <tr>
            <td>1</td>
            <td>Michango pesa taslimu</td>
            <td>{{number_format($michango_taslimu,2)}}</td>
        </tr>

        <tr>
            <td>2</td>
            <td>Michango benki</td>
            <td>{{number_format($michango_benki,2)}}</td>
        </tr>

        <tr>
            <td colspan="2">Jumla</td>
            <td>{{number_format($michango_total,2)}}</td>
        </tr>
        
    </table>

@endsection