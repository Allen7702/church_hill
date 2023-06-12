@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">{{$title}}</h4>
            </div>

            <div class="col-md-6 text-right">
                <a href="{{url('sadaka_kuu')}}"><button class="btn btn-info btn-sm mr-2">Weka sadaka</button></a>
                <a href="{{url('sadaka_kuu_mwezi_pdf',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                <a href="{{url('sadaka_kuu_mwezi_excel',['id'=>$mwezi])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
            </div>

        </div>
    </div>
    <div class="card-body">
        <table class="table w-100" id="table">
            
            <thead class="bg-light">
                <th>S/N</th>
                <th>Kichwa</th>
                <th>Kiasi</th>
                <th>Tarehe</th>
                <th>Imewekwa na</th>
            </thead>

            <tfoot>
                <th colspan="2">Jumla</th>
                <th colspan="3">{{number_format($sadaka_mwezi,2)}}</th>
            </tfoot>
            
            <tbody>
                @foreach ($data_sadaka as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->misa}}</td>
                        <td>{{number_format($item->kiasi,2)}}</td>
                        <td>{{Carbon::parse($item->tarehe)->format('d-M-Y')}}</td>
                        <td>{{$item->imewekwa}}</td>
                        {{-- <td>
                            <button class="view btn btn-sm btn-info mr-1" id="{{$item->id}}">Angalia</button>
                            <button class="edit btn btn-sm btn-warning mr-1" id="{{$item->id}}">Badili</button>
                            <button class="delete btn btn-sm bg-light text-danger" id="{{$item->id}}"><i class="fa fa-trash fa-fw"></i></button>
                        </td> --}}
                    </tr>
                @endforeach
            
            </tbody>
            
        </table>
        
    </div>
</div>

<div id="sadakaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="sadakaForm">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Aina ya misa:</label>
                        <select name="misa" id="misa" class="form-control" required>
                            <option value="">Chagua misa</option>
                            @foreach ($aina_za_misa as $item)
                                <option value="{{$item->jina_la_misa}}">{{$item->jina_la_misa}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Kiasi cha sadaka:</label>
                        <input type="number" min="0.0" step="any" id="kiasi" name="kiasi" class="form-control" placeholder="Jaza kiasi" required>
                    </div>

                    <div class="form-group">
                        <label for="">Tarehe:</label>
                        <input type="date" class="form-control" id="tarehe" name="tarehe" placeholder="chagua tarehe" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                </div>

            </form>
            
        </div>
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