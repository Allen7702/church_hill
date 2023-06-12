@extends('layouts.master') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- ================== THE HEADER PART =========== --}}
            <div class="card-header">
                <div class="row text-right">
                    <div class="col-md-12">
                        <a href="{{route('mapato_ya_maendeleo',[$name,'pdf',$kuanzia,$ukomo])}}"><button
                                class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <a href="{{route('mapato_ya_maendeleo',[$name,'excel',$kuanzia,$ukomo])}}"><button
                                class="btn btn-info btn-sm mr-0">EXCEL</button></a>

                    </div>
                </div>

                <div class="col-md-12">
                    @include('layouts.header')
                </div>
            </div>

            @if($name=='jumla_mapato_maendeleo')
            <div class="card-body">
                @foreach($aina_ya_michango_jumla as $key=>$michango)
                <table class="table_other table">

                    <thead class="bg-light">

                        <th colspan="2" class="text-info">{{ucfirst(strtolower($key))}}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <th>Aina ya Mchango</th>
                        <th>Kiasi</th>
                        <th>Tarehe</th>

                        <tfoot>

                            <th>Jumla</th>
                            <th colspan="3"> <a href="#">{{number_format($michango->sum('kiasi'),2)}}</a></th>
                        </tfoot>
                        <tbody>
                            @foreach($michango as $mchango)
                            <tr>
                                <td>{{$mchango->aina_ya_mchango}}</td>
                                <td>{{number_format($mchango->kiasi,2)}}</td>
                                <td>{{\Carbon\carbon::parse($mchango->tarehe)->format('d/m/Y')}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </tbody>

                </table>
                @endforeach @php $jumla_mapato_maendeleo=0; @endphp @foreach($aina_ya_michango_jumla as $key=>$michango) @php $jumla_mapato_maendeleo +=$michango->sum('kiasi'); @endphp @endforeach

                <table class="table">
                    <thead class="bg-light">
                        <th colspan="3" class="text-info">Jumla ya mapato ya Maendeleo</th>
                        <th style="font-size:18px;">{{number_format($jumla_mapato_maendeleo,2)}}</th>
                    </thead>

                </table>

            </div>

            @elseif($name=='jumla_ya_mapato_yote')
            <div class="card-body">
                <table class="table_other table justify-content-center">

                    <thead class="bg-light">
                        <th colspan="2" class="text-info">A: Sadaka za misa</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Misa</th>
                            <th>Kiasi</th>
                            <th>Tarehe</th>
                        </tr>
                        <tfoot>

                            <th>Jumla</th>
                            <th colspan="3">{{number_format($sadaka_za_misa->sum('kiasi'),2)}}</th>
                        </tfoot>
                        <tbody>
                            @foreach($sadaka_za_misa as $sadaka)
                            <tr>
                                <td>{{$sadaka->aina_za_misa->jina_la_misa}}</td>
                                <td>{{number_format($sadaka->kiasi,2)}}</td>
                                <td>{{\Carbon\carbon::parse($sadaka->tarehe)->format('d/m/Y')}}</td>

                            </tr>

                            @endforeach


                        </tbody>
                    </tbody>
                </table>
                <table class="table_other table">
                    @php $jumla_jumuiya=0; @endphp @foreach($sadaka_za_jumuiya as $sadaka) @php $jumla_jumuiya += $sadaka->kiasi; @endphp @endforeach
                    <thead class="bg-light">
                        <th colspan="2" class="text-info">B: Sadaka za jumuiya</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <th>Jumuiya</th>
                        <th>Kiasi</th>
                        <th>Tarehe</th>

                        <tfoot>

                            <th>Jumla</th>
                            <th colspan="3">{{number_format($jumla_jumuiya,2)}}</th>
                        </tfoot>
                        <tbody>
                            @foreach($sadaka_za_jumuiya as $sadaka)

                            <tr>
                                <td>{{$sadaka->jina_la_jumuiya}}</td>
                                <td>{{number_format($sadaka->kiasi,2)}}</td>
                                <td>{{\Carbon\carbon::parse($sadaka->tarehe)->format('d/m/Y')}}</td>
                            </tr>

                            @endforeach


                        </tbody>
                    </tbody>

                </table>

                <table class="table_other table">
                    @php $jumla_zaka=0; @endphp @foreach($mapato_ya_zaka as $zaka) @php $jumla_zaka += $zaka->kiasi; @endphp @endforeach
                    <thead class="bg-light">
                        <th colspan="2" class="text-info">C: Mapato ya zaka</th>
                        <th></th>
                    </thead>

                    <tbody>
                        <td>S/N</td>
                        <th>Kiasi</th>
                        <th>Mwezi</th>

                        <tfoot>

                            <th>Jumla</th>
                            <th colspan="3">{{number_format($jumla_zaka,2)}}</th>
                        </tfoot>
                        <tbody>
                            @php $count=1; @endphp @foreach($mapato_ya_zaka as $zaka)

                            <tr>
                                <td>{{$count}}</td>
                                <td>{{number_format($zaka->kiasi,2)}}</td>
                                <td>{{ date("F", mktime(0, 0, 0, $zaka->mwezi, 1)) }}</td>
                            </tr>
                            @php $count++; @endphp @endforeach

                        </tbody>
                        <tbody>

                        </tbody>
                    </tbody>

                </table>
                <table class="table">
                    <thead class="bg-light">
                        <th colspan="3" class="text-info">Jumla ya mapato ya kawaida (A+B+C) </th>
                        <th style="font-size:18px;">
                            {{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi'),2)}}</th>
                    </thead>

                </table>


            </div>

            <div class="card-body">
                @foreach($aina_ya_michango_jumla as $key=>$michango)
                <table class="table_other table">

                    <thead class="bg-light">

                        <th colspan="2" class="text-info">{{ucfirst(strtolower($key))}}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <th>Aina ya Mchango</th>
                        <th>Kiasi</th>
                        <th>Tarehe</th>

                        <tfoot>

                            <th>Jumla</th>
                            <th colspan="3"> {{number_format($michango->sum('kiasi'),2)}}</th>
                        </tfoot>
                        <tbody>
                            @foreach($michango as $mchango)

                            <tr>
                                <td>{{$mchango->aina_ya_mchango}}</td>
                                <td>{{number_format($mchango->kiasi,2)}}</td>
                                <td>{{\Carbon\carbon::parse($mchango->tarehe)->format('d/m/Y')}}</td>
                            </tr>

                            @endforeach


                        </tbody>
                    </tbody>

                </table>
                @endforeach @php $jumla_mapato_maendeleo=0; @endphp @foreach($aina_ya_michango_jumla as $key=>$michango) @php $jumla_mapato_maendeleo +=$michango->sum('kiasi'); @endphp @endforeach

                <table class="table">
                    <thead class="bg-light">
                        <th colspan="3" class="text-info">Jumla ya mapato ya Maendeleo</th>
                        <th style="font-size:18px;">{{number_format($jumla_mapato_maendeleo,2)}}</th>
                    </thead>

                </table>

                <table class="table">
                    <thead class="bg-light">
                        <th colspan="3" class="text-info" style="font-weight:bold;font-size:18px;">Jumla ya mapato yote</th>
                        <th style="font-size:21px;">
                            {{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi')+$jumla_mapato_maendeleo,2)}}</th>
                    </thead>

                </table>

            </div>

            @else @php $jumla=0; @endphp @foreach($aina_ya_michango_jumla as $michango) @php $jumla += $michango->kiasi; @endphp @endforeach

            <div class="card-body">
                <table class="table_other table">
                    @php $group=request()->input('group'); $kanda=request()->input('kanda'); $jumuiya=request()->input('jumuiya'); @endphp
                    <thead class="bg-light">
                        <th>Aina ya mchango</th>
                        <th>Kiasi</th>
                        <th>@if(is_null($group)) Tarehe @else Jumuiya @endif</th>
                    </thead>
                    <tfoot>


                        <th>Jumla</th>
                        <th colspan="3"> @if(is_null($group)) @if(!is_null($kanda) || !is_null($jumuiya))
                            <a href="{{request()->fullUrl().'&group=jumuiya_list'}}">
                              @else
                            <a href="{{request()->fullUrl().'?group=jumuiya_list'}}">
                              @endif
                           @else
                           <a href="{{request()->fullUrl()}}">
                            @endif{{number_format($jumla,2)}}</a></th>
                    </tfoot>
                    <tbody>
                        @foreach($aina_ya_michango_jumla as $michango)
                        <tr>
                            <td>{{$michango->aina_ya_mchango}}</td>
                            <td>{{number_format($michango->kiasi,2)}}</td>
                            @php $url = request()->fullUrl(); $url_exploded = explode('/',$url); $mwisho = explode('?',$url_exploded[7])[0]; @endphp
                            <td> @if(is_null($group)) {{\Carbon\carbon::parse($michango->tarehe)->format('d/m/Y')}} @else <a href="{{route('mapato_ya_kawaida_wanajumuiya',[$michango->aina_ya_mchango,'kuanzia'=>$url_exploded[6],'ukomo'=>$mwisho,'jumuiya'=>$michango->jumuiya])}}">{{$michango->jumuiya}}</a>@endif
                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

            @endif

        </div>
    </div>
</div>
@endsection