@extends('layouts.app')

@section('content')

<div class="main-content">
    <div class="container mt-5 pb-4">
        <div class="row justify-content-center">
        
            <div class="col-md-4 col-sm-12 col-lg-4">
                
                <div class="card border-0 mb-0">
                    
                    <div class="card-body px-lg-4 py-lg-5">

                        <div class="text-center py-3">  
                            <img src="{{asset('images/atlais.png')}}" alt="logo" class="img-fluid" style="width:120px;">
                        </div>

                        <?php 
                            $details = App\CentreDetail::first();
                        ?>
                        
                        @if($details != "")

                        {{-- <div class="text-center">
                            <h4>Atlais ya Parokia</h4>
                        </div> --}}
                    
                        <div class="text-center my-3">
                            <h4 class="mt-2 text-primary" style="font-weight:bold;">{{strtoupper($details->centre_name)}}</h4>
                        </div>
                        
                        @else
                        <div class="text-center mb-3">
                            {{-- <h4>Atlais ya Parokia</h4>
                            <h4></h4>     --}}
                        </div>
                        
                        @endif

                        <form role="form" method="POST" action="{{route('login')}}">
                            @csrf
                            <div class="form-group mb-3">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Barua pepe" type="email">
                                    @error('email')
                                        <span class="invalid-feedback text-center" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                    
                            <div class="form-group mb-3">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fw fa-key"></i></span>
                                    </div>
                                    <input class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Nywila/Neno la siri" type="password">
                                    @error('password')
                                        <span class="invalid-feedback text-center" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-primary w-100">Ingia</button>
                            </div>

                            <div class="form-group mb-2 text-center">
                                <a href="{{ route('password.request') }}" class="text-muted">Umesahau nywila?</a>
                            </div>
                        
                        </form>
                    </div>

                </div>
                
                <div class="text-center pt-4">
                    <h4 class="text-bold text-primary">Haki zimehifadhiwa &copy; {{Carbon::now()->format('Y')}} Atlais</h4>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
