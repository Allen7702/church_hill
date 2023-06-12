@extends('layouts.report_master') @section('content') @include('layouts.report_header')
<div class="card-body">
    <table class="table_other table">

        <thead class="bg-light">

            <th colspan="2" class="text-info"></th>
            <th></th>
        </thead>
        <tbody>
            <th>Mwanajumuiya</th>
            <th>Kiasi</th>

            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{$d->jina_kamili}}</td>
                    <td>{{number_format($d->kiasi,2)}}</td>

                </tr>
                @endforeach
                <tfoot>
                    <th>Jumla</th>
                    <th colspan="2"> {{number_format($data->sum('kiasi'),2)}}</th>
                </tfoot>
            </tbody>
        </tbody>
    </table>
</div>
@include('layouts.report_footer_signature') @endsection