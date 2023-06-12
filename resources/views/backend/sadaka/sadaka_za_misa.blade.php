@extends('layouts.master')

@section('content')

    <div class="card">

        <div class="card-header">
            <div class="row">

                <div class="col-md-4 mt-2">
                    <h3>{{strtoupper($title)}}</h3>
                </div>

                <div class="col-md-8">

                    <form class="form-inline" name="initial_form" id="initialForm" method="GET" action="{{route('sadaka_za_misas.index')}}">

                        @csrf
                        <div class="form-group">
                            <select name="sadaka" id="sadaka" class="form-control select2 select2-search--inline" required>
                                <option value="" disabled>Chagua aina ya sadaka</option>
                                @foreach (\App\AinaZaSadaka::all(['id','jina_la_sadaka']) as $item)

                                    <option value="{{$item->id}}" @if(!empty($sadaka) && $item->id == $sadaka) selected @endif >{{$item->jina_la_sadaka}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="misa" id="misa" class="form-control select2 select2-search--inline" required>
                                <option value="" disabled>Chagua aina ya misa</option>
                                @foreach (\App\AinaZaMisa::all(['id', 'jina_la_misa']) as $item)

                                    <option value="{{$item->id}}" @if(!empty($misa) && $item->id == $misa) selected @endif >{{$item->jina_la_misa}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <select name="year" id="year" class="form-control select2 select2-search--inline" required>
                                <option value="" disabled>Chagua mwaka</option>
                                @foreach (array_combine(range(date('Y'),2000),range(date('Y'),2000)) as $item)

                                    <option value="{{$item}}" @if( !empty($year) && $item==$year) selected @endif >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                </div>
            </div>

        </div>
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">

                </div>
                <div class="col-md-6 text-right">
                    <button id="addBtn" class="btn btn-info btn-sm mr-2">Ongeza sadaka za misa</button>
                    <a href="{{route('sadaka_za_misas.download_pdf', ['type' => $type , 'year'=> $year , 'sadaka' => $sadaka , 'misa'=> $misa,  ])}}"><button class="btn btn-info btn-sm mr-2">PDF</button></a>
                    <a href="{{route('sadaka_za_misas.download_excel', ['type' => $type , 'year'=> $year, 'sadaka' => $sadaka , 'misa'=> $misa ])}}"><button class="btn btn-info btn-sm mr-0">Excel</button></a>
                </div>
            </div>
        </div>
        <div class="card-body">

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{session('status')}}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @foreach ($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>
            @endif

            @if(session()->has('failures'))
                <div class="py-4">
                    <table class="table w-100 table-striped">
                        <thead class="bg-warning">
                        <tr>
                            <th>Row</th>
                            <th>Attribute</th>
                            <th>Errors</th>
                            <th>Value</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach (session()->get('failures') as $validation)
                            <tr>
                                <td>{{$validation->row()}}</td>
                                <td>{{$validation->attribute()}}</td>
                                <td>
                                    <ul>
                                        @foreach ($validation->errors() as $e)
                                            <li>{{$e}}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    {{$validation->values()[$validation->attribute()]}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

            <table class="table w-100" id="table">
                <thead class="bg-light">
                <th>S/N</th>
                <th>Aina ya Sadaka</th>
                <th>Aina ya Misa</th>
                <th>Maelezo</th>
                <th>Ilifanyika</th>
                <th>Eneo</th>
                <th>Kiasi</th>
                <th>Kitendo</th>
                </thead>
                <tbody>
                @foreach ($sadaka_za_misa as $item)

                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->aina_za_sadaka->jina_la_sadaka}}</td>

                        <td>{{$item->aina_za_misa->jina_la_misa}}</td>

                        @if($item->description == "")
                            <td>--</td>
                        @else
                            <td>{{$item->description}}</td>
                        @endif
                        <td>{{$item->ilifanyika}}</td>
                        <td>{{str_replace('App\\', '', $item->misaable_type)== 'CentreDetail'? 'Parokia': str_replace('App\\', '', $sadaka->misaable_type)}}</td>
                        <td>{{$item->kiasi}}</td>

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Vitendo
                                </button>
                                <div class="dropdown-menu">
                                    <a class="edit dropdown-item text-info" data-id="{{$item->id}}"
                                       data-aina_ya_sadaka="{{$item->aina_za_sadaka->id}}"
                                       data-aina_ya_misa="{{$item->aina_za_misa->id}}"
                                       data-description="{{$item->description}}"
                                       data-ilifanyika="{{$item->ilifanyika}}"
                                       data-eneo="{{strtolower(str_replace('App\\', '', $item->misaable_type))}}"
                                       data-misaable="{{$item->misaable_id}}"
                                       data-kiasi="{{$item->kiasi}}"
                                       href="#"><i class="fa fa-edit fa-fw"></i> badilisha</a>
                                    <a class="delete dropdown-item text-danger" data-id="{{$item->id}}" href="#"><i class="fa fa-trash fa-fw"></i> futa</a>
                                    <form id="delete-form-{{$item->id}}" action="" method="POST" style="display: none;">
                                        <input hidden type="text" name="_method" value="DELETE"/>
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        </div>

    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="my-modal-title">Ongeza sadaka kwenye misa </h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-white"></i></span>
                    </button>
                </div>
                <form id="mafundisho-add-Form" action="{{route('sadaka_za_misas.store')}}"  method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Chagua aina ya sadaka:</label>
                            <select name="sadaka" id="filter_sadaka" class="form-control select2 " required>
                                <option value="" disabled>Chagua aina ya sadaka</option>
                                @foreach (\App\AinaZaSadaka::all(['id','jina_la_sadaka']) as $item)

                                    <option value="{{$item->id}}" @if(!empty($sadaka) && $item->id ==$sadaka) selected @endif >{{$item->jina_la_sadaka}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Chagua aina ya misa:</label>
                            <select name="misa" id="filter_misa" class="form-control select2 " required>
                                <option value="" disabled>Chagua aina ya misa</option>
                                @foreach (\App\AinaZaMisa::all(['id', 'jina_la_misa']) as $item)

                                    <option value="{{$item->id}}" @if(!empty($misa) && $item->id == $misa) selected @endif >{{$item->jina_la_misa}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Ilifanyika:</label>
                            <input class="form-control" type="date" id="ilifanyika" name="ilifanyika" placeholder="Tarehe waliyoanza" required>
                        </div>
                        <div class="form-group">
                            <label for="">Kiasi:</label>
                            <input class="form-control" type="number" id="kiasi" name="kiasi" placeholder="Kiasi"/>
                        </div>
                        <div class="form-group" id="eneo-div">
                            <label for="">Eneo:</label>
                            <select name="eneo" id="eneo" class="form-control select2">
                                <option value="" disabled selected>Chagua Eneno</option>
                                <option value="parokia" >Parokia</option>
                                <option value="jumuiya" >Jumuiya</option>
                                <option value="kanda" >Kanda</option>
                            </select>
                        </div>

                        <div class="form-group" id="jumuiya-div" style="display: none">
                            <label for="">Chagua jumuiya:</label>
                            <select name="jumuiya" id="jumuiya" class="form-control select2 " required>
                                <option value="" disabled>Chagua jumuiya</option>
                                @foreach (\App\Jumuiya::all(['id', 'jina_la_jumuiya']) as $item)

                                    <option value="{{$item->id}}" >{{$item->jina_la_jumuiya}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group " id="kanda-div" style="display: none">
                            <label for="">Chagua kanda:</label>
                            <select name="kanda" id="kanda" class="form-control select2 " required>
                                <option value="" disabled>Chagua kanda</option>
                                @foreach (\App\Kanda::all(['id', 'jina_la_kanda']) as $item)

                                    <option value="{{$item->id}}" >{{$item->jina_la_kanda}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Maelezo:</label>
                            <textarea name="description" class="form-control" id="description" placeholder="Maelezo" rows="2"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Funga</button>
                        <input type="hidden" name="action" id="action-method" value="">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                    </div>
                </form>

        </div>
    </div>
    </div>


    <script>
        $("document").ready(function(){
            //turning on select2
            $('#jumuiya').select2({
                theme: 'bootstrap4',
                placeholder: 'tafuta jumuiya..',
                allowClear: true,
                // width: '100%',
            });
            $('#eneo').on('change', function (){
                filter_eneo = $('#eneo').val()

                if(filter_eneo == 'jumuiya') {
                    $('#kanda-div').css('display', 'none')
                    $('#jumuiya-div').css('display', 'block')
                }

                if (filter_eneo == 'parokia') {
                    $('#kanda-div').css('display', 'none')
                    $('#jumuiya-div').css('display', 'none')
                }


                if(filter_eneo == 'kanda') {
                    $('#jumuiya-div').css('display', 'none')
                    $('#kanda-div').css('display', 'block')
                }
            })

            $('#initialForm').on('change', function(){
                filter_sadaka = $('#sadaka').val()
                filter_misa = $('#misa').val()
                filter_year = $('#year').val()
                url = window.location.origin
                window.location.replace( url+'/sadaka_za_misas/index'+ window.location.search.split('&')[0] +'&year='+ filter_year+'&sadaka='+filter_sadaka+'&misa='+ filter_misa);
                // $('#initialForm').submit();
                // $(this).closest('form').submit();
            });

            //handling the submission of zaka form
            function ajaxCall1(){
                setTimeout(() => {
                    $('#zakaForm').trigger('submit');
                },4500);
            }

            //loading datatable
            $('#table').DataTable({
                responsive: true,
                stateSave: true,
            })

            $('#studentBtn').on('click', function (){
                $('#upload_student_form')[0].reset();
                $('#studentModal').modal({backdrop: 'static',keyboard: false});
                $('#studentModal').modal('show');
            })

            $('#addBtn').on('click', function (){
                $('#addModal').modal({backdrop: 'static',keyboard: false});
                $('#addModal').modal('show');
            })

            //editing enrollments details
            $(document).on('click','.edit', function(event){
                event.preventDefault();

                //getting the id from button
                let update_id = $(this).data('id');
                let aina_ya_sadaka = $(this).data('aina_ya_sadaka');
                let aina_ya_misa = $(this).data('aina_ya_misa');
                let description = $(this).data('description');
                let ilifanyika = $(this).data('ilifanyika');
                let eneo = $(this).data('eneo');
                let kiasi = $(this).data('kiasi');
                let misaable = $(this).data('misaable');
                let url = window.location.origin + "/sadaka_za_misas/"+ update_id +"/update"

                if(eneo == 'jumuiya') {
                    $('#kanda-div').css('display', 'none')
                    $('#jumuiya-div').css('display', 'block')
                }

                if(eneo == 'kanda') {
                    $('#jumuiya-div').css('display', 'none')
                    $('#kanda-div').css('display', 'block')
                }

                $('#filter_sadaka').val(aina_ya_sadaka);
                $('#filter_misa').val(aina_ya_misa);
                $('#description').val(description);
                $('#ilifanyika').val(ilifanyika);
                $('#eneo').val(eneo);
                $('#kiasi').val(kiasi);
                $('#jumuiya').val(misaable);
                $('#kanda').val(misaable);
                $('.modal-title').text('Badilisha Sadaka ya misa');
                $('#addModal').modal('show');
                $('#mafundisho-add-Form').attr('action',url)
                $('#action-method').attr('name','_method')
                $('#action-method').attr('value','PUT')

            })

            $('#addModal').on('hidden.bs.modal', function (){
                location.reload()
            });

            //deleting function
            $(document).on('click', '.delete', function (event) {
                event.preventDefault();

                //getting the id
                var delete_id = $(this).data('id');
                let url = window.location.origin + "/sadaka_za_misas/"+ delete_id +"/delete"
                //getting user confirmation
                Swal.fire({
                    title: "Una uhakika?",
                    text: "Unakaribia kufuta taarifa!",
                    icon: 'warning',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ndio, futa!',
                    cancelButtonText: 'Hapana, tunza!',
                    allowOutsideClick: false,

                }).then((result) => {
                    if (result.value) {
                        $('#delete-form-'+ delete_id).attr('action',url)
                        $('#delete-form-'+ delete_id).submit()

                    }

                    //means user has choose to cancel the action
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        Toast.fire({
                            icon: 'info',
                            title: "Umesitisha!",
                        })
                    }
                })

            })


        })

    </script>
@endsection
