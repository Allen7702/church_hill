@extends('layouts.master') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- ================== THE HEADER PART =========== --}}
            <div class="card-header">
                <div class="row text-right">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">

                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary">
                                        {{$title_kanda}}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown </span>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['kanda'=>'all']) }}">{{$sahihisha_kanda->name}}</a
                                        >
                                        @foreach($kandas as $kanda)
                                        <a
                                            class="dropdown-item"
                                            href="{{ request()->fullUrlWithQuery(['kanda'=>$kanda->id]) }}"
                                            >{{$kanda->jina_la_kanda}}</a
                                        >
                                        @endforeach
                                            
                            </div>
                                
                            </div>
                                
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary">
                                         {{$title_jumuiya}}
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                    
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a
                                                class="dropdown-item"
                                                href="{{ request()->fullUrlWithQuery(['jumuiya'=>'all']) }}"
                                                >{{$title_jumuiya}}</a
                                            >
                                            @foreach($jumuiyas as $jumuiya)
                                            <a
                                                class="dropdown-item"
                                                href="{{ request()->fullUrlWithQuery(['jumuiya'=>$jumuiya->id]) }}"
                                                >{{$jumuiya->jina_la_jumuiya}}</a
                                            >
                                            @endforeach
                                                
                                </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ URL::to('mapato_ya_kawaida_print/pdf/' . $name.'/'.$kuanzia.'/'.$ukomo) }}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                                        <!-- <a href="{{ URL::to('mapato_ya_kawaida_print/excel/' . $name.'/'.$kuanzia.'/'.$ukomo) }}"><button class="btn btn-info btn-sm mr-0">EXCEL</button></a> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            @include('layouts.header')
                        </div>
                    </div>
                    @if($name=='sadaka_za_misa')

                    <div class="card-body">
                        <table class="table_other table">

                            <thead class="bg-light">
                                <th>Misa</th>
                                <th>Kiasi</th>
                                <th>Tarehe</th>
                            </thead>
                            <tfoot>
                                <th>Jumla</th>
                                <th colspan="3">{{number_format($sadaka_za_misa->sum('kiasi'),2)}}</th>
                            </tfoot>
                            <tbody>
                                @foreach($sadaka_za_misa as $sadaka)
                                <tr>
                                    <td>{{$sadaka->aina_za_misa->jina_la_misa}}</td>
                                    <td>{{number_format($sadaka->kiasi,2)}}</td>
                                    <td>{{\Carbon\carbon::parse($sadaka->ilifanyika)->format('d/m/Y')}}</td>

                                </tr>

                                @endforeach


                            </tbody>

                        </table>
                    </div>
                    @endif @if($name=='sadaka_za_jumuiya') @php $jumla=0; @endphp @foreach($sadaka_za_jumuiya as $sadaka) @php $jumla += $sadaka->kiasi; @endphp @endforeach

                    <div class="card-body">
                        <table class="table_other table">

                            <thead class="bg-light">
                                <th>Jumuiya</th>
                                <th>Kiasi</th>
                                <th>Tarehe</th>
                            </thead>
                            <tfoot>
                                <th>Jumla</th>
                                <th colspan="3">{{number_format($jumla,2)}}</th>
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

                        </table>
                    </div>
                    @endif @if($name=='mapato_ya_zaka') @php $jumla=0; @endphp @foreach($mapato_ya_zaka as $zaka) @php $jumla += $zaka->kiasi; @endphp @endforeach

                    <div class="card-body">
                        <table class="table_other table">
                            @php $group=request()->input('group'); $kanda=request()->input('kanda'); $jumuiya=request()->input('jumuiya'); @endphp
                            <thead class="bg-light">
                                <td>S/N</td>
                                <th>Kiasi</th>
                                <th>@if(is_null($group)) Mwezi @else Jumuiya @endif</th>
                            </thead>
                            <tfoot>
                                <th>Jumla</th>

                                <th colspan="3">
                                    @if(is_null($group)) @if(!is_null($kanda) || !is_null($jumuiya))
                                    <a href="{{request()->fullUrl().'&group=jumuiya_list'}}">
                                      @else
                                    <a href="{{request()->fullUrl().'?group=jumuiya_list'}}">
                                      @endif
                                   @else
                                   <a href="{{request()->fullUrl()}}">
                                    @endif
                                         {{number_format($jumla,2)}}
                                    </a>
                                </th>
                            </tfoot>
                            <tbody>
                                @php $count=1; @endphp @foreach($mapato_ya_zaka as $zaka)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{number_format($zaka->kiasi,2)}}</td>
                                    @php $url = request()->fullUrl(); $url_exploded = explode('/',$url); $mwisho = explode('?',$url_exploded[6])[0]; @endphp
                                    <td> @if(is_null($group)) {{ date("F", mktime(0, 0, 0, $zaka->mwezi, 1)) }} @else <a href="{{route('mapato_ya_kawaida_wanajumuiya',['mapato_ya_zaka_wanajumuiya','kuanzia'=>$url_exploded[5],'ukomo'=>$mwisho,'jumuiya'=>$zaka->jumuiya])}}">{{$zaka->jumuiya}}</a>@endif
                                    </td>
                                </tr>
                                @php $count++; @endphp @endforeach

                            </tbody>

                        </table>
                    </div>
                    @endif @if($name=='jumla_mapato_ya_kawaida')
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
                                        <td>{{\Carbon\carbon::parse($sadaka->ilifanyika)->format('d/m/Y')}}</td>
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
                                <th style="font-size:20px;">{{number_format($jumla_zaka+$jumla_jumuiya+$sadaka_za_misa->sum('kiasi'),2)}}</th>
                            </thead>

                        </table>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endsection