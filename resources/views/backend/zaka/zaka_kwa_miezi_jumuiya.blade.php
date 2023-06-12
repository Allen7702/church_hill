@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if($church_details != "")
                        <table style="width:100%">
                            <tr class="text-center">
                                <td style="font-size: 18px; font-weight:700; color: #008BFF;">
                                    {{$church_details->jimbo}}
                                </td>
                            </tr>

                            <tr class="text-center">
                                <td style="font-size: 16px; font-weight:600; color: #008BFF;">
                                    {{$church_details->centre_name}}
                                </td>
                            </tr>

                            <tr>
                                <td style="text-align:center;">Anwani: {{$church_details->address}} {{$church_details->region}} {{$church_details->country}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">Simu ya mezani: {{$church_details->telephone1}} || Baruapepe: {{$church_details->email}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">{{$title}}</td>
                            </tr>
                            <tr  class="text-center text-indigo">
                                <td style="font-size: 15px; font-weight:600;">{{$jumuiya->jina_la_jumuiya}}</td>
                            </tr>
                        </table>
                    @endif
                </div>

                <div class="card-body">
                    <table class="table w-100" >
                        <thead class="bg-light">
                        <th>S/N</th>
                        <th>MWEZI</th>
                        <th> MWAKA</th>
                        <th>KIASI</th>
                        </thead>
                        <tbody>
                        @foreach ($zaka_data as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->mwezi}}</td>
                                <td>{{$item->mwaka}}</td>
                                <td>{{number_format($item->kiasi,2)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

            </div>
        </div>
    </div>
@endsection
