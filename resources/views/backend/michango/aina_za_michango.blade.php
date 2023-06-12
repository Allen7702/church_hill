@extends('layouts.master') @section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-bold">AINA ZA MICHANGO</h4>
            </div>
            <div class="col-md-6 text-right">
                <button id="ainaBtn" class="btn btn-info btn-sm mr-2">
                    Ongeza aina
                </button>
                <button class="btn btn-info btn-sm mr-2">PDF</button>
                <button class="btn btn-info btn-sm mr-0">Excel</button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Aina ya mchango</th>
                <th>Ahadi</th>
                <th>Maelezo</th>
                <th>Imeundwa</th>
                <th>Kitendo</th>
            </thead>

            <tbody>
                @foreach ($data_michango as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->aina_ya_mchango}}</td>
                    <td>
                    @if(!is_null($item->ahadi_ya_aina_za_mchango))
                    
                    <span class="badge badge-success">Ndio </span>
                    @else
                    <span class="badge badge-light">Hapana</span>
                    @endif
                    </td>
                    <td>{{Str::limit($item->maelezo,'18','..')}}</td>
                    <td>
                        {{Carbon::parse($item->created_at)->format('d-M-Y')}}
                    </td>
                    <td>
                        
                        @if(!is_null($item->ahadi_ya_aina_za_mchango))
                        <button
                            class="btn btn-sm btn-info mr-1"
                         
                        > 
                        <a href="{{route('ahadi_aina_za_mchango',$item->id)}}" style="color:white;">Gawia jumuiya</a>
                        </button>
                        @endif
                        
                        <button
                            class="edit btn btn-sm btn-warning mr-1"
                            id="{{$item->id}}"
                        >
                            Badili
                        </button>
                        <button
                            class="delete btn btn-sm bg-light text-danger"
                            id="{{$item->id}}"
                        >
                            <i class="fa fa-trash fa-fw"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div
    id="ainaModal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="my-modal-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"
                        ><i class="fas fa-fw fa-times-circle text-info"></i
                    ></span>
                </button>
            </div>

            <form id="ainaForm">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="aina_ya_mchango">Aina ya mchango:</label>
                        <input
                            class="form-control"
                            type="text"
                            id="aina_ya_mchango"
                            name="aina_ya_mchango"
                        />
                    </div>
                    <div class="form-group">
                        <label>Mchango una ahadi: </label>
                        <br />
                        <di class="col-md-6" style="margin-left:5px;">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="ahadi_checkbox"
                                name="ahadi"
                                value="ahadi"
                            />
                        </di>
                    </div>
                    <div
                        class="form-group awamu_div"
                        style="display:none"
                    >
                    <div class="row">
                    <div class="col-md-6">
                        <label>Kiasi:</label>
                        <input
                            class="form-control"
                            type="text"
                            id="kiasi"
                            name="kiasi"
                         
                        />
                        </div>
                        <div class="col-md-6">
                        <label>Awamu:</label>
                        <input
                            class="form-control"
                            type="number"
                            id="awamu"
                            name="awamu"
                            min="1"
                        />
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Tarehe ya mwisho ya mchango:</label>
                                <input type="date" name="tarehe" class="form-control" id="tarehe">
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="">Maelezo:</label>
                        <textarea
                            name="maelezo"
                            class="form-control"
                            id="maelezo"
                            placeholder="Maelezo kuhusu mchango"
                            rows="2"
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-warning btn-sm"
                        data-dismiss="modal"
                    >
                        Funga
                    </button>
                    <input type="hidden" name="action" id="action" value="" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <button
                        type="submit"
                        class="btn btn-info btn-sm"
                        id="submitBtn"
                    >
                        Wasilisha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("document").ready(function() {


        $("#kiasi").on('keyup', function (evt) {

if ($('#kiasi').val() == "") {
} else {
    if (evt.which != 110) {//not a fullstop
        var n = parseFloat($(this).val().replace(/\,/g, ''), 10);
        $(this).val(n.toLocaleString());
    }
}
});

        $('input[type="checkbox"]').click(function() {

            if ($(this).prop("checked") == true) {
                //show content
                $("#awamu").prop("required", true);
                $("#kiasi").prop("required", true);
                $(".awamu_div").css("display", "block");
            } else if ($(this).prop("checked") == false) {
                // empty content and remove content
                $("#awamu").prop("required", false);
                $("#awamu").val("");
                $("#kiasi").val("");
                $("#tarehe").val("");

                $(".awamu_div").css("display", "none");
            }
        });

        //loading datatable
        $("#table").DataTable({
            responsive: true,
            stateSave: true
        });

        //turning on the modal
        $("#ainaBtn").on("click", function() {
            $("#ainaForm")[0].reset();
            $(".modal-title").text("Ongeza aina ya mchango");
            $("#action").val("Generate");
            $("#submitBtn").attr("disabled", false);
            $("#submitBtn").html("Wasilisha");
            $("#submitBtn").show();
            $("#ainaModal").modal({ backdrop: "static", keyboard: false });
            $("#ainaModal").modal("show");
        });

        //submitting values
        $("#ainaForm").on("submit", function(event) {
            event.preventDefault();

            $("#submitBtn").attr("disabled", true);

            var data_url = "";

            //setting the route to hit
            if ($("#action").val() == "Generate")
                var data_url = "{{route('aina_za_michango.store')}}";

            if ($("#action").val() == "Update")
                var data_url = "{{route('aina_za_michango.update')}}";

            //submitting the values
            $.ajax({
                url: data_url,
                method: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(data) {
                    if (data.success) {
                        //preventing resubmission of data
                        $("#submitBtn").attr("disabled", true);

                        //hiding modal
                        $("#ainaModal").modal("hide");

                        //reseting the form
                        $("#ainaForm")[0].reset();

                        //grabbing message from controller
                        var message = data.success;

                        //toasting the message
                        Toast.fire({
                            icon: "success",
                            title: message
                        });

                        //refreshing the page
                        setTimeout(function() {
                            window.location.reload();
                        }, 4500);
                    }

                    //if we have error show this
                    if (data.errors) {
                        //preventing resubmission of data
                        $("#submitBtn").attr("disabled", false);

                        var message = data.errors;
                        Toast.fire({
                            icon: "info",
                            title: message
                        });
                    }
                }
            });
        });

        //viewing aina za misa  details
        $(document).on("click", ".view", function(event) {
            event.preventDefault();

            //getting the id from button
            var id = $(this).attr("id");

            //getting data from url
            $.ajax({
                url: "/aina_za_michango/" + id + "/edit",
                dataType: "JSON",
                success: function(data) {
                
                    $("#submitBtn").hide();
                    $("#hidden_id").val(id);
                    $("#aina_ya_mchango").val(data.result.aina_ya_mchango);
                    $("#maelezo").val(data.result.maelezo);
                    $(".modal-title").text("Angalia taarifa za mchango");
                    $("#ainaModal").modal({
                        backdrop: "static",
                        keyboard: false
                    });
                    $("#ainaModal").modal("show");
                }
            });
        });

        //editing aina za misa details
        $(document).on("click", ".edit", function(event) {
            event.preventDefault();

            //preventing resubmission of data
            $("#submitBtn").attr("disabled", false);

            //getting the id from button
            var id = $(this).attr("id");

            //getting data from url
            $.ajax({
                url: "/aina_za_michango/" + id + "/edit",
                dataType: "JSON",
                success: function(data) {
                    //preventing resubmission of data
                    $("#submitBtn").attr("disabled", false);
                    $("#createDiv").hide();
                    $("#updateDiv").show();
                    $("#submitBtn").show();
                    $("#submitBtn").html("Badilisha");
                    $("#action").val("Update");
                    $("#hidden_id").val(id);
                    $("#aina_ya_mchango").val(data.result.aina_ya_mchango);
                    $("#maelezo").val(data.result.maelezo);
                  
                      if(data.checkbox=='1'){
                        $
                        $('#ahadi_checkbox').attr('checked',true);
                        $(".awamu_div").css("display", "block");
                        $("#awamu").prop("required", true);
                        
                        $('#awamu').val(data.awamu);
                        $('#kiasi').val(data.kiasi);
                        $('#tarehe').val(data.tarehe);
                      }else{
                        $('#ahadi_checkbox').attr('checked',false)
                      }

                    $(".modal-title").text("Badili aina ya mchango");
                    $("#ainaModal").modal({
                        backdrop: "static",
                        keyboard: false
                    });
                    $("#ainaModal").modal("show");
                }
            });
        });

        //deleting function
        $(document).on("click", ".delete", function(event) {
            event.preventDefault();

            //getting the id
            var delete_id = $(this).attr("id");

            //getting user confirmation
            Swal.fire({
                title: "Una uhakika?",
                text: "Unakaribia kufuta taarifa!",
                icon: "warning",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Ndio, futa!",
                cancelButtonText: "Hapana, tunza!",
                allowOutsideClick: false
            }).then(result => {
                if (result.value) {
                    $.ajax({
                        url: "/aina_za_michango/destroy/" + delete_id,
                        success: function(data) {
                            if (data.success) {
                                //getting the message from response and toast it
                                var message = data.success;
                                Toast.fire({
                                    icon: "success",
                                    title: message
                                });

                                //refreshing the page
                                setTimeout(function() {
                                    window.location.reload();
                                }, 4500);
                            }

                            //errors
                            if (data.errors) {
                                var message = data.errors;
                                //alerting
                                Toast.fire({
                                    icon: "info",
                                    title: message
                                });
                            }
                        }
                    });
                }

                //means user has choose to cancel the action
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    Toast.fire({
                        icon: "info",
                        title: "Umesitisha!"
                    });
                }
            });
        });
    });
</script>

@endsection
