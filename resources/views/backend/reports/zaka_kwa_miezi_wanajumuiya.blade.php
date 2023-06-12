@extends('layouts.report_master')
@section('content')

    @include('layouts.report_header')

    <table class="table">
        <tr class="">
            <td style="width: 10%; height: 36px;" rowspan="2">S/N</td>
            <td style="width: 10%; height: 36px;" rowspan="2">JINA LA MWANAJUMUIYA</td>
            <td style="width: 10%; height: 36px;" colspan="12" align="center"> MWEZI</td>
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
        @foreach ($zaka_data as $data)
            @foreach ($data as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
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

@endsection
