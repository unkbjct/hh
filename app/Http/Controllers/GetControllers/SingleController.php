<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SingleController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
    
    public function signup()
    {
        return view('signup');
    }

    public function login()
    {
        return view('login');
    }
}
