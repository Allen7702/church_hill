
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
                <th>Mwanajumuiya</th>
                <th>Jumla ya Kiasi</th>
                <th> Ilihifadhiwa</th>
                </thead>
                <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$activity->jina_la_mwanajumuiya}}</td>
                        <td>{{ $activity->kiasi  }}</td>
                        <td>{{$activity->created_at->diffForHumans()}}</td>


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
                    <div class="data-properties">
                        <table class="table w-100" >
                            <thead class="bg-light">
                            <th>S/N</th>
                            <th>KIASI</th>
                            <th>ANIA YA KIASI</th>
                            <th>NAMBA YA KIASI</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>10000</td>
                                    <td>NOTI</td>
                                    <td id="10000"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>5000</td>
                                    <td>NOTI</td>
                                    <td id="5000"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>2000</td>
                                    <td>NOTI</td>
                                    <td id="2000"></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>1000</td>
                                    <td>NOTI</td>
                                    <td id="1000"></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>500</td>
                                    <td>NOTI</td>
                                    <td id="500"></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>500</td>
                                    <td>SARAFU</td>
                                    <td id="S500"></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>200</td>
                                    <td>SARAFU</td>
                                    <td id="S200"></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>100</td>
                                    <td>SARAFU</td>
                                    <td id="S100"></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>50</td>
                                    <td>SARAFU</td>
                                    <td id="S50"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                $("#10000").html($(this).data('noti10000'))
                $("#5000").html($(this).data('noti5000'))
                $("#2000").html($(this).data('noti2000'))
                $("#1000").html($(this).data('noti1000'))
                $("#500").html($(this).data('noti500'))
                $("#S500").html($(this).data('sarafu500'))
                $("#S200").html($(this).data('sarafu200'))
                $("#S100").html($(this).data('sarafu100'))
                $("#S50").html($(this).data('sarafu50'))

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
