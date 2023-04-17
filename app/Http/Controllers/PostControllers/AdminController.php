<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Resume;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function vacancy(Vacancy $vacancy, Request $request)
    {
        $vacancy->status = $request->status;
        if ($vacancy->status == 'CANCELED') {
            $request->validate([
                'cancelText' => 'required',
            ], [], [
                'cancelText' => 'Причина отмены'
            ]);
        }
        $vacancy->cancel_text = $request->cancelText;
        $vacancy->save();
        return response([
            'status' => 'success',
        ], 200);
    }

    public function resume(Resume $resume, Request $request)
    {
        $resume->status = $request->status;
        if ($resume->status == 'CANCELED') {
            $request->validate([
                'cancelText' => 'required',
            ], [], [
                'cancelText' => 'Причина отмены'
            ]);
        }
        $resume->cancel_text = $request->cancelText;
        $resume->save();

        return response([
            'status' => 'success',
        ], 200);
    }

    public function company(Company $company, Request $request)
    {
        $company->status = $request->status;
        if ($company->status == 'CANCELED') {
            $request->validate([
                'cancelText' => 'required',
            ], [], [
                'cancelText' => 'Причина отмены'
            ]);
        }
        $company->cancel_text = $request->cancelText;
        $company->save();
        return response([
            'status' => 'success',
        ], 200);
    }

    public function user(Request $request)
    {

        $user = User::find($request->userId);
        $user->status = $request->status;
        $user->save();
        return response([
            'status' => 'success',
        ], 200);
    }
}
