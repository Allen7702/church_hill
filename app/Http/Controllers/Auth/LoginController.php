<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Alert;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {   
        $input = $request->all();

        $this->validate($request, [
            'mawasiliano' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'mawasiliano';
        if(auth()->attempt(array($fieldType => $input['mawasiliano'], 'password' => $input['password'])))
        {
            return redirect()->route('home');
        }else{
            Alert::info("<h1 class='text-primary'>Taarifa!</h1>","Neno la siri au namba ya simu si sahihi.. jaribu tena")->autoclose(5000);
            return redirect()->route('login')->with('error','Neno la siri au mawasiliano si sahihi.');
        }
    }
}
