<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Company_user;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function list(Company $company)
    {
        $company->users = Company_user::where("company", $company->id)->get();
        $company->city = City::find($company->city);

        return view('personal.company.vacancy.list', [
            'company' => $company,
            // 'vacancies' => $vacancies,
        ]);
    }


    public function create(Company $company)
    {
        return view('personal.company.vacancy.create', [
            'company' => $company,
        ]);
    }
}
