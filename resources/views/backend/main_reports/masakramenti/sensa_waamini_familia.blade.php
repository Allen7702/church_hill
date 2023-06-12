@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">

                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-info mr-2">CHAPISHA</button>
                        <a href="{{url()->previous()}}"><button class="btn btn-warning btn-sm">Rudi</button></a>
                    </div>
                </div>
                
                <div class="col-md-12">
                    @include('layouts.header')    
                </div>
                
            </div>

            <div class="card-body">
                <table class="table">
                    
                    <thead class="bg-light">
                        <th>KAYA/FAMILIA</th>
                        <th>JUMUIYA</th>
                        <th>NAMBA YA SIMU</th>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{$data_familia->jina_la_familia}}</td>
                            <td>{{$data_familia->jina_la_jumuiya}}</td>
                            <td>{{$data_familia->mawasiliano}}</td>
                        </tr>
                    </tbody>
                    
                </table>
                A:
                <table class="table">
                    <thead class="bg-light">
                        <th>S/N</th>
                        <th>CHEO</th>
                        <th>JINA</th>
                        <th>HALI YA NDOA</th>
                        <th>DHEHEBU</th>
                        <th>AINA YA NDOA</th>
                    </thead>

                    <tbody>

                        @foreach ($data_wazazi as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->cheo_familia}}</td>
                                <td>{{$item->jina_kamili}}</td>
                                
                                @if($item->ndoa == NULL)
                                <td>--</td>
                                @else
                                <td>{{$item->ndoa}}</td>
                                @endif

                                @if($item->dhehebu == NULL)
                                    <td>--</td>
                                @else
                                    <td>{{$item->dhehebu}}</td>
                                @endif

                                @if($item->aina_ya_ndoa == NULL)
                                <td>--</td>
                                @else
                                <td>{{$item->aina_ya_ndoa}}</td>
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                B: Wanafamilia

                <table class="table w-100">
                    <tr>

                        <tr class="bg-light">
                            <th rowspan="2" style="width:8%;">S/N</th>
                            <th rowspan="2" style="width:25%;">JINA</th>
                            <th rowspan="2" style="width:12%;">RIKA</th>
                            <th colspan="4" class="text-center">SAKRAMENTI</th>
                        </tr>

                        <tr>
                            <th>UBATIZO</th>
                            <th>KOMUNIO</th>
                            <th>KIPAIMARA</th>
                            <th>NDOA</th>
                        </tr>
                    </tr>

                    <tbody>
                        @foreach ($data_wanafamilia as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->jina_kamili}}</td>
                                <td>{{App\MakundiRika::where('umri_kuanzia','<=',Carbon::parse($item->dob)->age)->where('umri_ukomo','>=',Carbon::parse($item->dob)->age)->value('rika')}}</td>
                                <td>{{$item->ubatizo}}</td>
                                <td>{{$item->komunio}}</td>
                                <td>{{$item->kipaimara}}</td>
                                <td>{{$item->ndoa}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
@endsection