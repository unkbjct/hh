<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SingleController extends Controller
{
    public function signup()
    {
        return view('signup');
    }
    
    public function login()
    {
        return view('login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }
    public function welcome()
    {
        return view('welcome');
    }


    public function personal()
    {
        return view('personal.personal');
    }

}
