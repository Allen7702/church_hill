@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{$title}}</h3>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <form method="POST" action="{{route('update_sahihisha')}}" id="fedhaForm">
                            @csrf
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="">Badili jina la Kanda/Vigango/Mtaa au jingine:</label>
                                    <input type="text" class="form-control" name="kanda" value="@if(is_null($sahihisha_kanda))Kanda @else{{$sahihisha_kanda->name}}@endif"  required>
                                </div>
                            </div>
                            <div class="form-group" style="margin-left:15px;">
                                <button class="btn btn-sm btn-info">Wasilisha &nbsp;&nbsp;<i class="fas fa-fw fa-check-circle"></i></button>
                            </div>  
                        </div>
    
                        </form>
                    </div>
                </div>                
            </div>
        </div>
    </div>

@endsection