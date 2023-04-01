<?php

namespace App\Http\Controllers\GetControllers\PersonalControllers;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\Resume_contact;
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
        // dd($resume);
        return view('personal.resume.applicant', [
            'resume' => $resume,
        ]);
    }
}
