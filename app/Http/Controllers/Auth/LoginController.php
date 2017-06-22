<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function login(Request $request){

        $email = $request->input('email', null);
        $password = $request->input('password', null);

        if(Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('/dashboard');
        }
    }



    public function logout(Request $request){
        Auth::logout();
        return redirect('/');
    }

    /*public function auth(Request $request)
    {

        $email = $request->input('email', null);
        $password = $request->input('password', null);
       // $user = User::where('email', $email);

        if(Auth::attempt(['email' => $email, 'password' => $password], true)) {
//            Auth::login($user);
           // dd(Auth::user());
            return redirect('/dashboard');
        }
        //dd(Auth::user());
//        else
//        {
//            return 'try again';
//        }
//        dd(Auth::guest());
    }*/
}
