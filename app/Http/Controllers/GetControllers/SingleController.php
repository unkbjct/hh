<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_education;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
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


    public function resume(Resume $resume)
    {
        $resume->personal = Resume_personal::where("resume", $resume->id)->first();
        $resume->contacts = Resume_contact::where("resume", $resume->id)->first();
        $resume->job = Resume_job::where("resume", $resume->id)->first();
        $resume->hasExperience = Resume_experience::where("resume", $resume->id)->first();
        $resume->experiences = Experience_item::where("resume", $resume->id)->get();
        $resume->educations = Resume_education::where("resume", $resume->id)->get();
        $resume->city = City::find($resume->personal->city);
        // dd($resume);
        return view('resume', [
            'resume' => $resume,
        ]);
    }
}
