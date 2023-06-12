@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="d-flex flex-row justify-content-end">
                        <div class="d-flex flex-row">
                            <a href="{{route('zaka_kwa_miezi_wanajumuiya_pdf',['jumuiya'=> $jumuiya->jina_la_jumuiya, 'mwaka' => $mwaka] ) }}"><button class="btn btn-info btn-sm mr-3">PDF</button></a>
                            <a href="{{route('zaka_kwa_miezi_wanajumuiya_excel',['jumuiya'=> $jumuiya->jina_la_jumuiya, 'mwaka' => $mwaka] ) }}"><button class="btn btn-info btn-sm mr-3">EXCEL</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
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

                        <div class="card-body overflow-auto" style="overflow: auto;">
                            <table class="table w-100 overflow-auto" border="1">
                                <tr class="">
                                <td  style="width: 10%; height: 36px;" rowspan="2">S/N</td>
                                <td  style="width: 10%; height: 36px;" rowspan="2">JINA LA MWANAJUMUIYA</td>
                                    <td  style="width: 10%; height: 36px;" colspan="12" align="center"> MWEZI</td>
                                </tr>
                                <tr>
                                    <td>
                                        January
                                    </td>
                                    <td>
                                        February
                                    </td>
                                    <td>
                                        March
                                    </td>
                                    <td>
                                        April
                                    </td>
                                    <td>
                                        May
                                    </td>
                                    <td>
                                        June
                                    </td>
                                    <td>
                                        July
                                    </td>
                                    <td>
                                        August
                                    </td>
                                    <td>
                                        September
                                    </td>
                                    <td>
                                        October
                                    </td>
                                    <td>
                                        November
                                    </td>
                                    <td>
                                        December
                                    </td>

                                </tr>
                                @php
                                    $index = 0;
                                @endphp
                                @foreach ($zaka_data as $data)

                                @foreach ($data as $item)
                                        @php
                                            $index = $index + 1;
                                        @endphp
                                    <tr>
                                        <td>{{$index}}</td>
                                        <td>{{$item['jina_kamili']}}</td>
                                            <td>
                                               @if(isset($item['zaka'][0]) && $item['zaka'][0]['mwezi'] == 'January')
                                                    {{number_format($item['zaka'][0]['kiasi'],2)}}
                                               @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][1]) && $item['zaka'][1]['mwezi'] == 'February')
                                                    {{number_format($item['zaka'][1]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][2]) && $item['zaka'][2]['mwezi'] == 'March')

                                                    {{number_format($item['zaka'][2]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][3]) && $item['zaka'][3]['mwezi'] == 'April')
                                                    {{number_format($item['zaka'][3]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][4]) && $item['zaka'][4]['mwezi'] == 'May')
                                                    {{number_format($item['zaka'][4]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][5]) && $item['zaka'][5]['mwezi'] == 'June')
                                                    {{number_format($item['zaka'][5]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][6]) && $item['zaka'][6]['mwezi'] == 'July')
                                                    {{number_format($item['zaka'][6]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][7]) && $item['zaka'][7]['mwezi'] == 'August')
                                                    {{number_format($item['zaka'][7]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][8]) && $item['zaka'][8]['mwezi'] == 'September')
                                                    {{number_format($item['zaka'][8]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][9]) && $item['zaka'][9]['mwezi'] == 'October')
                                                    {{number_format($item['zaka'][9]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][10]) && $item['zaka'][10]['mwezi'] == 'November')
                                                    {{number_format($item['zaka'][10]['kiasi'],2)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item['zaka'][11]) && $item['zaka'][11]['mwezi'] == 'December')
                                                    {{number_format($item['zaka'][11]['kiasi'],2)}}
                                                @endif
                                            </td>

                                    </tr>
                                @endforeach
                                @endforeach

                            </table>

                    </div>
                </div>
            </div>
            </div>
        </div>
@endsection
