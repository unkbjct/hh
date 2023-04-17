<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Resume;
use App\Models\Resume_job;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function resume(Request $request)
    {
        $request->flash();
        $resumes = Resume::join("resume_personals", "resumes.id", "=", "resume_personals.resume")
            ->select("resumes.*", "resume_personals.name", "resume_personals.surname");

        if ($request->has('name') && $request->name) $resumes->where("resume_personals.name", "LIKE", "%{$request->name}%")->orWhere("resume_personals.surname", "LIKE", "%{$request->name}%");
        if ($request->has('position') && $request->position) {
            $resumes->join("resume_jobs", "resumes.id", "=", "resume_jobs.resume")->addSelect("resume_jobs.title");
            $resumes->where("title", "LIKE", "%{$request->position}%");
        }
        if ($request->has('status') && $request->status) $resumes->whereIn('status', $request->status);

        if ($request->has('sort') && $request->sort) {
            $resumes->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1]);
        }

        $resumes = $resumes->get();

        $resumes->transform(function ($resume) {
            $resume->job = Resume_job::where("resume", $resume->id)->first();
            return $resume;
        });



        return view('admin.resume', [
            'resumes' => $resumes,
        ]);
    }

    public function company(Request $request)
    {
        $request->flash();
        $companies = Company::select();
        if ($request->has('status') && $request->status) $companies->whereIn('status', $request->status);
        if ($request->has('title') && $request->title) $companies->where("legal_title", "LIKE", "%{$request->title}%");
        if ($request->has('sort') && $request->sort) {
            $companies->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1]);
        }
        $companies = $companies->get();

        return view('admin.company', [
            'companies' => $companies,
        ]);
    }

    public function companyInformation(Company $company)
    {
        $company->city = City::find($company->city);
        return view('admin.company-information', [
            'company' => $company,
        ]);
    }

    public function vacancy(Request $request)
    {
        $request->flash();
        $vacancies = Vacancy::select();
        if ($request->has('status') && $request->status) {
            if ($request->status == 'PUBLISHED') {
                array_push($request->status, "HIDDEN");
                $vacancies->whereIn('status', $request->status);
            } else {
                $vacancies->whereIn('status', $request->status);
            }
        };
        if ($request->has('position') && $request->position) $vacancies->where("position", "LIKE", "%{$request->title}%");
        if ($request->has('sort') && $request->sort) {
            $vacancies->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1]);
        }
        $vacancies = $vacancies->get();
        return view('admin.vacancy', [
            'vacancies' => $vacancies,
        ]);
    }

    public function users(Request $request)
    {
        $request->flash();
        $users = User::select();
        if ($request->has('status') && $request->status) {
            if ($request->status == 'PUBLISHED') {
                array_push($request->status, "HIDDEN");
                $users->whereIn('status', $request->status);
            } else {
                $users->whereIn('status', $request->status);
            }
        };
        if ($request->has('phone') && $request->phone) $users->where("phone", "LIKE", "%{$request->phone}%");
        if ($request->has('email') && $request->email) $users->where("email", "LIKE", "%{$request->email}%");
        if ($request->has('name') && $request->name) $users->where("name", "LIKE", "%{$request->name}%")->orWhere("surname", "LIKE", "%{$request->name}%");
        if ($request->has('sort') && $request->sort) {
            $users->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1]);
        }

        $users = $users->get();
        return view('admin.users', [
            'users' => $users,
        ]);
    }
}
