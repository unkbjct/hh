<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Company_user;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function list()
    {
        $companies = Company_user::where("user", Auth::user()->id)
            ->join("companies", "company_users.company", "=", "companies.id")
            // ->join("users", "company_users.user", "=", "users.id")
            ->get();
        $companies->transform(function ($item) {
            $item->count = Vacancy::where("company", $item->id)->count();
            return $item;
        });
        return view('personal.company.list', [
            'companies' => $companies,
        ]);
    }

    public function create()
    {
        return view('personal.company.create');
    }

    public function edit(Company $company)
    {
        $company->cityId = $company->city;
        $company->city = (City::find($company->city)->city)
            ? City::find($company->city)->city
            : City::find($company->city)->region;

        return view('personal.company.edit', [
            'company' => $company,
        ]);
    }
}
