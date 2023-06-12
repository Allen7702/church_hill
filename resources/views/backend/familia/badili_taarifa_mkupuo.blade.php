@extends('layouts.master')

@section('content')

<div class="card">

    <div class="card-header">
        <div class="row">

            <div class="col-md-6 mt-2">
                <h3>{{strtoupper($title)}}</h3>
            </div>

            <div class="col-md-6">

                <form name="initial_form" id="initialForm" method="POST" action="{{route('badilisha_taarifa_mkupuo.data')}}">
                    @csrf
                    <div class="form-group">
                        <select name="jumuiya" id="jumuiya" class="form-control select2" required>
                            <option value="">Chagua jumuiya</option>
                            @foreach ($jumuiya as $item)
                                <option value="{{$item->jina_la_jumuiya}}">{{$item->jina_la_jumuiya}}-{{$item->jina_la_kanda}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <div class="card-body">

        <div class="card-body" style="overflow: scroll">
            <table class="table w-100 table-striped" id="editable-datatable">

                <thead>
                <th></th>
                <th>S/N</th>
                <th>Jina kamili</th>
                <th>Mawasiliano</th>
                <th>Ndoa</th>
                <th>Jinsia</th>
                <th>Ubatizo</th>
                <th>Ekaristi</th>
                <th>Kipaimara</th>
                <th>Komunio</th>
                <th>Ndoa</th>
                <th>Date of Birth</th>
                <th>Namba ya Cheti</th>
                <th>Jimbo la Ubatizo</th>
                <th>Parokia ya Ubatizo</th>
                <th>Namba ya utambulisho</th>
                <th>Cheo cha Familia</th>
                </thead>

                <tbody>
                @foreach ($members as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$item->jina_kamili}}</td>
                        <td>{{$item->mawasiliano}}</td>
                        <td>{{$item->familia}}</td>
                        <td>{{$item->ndoa}}</td>
                        <td>{{$item->jinsia}}</td>
                        <td>{{$item->ubatizo}}</td>
                        <td>{{$item->ekaristi}}</td>
                        <td>{{$item->kipaimara}}</td>
                        <td>{{$item->komunio}}</td>
                        <td>{{$item->aina_ya_ndoa}}</td>
                        <td>{{$item->taaluma}}</td>
                        <td>{{$item->dob}}</td>
                        <td>{{$item->namba_ya_cheti}}</td>
                        <td>{{$item->jimbo_la_ubatizo}}</td>
                        <td>{{$item->parokia_ya_ubatizo}}</td>
                        <td>{{$item->namba_utambulisho}}</td>
                        <td>{{$item->cheo_familia}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
    $("document").ready(function(){

        //declaring the value of zaka total
        var zaka_total_overall = 0;
        var denomination_total_overall = 0;

        //reseting the value of select two
        $('#jumuiya').val('').trigger('change');
        $('#zakaForm')[0].reset();

        if($('#fields').val()== 'text_jina') {
            $('#field-change').html(

            )
        }

        //turning on select2
        $('#jumuiya').select2({
            theme: 'bootstrap4',
            placeholder: 'tafuta jumuiya..',
            allowClear: true,
            // width: '100%',
        });


        $('#initialForm,select').on('change', function(){
            $('#initialForm').submit();
            // $(this).closest('form').submit();
        });

        //handling all the input of zaka


        //function to handle the submit click
        //when user clicks the
        $('#denoBtn').on('click',function(){

            //user skipped filling the date
            if($('#tarehe').val() == ""){
                Toast.fire({
                    title: "Tafadhali jaza tarehe kwa majitoleo husika..",
                    icon: "info",
                });

                return false;
            }

            if(zaka_total_overall != denomination_total_overall){

                Swal.fire({
                    title: "<b class='text-primary'>Taarifa!</b>",
                    icon: "info",
                    text: "Tafadhali hakiki tena kiasi kilichojazwa kwakua hakiendani na idadi ya noti na sarafu zilizojazwa..",
                    timer: 8000,
                    showConfirmButton: false,
                    showCancelButton: false,
                })
                return false;
            }

            //we are now correct
            else{

                //getting the value of aina ya michango to be in hidden aina
                var aina_data = "Zaka";
                var tarehe_data = $('#tarehe').val();

                //assigning aina ya toleo
                $('#hidden_aina').val(aina_data);

                //assigning tarehe husika
                $('#hidden_date').val(tarehe_data);

                //handling the submission of denomination form
                $('#denominationForm').submit(function(event){
                    event.preventDefault();

                    var url_data = "{{route('denomination.store')}}";

                    $.ajax({
                        url: url_data,
                        method: "POST",
                        dataType: "JSON",
                        data: $(this).serialize(),
                        success: function(data){

                            //if we have a successfully request
                            if(data.success){

                                $('#denominationModal').modal('hide');

                                var message = data.success;

                                Toast.fire({
                                    title: message,
                                    icon: "success",
                                })

                                ajaxCall1();
                            }

                            //if we have a wrong request
                            if(data.errors){
                                var message = data.errors;
                                Toast.fire({
                                    title: message,
                                    icon: "info",
                                })
                            }
                        }
                    })
                })
            }
        })
    })
</script>

<script>

    var editor;

    $(document).ready(function() {
        editor = new $.fn.dataTable.Editor( {
            table: "#editable-datatable",
            fields: [ {
                label: "Jina Kamili:",
                name: "jina_kamili"
            }, {
                label: "Mawasiliano:",
                name: "mawasiliano"
            }, {
                label: "Familia:",
                name: "familia"
            }, {
                label: "Ondoa:",
                name: "ndoa"
            }, {
                label: "Jinsia:",
                name: "jinsia"
            }, {
                label: "Ubatizo:",
                name: "ubatizo",
                type: "datetime"
            }, {
                label: "Ekaristi:",
                name: "ekaristi"
            } , {
                label: "Kipaimara:",
                name: "kipaimara"
            }, {
                label: "Komunio:",
                name: "komunio"
            },{
                label: "Aina ya ndoa:",
                name: "aina_ya_ndoa"
            }, {
                label: "Taaluma:",
                name: "taaluma"
            }, {
                label: "Tarehe ya kuzaliwa:",
                name: "dob",
                type: "datetime"
            }, {
                label: "Salary:",
                name: "namba_ya_cheti"
            }, {
                label: "Jimbo la ubatizo:",
                name: "jimbo_la_ubatizo"
            }, {
                label: "Parokia ya ubatizo:",
                name: "parokia_ya_ubatizo"
            }, {
                label: "Namba ya utambulisho:",
                name: "namba_utambulisho"
            }, {
                label: "Cheo kwenye Familia:",
                name: "cheo_familia"
            }
            ]
        } );

        // Activate an inline edit on click of a table cell
        $('#editable-datatable').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this );
        } );

        $('#editable-datatable').DataTable( {

            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                { extend: "create", editor: editor },
                { extend: "edit",   editor: editor },
                { extend: "remove", editor: editor }
            ]
        } );
    } );
//handling the submission of zaka form
function ajaxCall1(){
    setTimeout(() => {
        $('#zakaForm').trigger('submit');
    },4500);
}

</script>
@endsection
