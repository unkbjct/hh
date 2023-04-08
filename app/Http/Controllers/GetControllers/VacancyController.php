<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Company_user;
use App\Models\Employment;
use App\Models\Schedule;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function list(Company $company)
    {
        $company->users = Company_user::where("company", $company->id)->get();
        $company->city = City::find($company->city);

        $vacancies = Vacancy::where("company", $company->id)->get();

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
}
