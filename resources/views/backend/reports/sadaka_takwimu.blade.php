@extends('layouts.report_master')
@section('content')
    {{-- This part shows the header of report it has been from app serv with variable church_details --}}
    @include('layouts.report_header')

    <table class="table">

        <tr>
            <th>S/N</th>
            <th>SADAKA</th>
            <th>KIASI (TZS)</th>
        </tr>

        <tr>
            <td>1</td>
            <td>Sadaka</td>
            <td>{{number_format($sadaka_kuu,2)}}</td>
        </tr>

        <tr>
            <td>2</td>
            <td>Sadaka za Jumuiya</td>
            <td>{{number_format($sadaka_jumuiya,2)}}</td>
        </tr>

        <tr>
            <td colspan="2">Jumla</td>
            <td>{{number_format($sadaka_total,2)}}</td>
        </tr>
        
    </table>

@endsection