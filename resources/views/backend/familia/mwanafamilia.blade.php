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
                </table>
                @endif
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($binafsi->count() != 0)
                            @if($binafsi->jinsia == "Mwanaume")
                                <img src="{{asset('images/profile.png')}}" width="70%" alt="">
                            @else
                                <img src="{{asset('images/female.png')}}" width="70%" alt="">
                            @endif
                        @else
                            <img src="{{asset('images/profile.png')}}" width="70%" alt="">
                        @endif
                    </div>

                    <div class="col-md-5">
                        <h4 class="text-info">Taarifa binafsi</h4><hr>
                        <ul style="list-style-type:none;padding:0; margin:0;">
                            <div class="row text-left">
                                <div class="col-xs-12 col-md-6">
                                    <li>Namba #:</li>
                                    <li>Jina:</li>
                                    <li>Jinsia:</li>
                                    <li>Mawasiliano:</li>
                                    <li>Cheo cha familia:</li>
                                    <li>Tarehe ya kuzaliwa:</li>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    @if($binafsi->count() != 0)
                                        <li>{{$binafsi->namba_utambulisho}},</li>
                                        <li>{{$binafsi->jina_kamili}},</li>
                                        <li>{{$binafsi->jinsia}},</li>
                                        <li>{{$binafsi->mawasiliano}},</li>
                                        <li>{{$binafsi->cheo_familia}},</li>
                                        <li>{{Carbon::parse($binafsi->dob)->format('d/M/Y')}}</li>
                                    @else
                                    <li>--,</li>
                                    <li>--,</li>
                                    <li>--,</li>
                                    <li>--,</li>
                                    <li>--</li>
                                    @endif
                                </div>
                            </div>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <h4 class="text-info">Taarifa za masakramenti:</h4><hr>
                        <ul style="list-style-type:none;padding:0; margin:0;">
                            <div class="row text-left">
                                <div class="col-xs-12 col-md-6">
                                    <li>Ndoa:</li>
                                    <li>Ubatizo:</li>
                                    <li>Komunio:</li>
                                    <li>Kipaimara:</li>
                                    <li>Ekaristi:</li>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    @if($binafsi->count() != 0)
                                    <li>{{$binafsi->ndoa}},</li>
                                    <li>{{$binafsi->ubatizo}},</li>
                                    <li>{{$binafsi->komunio}},</li>
                                    <li>{{$binafsi->kipaimara}},</li>
                                    <li>{{$binafsi->ekaristi}}</li>
                                    @endif
                                </div>
                            </div>

                        </ul>
                    </div>
                </div>

                <div class="separator-solid"></div>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <h4 class="text-primary">Taarifa za jumuiya</h4>
                        <div class="separator-solid"></div>

                        <ul style="list-style-type:none;padding:0; margin:0;">
                            <div class="row text-left">
                                <div class="col-xs-12 col-md-6">
                                    <li>Jumuiya:</li>
                                    <li>Familia:</li>
                                    {{-- <li>Kiongozi:</li> --}}

                                </div>
                                <div class="col-xs-12 col-md-6">
                                    @if($taarifa_jumuiya->count() != 0)
                                    <li>{{$taarifa_jumuiya->jina_la_jumuiya}},</li>
                                    <li>{{$taarifa_jumuiya->jina_la_familia}},</li>
                                    {{-- <li>--,</li> --}}
                                    @else
                                        <li>--,</li>
                                        <li>--,</li>
                                        {{-- <li>--</li> --}}
                                    @endif

                                </div>
                            </div>
                        </ul>

                    </div>
                    <div class="col-xs-12 col-md-6">
                        <h4 class="text-info">Taarifa zinginezo</h4>
                        <div class="separator-solid"></div>

                        <ul style="list-style-type:none;padding:0; margin:0;">
                            <div class="row text-left">
                                <div class="col-xs-12 col-md-6">
                                    <li>Vyama vya kitume:</li>
                                    <li>Chama/Vyama:</li>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <li>{{$vyama_idadi}},</li>
                                    <li>
                                        @foreach ($taarifa_vyama as $row)
                                            {{$row->jina_la_kikundi}},
                                        @endforeach
                                    </li>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>

                <div class="separator-solid"></div>

                <div class="row">
                    <div class="col-md-6">

                        <table class="table table_other">
                            <thead class="bg-light">
                                <th colspan="2">Taarifa za matoleo mwaka ({{Carbon::now()->format('Y')}})</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Zaka</td>
                                    <td><a href="{{route('zaka_kwa_miezi_wanajumuiya', ['jumuiya' => !empty($taarifa_jumuiya)?$taarifa_jumuiya->jina_la_jumuiya:'' , 'mwaka' => Carbon::now()->format('Y')])}}">{{number_format($zaka_mwakahuu)}}</a></td>
                                </tr>

                                <tr>

                                    <td>Michango</td>
                                    <td>{{number_format($michango_mwakahuu)}}</td>
                                </tr>

                                <tr>
                                    <td><b>Jumla</b></td>
                                    <td><b>{{number_format($jumla_mwakahuu)}}</b></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="col-md-6">
                        <table class="table table_other">
                            <thead class="bg-light">
                                <th colspan="2">Taarifa za matoleo mwaka ({{Carbon::now()->subYear(1)->format('Y')}})</th>
                            </thead>
                            <tbody>
                                <tr>

                                    <td>Zaka</td>
                                    <td><a href="{{route('zaka_kwa_miezi_wanajumuiya', ['jumuiya' => !empty($taarifa_jumuiya)?$taarifa_jumuiya->jina_la_jumuiya:'' , 'mwaka' => Carbon::now()->subYear(1)->format('Y')])}}">{{number_format($zaka_mwakajana)}}</a></td>
                                </tr>

                                <tr>
                                    <td>Michango</td>
                                    <td>{{number_format($michango_mwakajana)}}</td>
                                </tr>
                                <tr>
                                    <td><b>Jumla</b></td>
                                    <td><b>{{number_format($jumla_mwakajana)}}</b></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
