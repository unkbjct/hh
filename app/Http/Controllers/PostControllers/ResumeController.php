<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\Driving_license_category;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_contant;
use App\Models\Resume_education;
use App\Models\Resume_employment;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
use App\Models\Resume_schedule;
use App\Models\Resume_skill;
use App\Models\Resume_drive_category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ResumeController extends Controller
{
    public function create(Request $request)
    {
        $user = ($request->apiToken) ? User::where("api_token", $request->apiToken)->first() : Auth::user();

        // return $request->apiToken;

        $resume = new Resume();
        $resume->user = $user->id;
        $resume->save();
        $personal = new Resume_personal();

        $personal->name = $user->name;
        $personal->resume = $resume->id;
        $personal->surname = $user->surname;
        $personal->city = json_decode(Cookie::get('city'))->id;
        $personal->save();

        $contants = new Resume_contact();
        $contants->resume = $resume->id;
        $contants->phone = $user->phone;
        $contants->save();

        $experience = new Resume_experience();
        $experience->resume = $resume->id;
        $experience->has = 0;
        $experience->save();

        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.resume.applicant', ['resume' => $resume->id]),
                'resume' => $resume,
            ]
        ], 200);
    }


    public function edit(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (User::where("api_token", $request->token)->first() && $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        if ($request->has('about') && $resume->about != $request->about) $resume->about = $request->about;
        if ($request->has('education_level') && $resume->education_level != $request->education_level) $resume->education_level = $request->education_level;
        ($request->has('has_car') && $resume->has_car != $request->has_car) ? $resume->has_car = 1 : $resume->has_car = 0;
        $resume->save();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function personal(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (User::where("api_token", $request->token)->first() && $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $personal = Resume_personal::where("resume", $resume->id)->first();

        if ($request->name && $personal->name != $request->name) $personal->name = $request->name;
        if ($request->surname && $personal->surname != $request->surname) $personal->surname = $request->surname;
        if ($request->has('patronymic') && $personal->patronymic != $request->patronymic) $personal->patronymic = $request->patronymic;
        if ($request->birthdayDay && $personal->birthday_day != $request->birthdayDay) $personal->birthday_day = $request->birthdayDay;
        if ($request->birthdayMonth && $personal->birthday_month != $request->birthdayMonth) $personal->birthday_month = $request->birthdayMonth;
        if ($request->birthdayYear && $personal->birthday_year != $request->birthdayYear) $personal->birthday_year = $request->birthdayYear;
        if ($request->gender && $personal->gender != $request->gender) $personal->gender = $request->gender;
        if ($request->moving && $personal->moving != $request->moving) $personal->moving = $request->moving;
        if ($request->city && $personal->city != $request->city) $personal->city = $request->city;
        if ($request->trips && $personal->trips != $request->trips) $personal->trips = $request->trips;

        $personal->save();
        return response([
            'status' => 'success'
        ], 200);
    }

    public function contacts(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (User::where("api_token", $request->token)->first() && $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $contacts = Resume_contact::where("resume", $resume->id)->first();
        if ($request->has("phone") && $contacts->phone != $request->phone) $contacts->phone = $request->phone;
        if ($request->has("email") && $contacts->email != $request->email) $contacts->email = $request->email;
        if ($request->has("telegram") && $contacts->telegram != $request->telegram) $contacts->telegram = $request->telegram;
        if ($request->has("recomended") && $contacts->recomended != $request->recomended) $contacts->recomended = $request->recomended;
        $contacts->save();

        return response([
            'status' => 'success'
        ], 200);
    }

    public function job(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (User::where("api_token", $request->token)->first() && $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $job = (Resume_job::where("resume", $request->resumeId)->first()) ? Resume_job::where("resume", $request->resumeId)->first() : new Resume_job();
        if (!$job->resume) $job->resume = $request->resumeId;
        if ($request->title && $job->title != $request->title) $job->title = $request->title;
        if ($request->salary && $job->salary != $request->salary) $job->salary = $request->salary;
        $job->save();

        return response([
            'status' => 'success'
        ], 200);
    }

    public function experience(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (User::where("api_token", $request->token)->first() && $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $experience = (Resume_experience::where("resume", $request->resumeId)->first()) ? Resume_experience::where("resume", $request->resumeId)->first() : new Resume_experience();
        if (!$experience->resume) $experience->resume = $request->resumeId;
        ($request->has && $experience->has != $request->has) ? $experience->has = $request->has : $experience->has = 0;
        $experience->save();

        return response([
            'status' => 'success'
        ], 200);
    }

    public function experienceItem(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $request->validate([
            'company' => 'required',
            'position' => 'required',
            'responsibilities' => 'required',
        ], [], [
            'company' => 'Организация',
            'position' => 'Должность',
            'responsibilities' => 'Обязанности',
        ]);

        $experienceItem = (Experience_item::find($request->epxerienceItemId)) ? Experience_item::find($request->epxerienceItemId) : new Experience_item();
        if (!$experienceItem->resume) $experienceItem->resume = $request->resumeId;
        if ($experienceItem->company != $request->company) $experienceItem->company = $request->company;
        if ($experienceItem->position != $request->position) $experienceItem->position = $request->position;
        if ($experienceItem->responsibilities != $request->responsibilities) $experienceItem->responsibilities = $request->responsibilities;
        if ($experienceItem->start_day != $request->start_day) $experienceItem->start_day = $request->start_day;
        if ($experienceItem->start_year != $request->start_year) $experienceItem->start_year = $request->start_year;
        if ($experienceItem->end_day != $request->end_day) $experienceItem->end_day = $request->end_day;
        if ($experienceItem->end_year != $request->end_year) $experienceItem->end_year = $request->end_year;
        $experienceItem->save();

        return response([
            'status' => 'success'
        ], 200);
    }

    public function experienceItemRemove(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Experience_item::find($request->epxerienceItemId)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function educationItem(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $education = new Resume_education();
        $education->resume = $resume->id;
        $education->save();

        return response([
            'status' => 'success',
            'data' => [
                'education' => $education
            ]
        ], 200);
    }


    public function educationItemEdit(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $educationItem = Resume_education::find($request->educationItemId);
        if ($request->has('college') && $educationItem->college != $request->college) $educationItem->college = $request->college;
        if ($request->has('faculty') && $educationItem->faculty != $request->faculty) $educationItem->faculty = $request->faculty;
        if ($request->has('specialitty') && $educationItem->specialitty != $request->specialitty) $educationItem->specialitty = $request->specialitty;
        if ($request->has('year_end') && $educationItem->year_end != $request->year_end) $educationItem->year_end = $request->year_end;
        $educationItem->save();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function educationItemRemove(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_education::find($request->educationItemId)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function employments(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_employment::where("resume", $resume->id)->delete();

        if ($request->employments) {
            foreach ($request->employments as $employ) {
                $employment = new Resume_employment();
                $employment->resume = $resume->id;
                $employment->employment = $employ;
                $employment->save();
            }
        }

        return response([
            'status' => 'success'
        ], 200);
    }

    public function employmentsClear(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_employment::where("resume", $resume->id)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function drivingCategories(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_drive_category::where("resume", $resume->id)->delete();

        if ($request->categories) {
            foreach ($request->categories as $categ) {
                $drive = new Resume_drive_category();
                $drive->resume = $resume->id;
                $drive->category = $categ;
                $drive->save();
            }
        }

        return response([
            'status' => 'success'
        ], 200);
    }

    public function drivingCategoriesClear(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_drive_category::where("resume", $resume->id)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function schedules(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_schedule::where("resume", $resume->id)->delete();

        if ($request->schedules) {
            foreach ($request->schedules as $schedule) {
                $newSchedule = new Resume_schedule();
                $newSchedule->resume = $resume->id;
                $newSchedule->schedule = $schedule;
                $newSchedule->save();
            }
        }

        return response([
            'status' => 'success'
        ], 200);
    }


    public function schedulesClear(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_schedule::where("resume", $resume->id)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }


    public function skills(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_skill::where("resume", $resume->id)->delete();

        if ($request->skills) {
            foreach ($request->skills as $skill) {
                $newSkill = new Resume_skill();
                $newSkill->resume = $resume->id;
                $newSkill->skill = $skill;
                $newSkill->save();
            }
        }

        return response([
            'status' => 'success'
        ], 200);
    }


    public function skillsClear(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        Resume_skill::where("resume", $resume->id)->delete();

        return response([
            'status' => 'success'
        ], 200);
    }



    public function image(Request $request)
    {
        $resume = Resume::find($request->resumeId);
        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);
        
        $path = $request->file('image')->store('images/resumes', 'public');
        $resume->image = "/public/storage/{$path}";
        $resume->save();

        return response([
            'status' => 'success'
        ], 200);
    }




    public function publish(Request $request)
    {
        $resume = Resume::find($request->resumeId);

        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);

        $resume->status = 'EXPECTING';
        $resume->save();

        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.resume.list'),
            ]
        ], 200);
    }

    public function remove(Request $request)
    {
        $resume = Resume::find($request->resumeId);
        // return User::where("api_token", $request->token)->first();
        if (!User::where("api_token", $request->token)->first() || $resume->user != User::where("api_token", $request->token)->first()->id) return response('', 404);
        Resume_job::where("resume", $resume->id)->delete();
        Resume_contact::where("resume", $resume->id)->delete();
        Resume_education::where("resume", $resume->id)->delete();
        Resume_employment::where("resume", $resume->id)->delete();
        Resume_experience::where("resume", $resume->id)->delete();
        Resume_personal::where("resume", $resume->id)->delete();
        Resume_schedule::where("resume", $resume->id)->delete();
        Resume_skill::where("resume", $resume->id)->delete();
        Resume_drive_category::where("resume", $resume->id)->delete();
        $resume->delete();
        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.resume.list'),
            ]
        ], 200);
    }

    public function visibility(Request $request)
    {
        $resume = Resume::find($request->resumeId);
        $resume->status = ($request->visibility == 'show') ? 'PUBLISHED' : 'HIDDEN';
        $resume->save();
        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.resume.list'),
            ]
        ], 200);

    }
}
