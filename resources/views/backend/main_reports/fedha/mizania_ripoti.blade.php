@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row text-right">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-info mr-2">CHAPISHA</button>
                            <a href="{{url()->previous()}}"><button class="btn btn-warning btn-sm">Rudi</button></a>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        @include('layouts.header')    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection