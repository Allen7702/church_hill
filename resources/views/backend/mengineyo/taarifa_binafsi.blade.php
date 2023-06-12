@extends('layouts.master')
@section('content')

<body onload="myFunction()">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-with-nav">
                <div class="card-header">
                    <div class="row row-nav-line">
                        <ul class="nav nav-tabs nav-line nav-color-secondary w-100 pl-3" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" onclick="personalFunction()" data-toggle="tab" href="#home" aria-selected="true">Taarifa Binafsi</a> </li>
                            <li class="nav-item"> <a class="nav-link" onclick="nywilaFunction()" data-toggle="tab" href="#nywila" role="tab" aria-selected="true">Nywila</a> </li>
                        </ul>
                    </div>

                    @if (session('errors'))
                    <div class="alert alert-warning text-center text-small mt-2">
                        <button class="close" type="button" data-dismiss="alert">&times;</button>
                        @foreach ($errors->all() as $error)
                        <small>{{$error}}</small>
                        @endforeach
                    </div>
                    @endif
    
                    @if (session('success'))
                    <div class="alert alert-success">
                        <button class="close" type="button" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
                <div class="card-body">
    
                    <div class="tab" id="personalDiv">
                        <form action="{{route('users.profile_update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Jina Kamili:</label>
                                        <input type="text" name="jina_kamili" class="form-control" value="{{$personal_details->jina_kamili}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Ngazi:</label>

                                        @if(($personal_details->ngazi == "") || ($personal_details->ngazi == "NULL"))
                                            <input type="text" name="ngazi" readonly class="form-control" value="Kiongozi">
                                        @else
                                            <input type="text" name="ngazi" readonly class="form-control" value="{{$personal_details->ngazi}}">
                                        @endif
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Cheo:</label>

                                        @if($personal_details->cheo == "NULL")
                                        <input type="text" name="cheo" class="form-control" readonly value="{{$personal_details->cheo}}">
                                        @else
                                        <input type="text" name="cheo" class="form-control" readonly value="Mtumiaji">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Barua pepe:</label>
                                        <input type="email" name="email" class="form-control" readonly value="{{$personal_details->email}}">
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Anwani:</label>
                                        <input type="text" name="anwani" class="form-control" value="{{$personal_details->anwani}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Simu:</label>
                                        <input type="text" name="mawasiliano" readonly class="form-control" value="{{$personal_details->mawasiliano}}">
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Picha yako:</label>
                                        <input class="form-control" accept="images/*.png,.png,.jpg,.jpeg,.gif,.tiff" type="file" name="picha">
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <div class="text-right mb-0">
                                    <button type="reset" class="btn btn-warning mr-3">Anza upya</button>
                                    <button type="submit" class="btn btn-info">Wasilisha taarifa</button>
                                </div>
                            </div>
        
                        </form>
                    </div>
    
                    <div class="tab" id="nywilaDiv">
                        <form action="{{route('users.password_update')}}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="">Nywila ya sasa:</label>
                                <input id="old_password" name="old_password" type="password" placeholder="Nywila ya sasa"
                                class="form-control @error('old_password') is-invalid @enderror">
                            </div>
        
                            <div class="form-group">
                                <label>Nywila mpya:</label>
                                <input id="password" name="password" type="password" placeholder="Nywila mpya"
                                class="form-control @error('password') is-invalid @enderror">                             
                            </div>
        
                            <div class="form-group">
                                <label>Hakiki nywila mpya:</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    placeholder="Hakiki nywila mpya"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            </div>

                            <div class="form-group">
                                <div class="text-right mb-0">
                                    <button class="btn btn-warning mr-3">Anza upya</button>
                                    <button type="submit" class="btn btn-info">Wasilisha taarifa</button>
                                </div>
                            </div>
                        </form>
                    </div>
    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                    <div class="profile-picture">
                        <div class="avatar avatar-xl">
                            @if(auth()->user()->picha == "")
                                <img src="{{asset('images/profile.png')}}" alt="..." class="avatar-img rounded-circle">
                            @else
                                <img src="{{url('uploads/images/'.$personal_details->picha)}}" alt="..." class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="user-profile text-center">
                        <div class="name">{{auth()->user()->jina_kamili}}</div>
                        <div class="job">{{auth()->user()->ngazi}}</div>
                        
                        <div class="social-media">
                            <a class="btn btn-info btn-email btn-sm btn-link"> 
                                <span class="btn-label just-icon"><i class="flaticon-envelope"></i> </span>
                            </a>{{$personal_details->email}}
                        </div>
    
                        <div class="view-profile">
                            <a class="btn btn-info w-100" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fas fa-fw fa-power-off"></i> &nbsp;&nbsp; {{ __('Ondoka') }}
                            </a>
    
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>   
</body>

<script>
    
    function myFunction(){
        $('#personalDiv').show();
        $('#nywilaDiv').hide();
    }

    function nywilaFunction(){
        $('#personalDiv').hide();
        $('#nywilaDiv').show();
    }

    function personalFunction(){
        $('#personalDiv').show();
        $('#nywilaDiv').hide();
    }

</script>
@endsection