@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{strtoupper($title)}}</h4>
            </div>

            <div class="col-md-6 text-right">
                <a href="{{url('zaka_mkupuo')}}"><button class="btn btn-sm btn-info mr-1">Zaka mkupuo</button></a>
                <a href="{{url('zaka')}}"><button class="btn btn-info btn-sm mr-2">Ona zaidi zaka</button></a>
                <a href="{{url('zaka_mwezi_husika_pdf',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('zaka_mwezi_husika_excel',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-2">Excel</button></a>
            </div>

        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">

            <thead class="bg-light">
                <th>S/N</th>
                <th>Jumuiya</th>
                <th>Kiasi</th>
                <th>Nafasi</th>
                <th>Kitendo</th>
            </thead>

            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="3">{{number_format($zaka_mwezi,2)}}</th>
            </tfoot>

            <tbody>
                @foreach ($data_zaka as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jumuiya}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{$loop->index+1}}</td>
                        <td>
{{--                            <a href="{{url('zaka_jumuiya_husika_mwezi',['id'=>$item->jumuiya,'mwezi'=>$mwezi])}}"><button class="btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button></a>--}}
                            <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Vitendo
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('zaka_kwa_miezi_jumuiya',['jumuiya'=> $item->jumuiya, 'mwaka' => \Carbon\Carbon::now()->year] )  }}">malipo ya miezi ya jumuiya</a>
                                <a class="dropdown-item" href="{{ route('zaka_kwa_miezi_wanajumuiya',['jumuiya'=> $item->jumuiya, 'mwaka' => \Carbon\Carbon::now()->year] )  }}">malipo ya miezi ya wanajumuiya</a>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
</div>

<script type="text/javascript">
    $("document").ready(function(){

        //loading datatable
        $('#table').DataTable({
            responsive: true,
            stateSave: true,
        })

    })
</script>
@endsection
