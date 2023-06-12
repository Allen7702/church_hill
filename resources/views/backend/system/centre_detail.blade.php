@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Taarifa za Parokia</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        
                            @if($details->count() != 0)
                            <form action="{{route('centre_details.update')}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @foreach ($details as $item)

                                <input type="hidden" name="hidden_id" value="{{$item->id}}">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Jina la parokia:</label>
                                            <input type="text" class="form-control" name="centre_name" value="{{$item->centre_name}}" placeholder="Jina la parokia" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Anwani:</label>
                                            <input type="text" class="form-control" name="address" value="{{$item->address}}" placeholder="Anwani" required>
                                        </div>
                                    </div> 
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Barua pepe:</label>
                                            <input type="email" class="form-control" name="email" value="{{$item->email}}" placeholder="Barua pepe">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Mkoa:</label>
                                            <input type="text" class="form-control" name="region" value="{{$item->region}}" placeholder="Mkoa" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Nchi:</label>
                                            <input type="text" class="form-control" name="country" value="{{$item->country}}" placeholder="Nchi husika" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Jimbo:</label>
                                            <input type="text" class="form-control" name="jimbo" value="{{$item->jimbo}}" placeholder="Jina la jimbo" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Simu ya mezani #1:</label>
                                            <input type="text" class="form-control" name="telephone1" value="{{$item->telephone1}}" placeholder="Simu ya mezani">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Simu ya mezani #2:</label>
                                            <input type="text" class="form-control" name="telephone2" value="{{$item->telephone2}}" placeholder="Simu ya mezani">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">Picha (Logo):</label>
                                            <input type="file" accept="images/*.png,.png,.gif,.jpg,.jpeg,.tiff" class="form-control" name="photo" placeholder="Picha ya Parokia">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Badili taarifa</button>
                                </div>
        
                            </form>
                            @endforeach

                            @else
                            <form action="{{route('centre_details.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Jina la parokia:</label>
                                        <input type="text" class="form-control" name="centre_name" placeholder="Jina la parokia" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Anwani:</label>
                                        <input type="text" class="form-control" name="address" placeholder="Anwani" required>
                                    </div>
                                </div> 
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Barua pepe:</label>
                                        <input type="email" class="form-control" name="email" placeholder="Barua pepe">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Mkoa:</label>
                                        <input type="text" class="form-control" name="region" placeholder="Mkoa" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Nchi:</label>
                                        <input type="text" class="form-control" name="country" placeholder="Nchi husika" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Jimbo:</label>
                                        <input type="text" class="form-control" name="jimbo" placeholder="Jimbo" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Simu ya mezani #1:</label>
                                        <input type="text" class="form-control" name="telephone1" placeholder="Simu ya mezani" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Simu ya mezani #2:</label>
                                        <input type="text" class="form-control" name="telephone2" placeholder="Simu ya mezani">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Picha (Logo):</label>
                                        <input type="file" accept="images/*.png,.png,.gif,.jpg,.jpeg,.tiff" class="form-control" name="photo" placeholder="Picha ya Parokia">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Wasilisha taarifa</button>
                            </div>
                        </form>
                            @endif

                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-profile">

            @if($details->count() != 0)
            @foreach ($details as $item)
            
            <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                <div class="profile-picture">
                    <div class="avatar avatar-xl">
                        <img src='{{url("uploads/images/$item->photo")}}' alt="..." class="avatar-img rounded-circle">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="user-profile text-center">
                    <div class="name">{{$item->centre_name}}</div>
                    <div class="job">{{$item->address}} </div>
                    <div class="job">{{$item->region}} {{$item->country}}</div>
                    <div class="desc">{{$item->telephone1}}</div>
                    <div class="social-media">
                        <a class="btn btn-info btn-email btn-sm btn-link"> 
                            <span class="btn-label just-icon"><i class="flaticon-envelope"></i> </span>
                        </a>{{$item->email}}
                    </div>
                </div>
            </div>
            
            @endforeach
            @endif
        </div>
    </div>
    
</div>
    
@endsection