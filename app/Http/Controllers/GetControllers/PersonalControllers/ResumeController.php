<?php

namespace App\Http\Controllers\GetControllers\PersonalControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Employment;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_education;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
use App\Models\Schedule;
use App\Models\Driving_license_category;
use App\Models\Resume_drive_category;
use App\Models\Resume_employment;
use App\Models\Resume_schedule;
use App\Models\Resume_skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function list()
    {
        $resumesList = Resume::where("user", Auth::user()->id)->get();

        $resumesList->transform(function ($resume) {
            $resume->job = Resume_job::where("resume", $resume->id)->first();
            return $resume;
        });

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
        $resume->employments = Resume_employment::where("resume", $resume->id)->get();
        $resume->schedules = Resume_schedule::where("resume", $resume->id)->get();
        $resume->drivingCategories = Resume_drive_category::where("resume", $resume->id)->get();
        $resume->skills = Resume_skill::where("resume", $resume->id)->get();
        $resume->personal->city = (City::find($resume->personal->city)->city) ? City::find($resume->personal->city)->city : City::find($resume->personal->city)->region;
        $employments = Employment::all();
        $schedules = Schedule::all();
        $drivingCategories = Driving_license_category::all();
        // dd($resume);
        return view('personal.resume.applicant', [
            'resume' => $resume,
            'employments' => $employments,
            'schedules' => $schedules,
            'drivingCategories' => $drivingCategories,

        ]);
    }

    public function personal(Request $request, Resume $resume)
    {
        $resume->personal = Resume_personal::where("resume", $resume->id)->first();
        return view('personal.resume.edit.personal', [
            'resume' => $resume,
        ]);
    }

    public function contacts(Resume $resume)
    {
        $resume->contacts = Resume_contact::where("resume", $resume->id)->first();
        return view('personal.resume.edit.contacts', [
            'resume' => $resume,
        ]);
    }

    public function job(Resume $resume)
    {
        $resume->job = Resume_job::where("resume", $resume->id)->first();
        $resume->employments = Resume_employment::where("resume", $resume->id)->get();
        $resume->schedules = Resume_schedule::where("resume", $resume->id)->get();


        $employments = Employment::all();
        $schedules = Schedule::all();
        return view('personal.resume.edit.job', [
            'resume' => $resume,
            'employments' => $employments,
            'schedules' => $schedules,
        ]);
    }

    public function experience(Resume $resume)
    {
        $resume->hasExperience = Resume_experience::where("resume", $resume->id)->first();
        $resume->experiences = Experience_item::where("resume", $resume->id)->get();
        return view('personal.resume.edit.experience', [
            'resume' => $resume,
        ]);
    }

    public function education(Resume $resume)
    {
        $resume->educations = Resume_education::where("resume", $resume->id)->get();

        return view('personal.resume.edit.education', [
            'resume' => $resume,
        ]);
    }

    public function drivingExperience(Resume $resume)
    {
        $resume->drivingCategories = Resume_drive_category::where("resume", $resume->id)->get();

        $drivingCategories = Driving_license_category::all();

        return view('personal.resume.edit.driving-experience', [
            'resume' => $resume,
            'drivingCategories' => $drivingCategories,
        ]);
    }


    public function skills(Resume $resume)
    {
        $resume->skills = Resume_skill::where("resume", $resume->id)->get();

        return view('personal.resume.edit.skills', [
            'resume' => $resume,
        ]);
    }
}
