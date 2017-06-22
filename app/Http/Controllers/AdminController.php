<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        dd(Auth::user());
//        dd(Auth::user());
//        if(Auth::guest())
//            return 'Please sign in';
        return 'dashboard';
    }
}
