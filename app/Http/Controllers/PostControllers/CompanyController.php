<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Company_user;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'legal_title' => 'required',
            'description' => 'min:100',
            // 'image' => 'required',
            'city' => 'required',
        ], [], [
            'legal_title' => 'Юридическое название компании',
            'description' => 'Описание компании',
            // 'image' => 'Лого компании',
            'city' => 'Город',
        ]);


        $company = new Company();
        $company->legal_title = $request->legal_title;
        $company->description = $request->description;
        $company->city = $request->city;
        $company->address = $request->address;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/companies', 'public');
            $company->image = "public/storage/{$path}";
        }
        $company->save();
        $companyUser = new Company_user();
        $companyUser->company = $company->id;
        $companyUser->user = User::where("api_token", $request->token)->first()->id;
        $companyUser->save();
        return response([
            'status' => 'success'
        ], 200);
    }

    public function edit(Company $company, Request $request)
    {
        $request->validate([
            'legal_title' => 'required',
            'description' => 'min:100',
        ], [], [
            'legal_title' => 'Юридическое название компании',
            'description' => 'Описание компании',
        ]);

        $company->legal_title = $request->legal_title;
        $company->description = $request->description;
        $company->city = $request->city;
        $company->address = $request->address;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/companies', 'public');
            $company->image = "public/storage/{$path}";
        }
        if ($company->status == "CANCELED") $company->status = "CREATED";
        $company->save();
        return response([
            'status' => 'success'
        ], 200);
    }

    public function remove(Company $company)
    {
        $company->delete();
        return response([
            'status' => 'success'
        ], 200);
    }
}
