
@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4 class="text-bold">{{strtoupper($title)}}</h4>
                </div>
                <div class="col-md-6 text-right">
{{--                    <button id="kiongoziBtn" class="btn btn-info btn-sm mr-2">Ongeza </button>--}}
{{--                    <a href="{{url('orodha_viongozi_pdf')}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>--}}
{{--                    <a href="{{url('orodha_viongozi_excel')}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>--}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table w-100" id="table">
                <thead class="bg-light">
                <th>S/N</th>
                <th>Tukio</th>
                <th>Msababishaji</th>
                <th>Kisababisha</th>
                <th>Imerekodiwa</th>
                <th>Taarifa</th>
                </thead>
                <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$activity->description}}</td>
                        <td>{{$activity->causer->jina_kamili}}</td>
                        <td>{{str_replace('App\\','', $activity->subject_type)}}</td>
                        <td>{{$activity->created_at->diffForHumans()}}</td>
                        <td><a data-toggle="modal" data-properties="{{ $activity->properties }}" title="Onyesha kilichomo Taarifa" class="open-detailed-view" href="#activityModal">Onyesha</a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div id="activityModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="my-modal-title">Taarifa ya tukio </h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre class="data-properties">

                    </pre>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        $("document").ready(function(){

            $(document).on("click", ".open-detailed-view", function () {
                var properties = $(this).data('properties');
                // JSON.parse(properties);
                $(".data-properties").html(JSON.stringify(properties, null, 4))

                // As pointed out in comments,
                // it is unnecessary to have to manually call the modal.
                $('#activityModal').modal('show');
            });

            //loading datatable
            $('#table').DataTable({
                responsive: true,
                stateSave: true,
            })

        })
    </script>

@endsection
