<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_contant;
use App\Models\Resume_personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ResumeController extends Controller
{
    public function create(Request $request)
    {
        $resume = new Resume();
        $resume->user = Auth::user()->id;
        $resume->save();
        $personal = new Resume_personal();

        $personal->name = Auth::user()->name;
        $personal->resume = $resume->id;
        $personal->surname = Auth::user()->surname;
        $personal->city = json_decode(Cookie::get('city'))->id;
        $personal->save();

        $contants = new Resume_contact();
        $contants->resume = $resume->id;
        $contants->phone = Auth::user()->phone;
        $contants->save();

        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.resume.applicant', ['resume' => $resume->id]),
                'resume' => $resume,
            ]
        ], 200);
    }


    public function personal(Request $request)
    {
        $resume = Resume::find(12);
        if ($resume->user != Auth::user()->id) return response('', 404);

        $personal = Resume_personal::where("resume", $resume->id)->first();
        
        if ($request->name && $personal->name != $request->name) $personal->name = $request->name;
        if ($request->surname && $personal->surname != $request->surname) $personal->surname = $request->surname;
        if ($request->patronymic && $personal->patronymic != $request->patronymic) $personal->patronymic = $request->patronymic;
        if ($request->birthdayDay && $personal->birthday_day != $request->birthdayDay) $personal->birthday_day = $request->birthdayDay;
        if ($request->birthdayMonth && $personal->birthday_month != $request->birthdayMonth) $personal->birthday_month = $request->birthdayMonth;
        if ($request->birthdayYear && $personal->birthday_year != $request->birthdayYear) $personal->birthday_year = $request->birthdayYear;
        if ($request->gender && $personal->gender != $request->gender) $personal->gender = $request->gender;
        if ($request->moving && $personal->moving != $request->moving) $personal->moving = $request->moving;
        if ($request->trips && $personal->trips != $request->trips) $personal->trips = $request->trips;

        $personal->save();
        return 123;
    }
}
