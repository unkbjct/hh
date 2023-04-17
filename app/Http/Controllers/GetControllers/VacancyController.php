<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Company_user;
use App\Models\Employment;
use App\Models\Resume;
use App\Models\Schedule;
use App\Models\User_response;
use App\Models\Vacancy;
use App\Models\Vacancy_offer;
use App\Models\Vacancy_plus;
use App\Models\Vacancy_requirement;
use App\Models\Vacancy_responsibility;
use App\Models\Vacancy_skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function list(Company $company)
    {
        $users = [];
        foreach (Company_user::where("company", $company->id)->get() as $user) {
            array_push($users, $user->user);
        }
        $company->users = $users;

        $company->city = City::find($company->city);
        if (!Auth::check() || !in_array(Auth::user()->id, $company->users)) {
            $vacancies = Vacancy::where("company", $company->id)->where("status", "PUBLISHED")->get();
        } else {
            $vacancies = Vacancy::where("company", $company->id)->get();
        }
        $company->count = $vacancies->count();

        $vacancies->transform(function ($item) {
            $item->city =  (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });



        return view('personal.company.vacancy.list', [
            'company' => $company,
            'vacancies' => $vacancies,
            // 'vacancies' => $vacancies,
        ]);
    }


    public function create(Company $company)
    {
        $employments = Employment::all();
        $schedules = Schedule::all();
        return view('personal.company.vacancy.create', [
            'company' => $company,
            'employments' => $employments,
            'schedules' => $schedules,
        ]);
    }

    public function vacancy(Company $company, Vacancy $vacancy)
    {
        $company->city = City::find($company->city);
        $vacancy->requirements = Vacancy_requirement::where("vacancy", $vacancy->id)->get();
        $vacancy->responsibilities = Vacancy_responsibility::where("vacancy", $vacancy->id)->get();
        $vacancy->offers = Vacancy_offer::where("vacancy", $vacancy->id)->get();
        $vacancy->skills = Vacancy_skill::where("vacancy", $vacancy->id)->get();
        $vacancy->pluses = Vacancy_plus::where("vacancy", $vacancy->id)->get();
        $vacancy->employment = Employment::find($vacancy->employment)->employment;
        $vacancy->schedule = Schedule::find($vacancy->schedule)->schedule;
        $vacancy->city = (City::find($vacancy->city)->city) ? City::find($vacancy->city)->city : City::find($vacancy->city)->region;
        $users = [];
        foreach (Company_user::where("company", $company->id)->get() as $user) {
            array_push($users, $user->user);
        }
        $vacancy->users = $users;
        // dd($vacancy);
        return view('personal.company.vacancy.vacancy', [
            'company' => $company,
            'vacancy' => $vacancy,
        ]);
    }

    public function edit(Company $company, Vacancy $vacancy)
    {
        $employments = Employment::all();
        $schedules = Schedule::all();

        $vacancy->requirements = Vacancy_requirement::where("vacancy", $vacancy->id)->get();
        $vacancy->responsibilities = Vacancy_responsibility::where("vacancy", $vacancy->id)->get();
        $vacancy->offers = Vacancy_offer::where("vacancy", $vacancy->id)->get();
        $vacancy->skills = Vacancy_skill::where("vacancy", $vacancy->id)->get();
        $vacancy->pluses = Vacancy_plus::where("vacancy", $vacancy->id)->get();
        $vacancy->city = (City::find($vacancy->city)->city) ? City::find($vacancy->city)->city : City::find($vacancy->city)->region;

        return view('personal.company.vacancy.edit', [
            'company' => $company,
            'vacancy' => $vacancy,
            'employments' => $employments,
            'schedules' => $schedules,
        ]);
    }

    public function responses(Company $company, Vacancy $vacancy, Request $request)
    {
        $responses = User_response::where("vacancy", $vacancy->id)
            ->join("users", "user_responses.user", "=", "users.id")
            ->select("users.*", "user_responses.user", "user_responses.vacancy", "user_responses.id as responseId");

        $responses = $responses->get();

        $responses->transform(function ($item) {
            $item->resumes = Resume::where("user", $item->user)->where("status", "PUBLISHED")
                ->join("resume_jobs", "resumes.id", "=", "resume_jobs.resume")
                ->join("resume_personals", "resumes.id", "=", "resume_personals.resume")
                ->join("resume_contacts", "resumes.id", "=", "resume_contacts.resume")
                ->select("resumes.*", "resume_jobs.title as position", "resume_personals.name", "resume_personals.surname", "resume_personals.city")
                ->addSelect("resume_contacts.phone", "resume_contacts.email", "resume_contacts.telegram", "resume_contacts.recomended")
                ->get();

            return $item;
        });

        // dd($responses[0]);

        return view('personal.company.vacancy.responses', [
            'company' => $company,
            'vacancy' => $vacancy,
            'responses' => $responses,
        ]);
    }
}
