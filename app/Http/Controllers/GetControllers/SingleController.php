<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_drive_category;
use App\Models\Resume_education;
use App\Models\Resume_employment;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
use App\Models\Resume_schedule;
use App\Models\Resume_skill;
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
        $resume->employments = Resume_employment::where("resume", $resume->id)
        ->join("employments", "resume_employments.employment", "=", "employments.id")
        ->get();
        $resume->schedules = Resume_schedule::where("resume", $resume->id)
        ->join("schedules", "resume_schedules.schedule", "=", "schedules.id")
        ->get();
        $resume->drivingCategories = Resume_drive_category::where("resume", $resume->id)
        ->join("driving_license_categories", "resume_drive_categories.category", "=", "driving_license_categories.id")
        ->get();
        $resume->skills = Resume_skill::where("resume", $resume->id)->get();
        // dd($resume);

        // dd($resume);
        return view('resume', [
            'resume' => $resume,
        ]);
    }
}
