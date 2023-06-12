@extends('layouts.master') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- ================== THE HEADER PART =========== --}}
            <div class="card-header">
                <div class="row text-right">
                    <div class="col-md-12">
                        @php $group=request()->input('group'); @endphp @if(is_null($group))
                        <a href="{{request()->fullUrl().'?print=pdf'}}">
                          @else
                        <a href="{{request()->fullUrl().'&print=pdf'}}">
                          @endif<button
                                class="btn btn-info btn-sm mr-2">PDF</button></a>
                        <!-- <a href="{{route('mapato_ya_maendeleo',[$name,'excel',$kuanzia,$ukomo])}}"><button
                                class="btn btn-info btn-sm mr-0">EXCEL</button></a> -->

                    </div>
                </div>

                <div class="col-md-12">
                    @include('layouts.header')
                </div>
            </div>
        </div>

        <div class="card-body">

            <table class="table_other table">

                <thead class="bg-light">

                    <th colspan="2" class="text-info"></th>
                    <th></th>
                </thead>
                <tbody>
                    <th>Mwanajumuiya</th>
                    <th>Kiasi</th>
                    <tfoot>

                        <th>Jumla</th>
                        <th colspan="3"> {{number_format($data->sum('kiasi'),2)}}</th>
                    </tfoot>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{$d->jina_kamili}}</td>
                            <td>{{number_format($d->kiasi,2)}}</td>

                        </tr>

                        @endforeach
                    </tbody>
                </tbody>

            </table>




        </div>
    </div>
</div>


@endsection