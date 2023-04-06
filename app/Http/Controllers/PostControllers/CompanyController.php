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
        ], [], [
            'legal_title' => 'Юридическое название компании',
            'description' => 'Описание компании',
        ]);

        $company = new Company();
        $company->legal_title = $request->legal_title;
        $company->description = $request->description;
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
}
