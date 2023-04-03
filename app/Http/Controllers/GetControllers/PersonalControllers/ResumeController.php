<?php

namespace App\Http\Controllers\GetControllers\PersonalControllers;

use App\Http\Controllers\Controller;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_education;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function list()
    {
        $resumesList = Resume::where("user", Auth::user()->id)->get();
        return view('personal.resume.list', [
            'resumesList' => $resumesList,
        ]);
    }

    public function applicant(Resume $resume)
    {
        $resume->personal = Resume_personal::where("resume", $resume->id)->first();
        $resume->contacts = Resume_contact::where("resume", $resume->id)->first();
        $resume->job = Resume_job::where("resume", $resume->id)->first();
        $resume->hasExperience = Resume_experience::where("resume", $resume->id)->first();
        $resume->experiences = Experience_item::where("resume", $resume->id)->get();
        $resume->educations = Resume_education::where("resume", $resume->id)->get();
        // dd($resume);
        return view('personal.resume.applicant', [
            'resume' => $resume,
        ]);
    }
}
