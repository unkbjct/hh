<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Employment;
use App\Models\Experience_item;
use App\Models\Resume;
use App\Models\Resume_contact;
use App\Models\Resume_drive_category;
use App\Models\Resume_education;
use App\Models\Resume_employment;
use App\Models\Resume_experience;
use App\Models\Resume_job;
use App\Models\Resume_personal;
use App\Models\Resume_schedule;
use App\Models\Resume_skill;
use App\Models\Schedule;
use App\Models\User_response;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SingleController extends Controller
{
    public function signup()
    {
        return view('signup');
    }

    public function login()
    {
        return view('login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function welcome()
    {
        if (!Cookie::get('city')) return redirect()->route('api.city.define');
        $vacancies = Vacancy::where("status", "PUBLISHED")
            ->where("city", json_decode(Cookie::get('city'))->id)
            ->orderByDesc("id")
            ->limit(10)
            ->get();

        $city = (City::find(json_decode(Cookie::get('city'))->id)->city)
            ? City::find(json_decode(Cookie::get('city'))->id)->city
            : City::find(json_decode(Cookie::get('city'))->id)->region;

        if ($vacancies->isEmpty()) {
            $city = false;
            $vacancies = Vacancy::where("status", "PUBLISHED")->limit(10)->orderByDesc("id")->get();
        }

        $vacancies->transform(function ($item) {
            $item->city = (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });

        $resumes = Resume::where("status", "PUBLISHED")
            ->join("resume_jobs", "resumes.id", "=", "resume_jobs.resume")
            ->join("resume_personals", "resumes.id", "=", "resume_personals.resume")
            ->join("resume_experiences", "resumes.id", "=", "resume_experiences.resume")
            ->select("resumes.*", "resume_jobs.title as position", "resume_jobs.salary")
            ->addSelect("resume_personals.name", "resume_personals.surname", "resume_personals.city", "resume_personals.gender")
            ->addSelect("resume_personals.birthday_month", "resume_personals.birthday_year", "resume_personals.birthday_day")
            ->get();

        $resumes->transform(function ($item) {
            $item->city = (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->hasExperience = (Experience_item::where("resume", $item->id)->first()) ? 1 : 0;
            return $item;
        });



        // dd($city);

        return view('welcome', [
            'vacancies' => $vacancies,
            'resumes' => $resumes,
            'city' => $city,
        ]);
    }


    public function personal()
    {
        return view('personal.personal');
    }


    public function resume(Resume $resume)
    {
        $resume->personal = Resume_personal::where("resume", $resume->id)->first();
        $resume->contacts = Resume_contact::where("resume", $resume->id)->first();
        $resume->job = Resume_job::where("resume", $resume->id)->first();
        $resume->hasExperience = Resume_experience::where("resume", $resume->id)->first();
        $resume->experiences = Experience_item::where("resume", $resume->id)->get();
        $resume->educations = Resume_education::where("resume", $resume->id)->get();
        $resume->city = (City::find($resume->personal->city)->city) ? City::find($resume->personal->city)->city : City::find($resume->personal->city)->region;
        $resume->employments = Resume_employment::where("resume", $resume->id)
            ->join("employments", "resume_employments.employment", "=", "employments.id")
            ->get();
        $resume->schedules = Resume_schedule::where("resume", $resume->id)
            ->join("schedules", "resume_schedules.schedule", "=", "schedules.id")
            ->get();
        $resume->drivingCategories = Resume_drive_category::where("resume", $resume->id)
            ->join("driving_license_categories", "resume_drive_categories.category", "=", "driving_license_categories.id")
            ->get();
        $resume->skills = Resume_skill::where("resume", $resume->id)->get();
        // dd($resume);

        // dd($resume);
        return view('resume', [
            'resume' => $resume,
        ]);
    }


    public function vacancySearch(Request $request)
    {
        $request->flash();
        $vacancies = Vacancy::where("status", "PUBLISHED");

        if ($request->has('position') && $request->position) $vacancies->where("position", "LIKE", "%{$request->position}%");
        if ($request->has('salary')) {
            if (isset($request->salary['from'])) $vacancies->where("salary", ">=", $request->salary['from']);
            if (isset($request->salary['to'])) $vacancies->where("salary", "<=", $request->salary['to']);
        }
        if ($request->has('education') && $request->education) $vacancies->whereIn("education", $request->education);
        if ($request->has('experience') && $request->experience) $vacancies->where("experience", $request->experience);
        if ($request->has('employments') && $request->employments) $vacancies->whereIn("employment", $request->employments);
        if ($request->has('schedules') && $request->schedules) $vacancies->whereIn("schedule", $request->schedules);
        ($request->has('sort') && $request->sort)
            ? $vacancies->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1])
            : $vacancies->orderBy("id", "DESC");


        if ($request->has('city') && $request->city && $request->city == 'own') $vacancies->where("city", json_decode(Cookie::get('city'))->id);

        $vacancies = $vacancies->get();
        // dd($request->education);

        if (Auth::check()) {
            foreach ($vacancies as $vacancy) {
                $vacancy->userResponse = (User_response::where("user", Auth::user()->id)->where("vacancy", $vacancy->id)->where("created_at", ">", Carbon::now()->subDays(1))->first())
                    ? 1 : 0;
            }
        }

        $vacancies->transform(function ($item) {
            $item->city =  (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });

        $employments = Employment::all();
        $schedules = Schedule::all();
        // dd($vacancies);

        return view('vacancy-search', [
            'vacancies' => $vacancies,
            'employments' => $employments,
            'schedules' => $schedules,
        ]);
    }

    public function favorites()
    {
        $favorites = json_decode(Cookie::get('favorite_vacancy'));
        if ($favorites) {
            $vacancies = Vacancy::whereIn("id", $favorites)->get();
        } else {
            $vacancies = new Collection();
        }

        foreach ($vacancies as $vacancy) {
            $vacancy->userResponse = (User_response::where("user", Auth::user()->id)->where("vacancy", $vacancy->id)->where("created_at", ">", Carbon::now()->subDays(1))->first())
                ? 1 : 0;
        }

        $vacancies->transform(function ($item) {
            $item->city =  (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });

        return view('personal.favorites', [
            'vacancies' => $vacancies,
        ]);
    }

    public function responses()
    {
        $vacancies = Vacancy::join("user_responses", "vacancies.id", "=", "user_responses.vacancy")
            ->where("user", Auth::user()->id)
            ->select("vacancies.*", "user_responses.id as response_queue")
            ->orderBy("response_queue", "desc")
            ->get();

        $vacancies = $vacancies->unique();

        foreach ($vacancies as $vacancy) {
            $vacancy->userResponse = (User_response::where("user", Auth::user()->id)->where("vacancy", $vacancy->id)->where("created_at", ">", Carbon::now()->subDays(1))->first())
                ? 1 : 0;
        }

        $vacancies->transform(function ($item) {
            $item->city =  (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });

        return view('personal.responses', [
            'vacancies' => $vacancies,
        ]);
    }


    public function resumeSearch(Request $request)
    {
        $request->flash();
        $resumes = Resume::where("status", "PUBLISHED")
            ->join("resume_jobs", "resumes.id", "=", "resume_jobs.resume")
            ->join("resume_personals", "resumes.id", "=", "resume_personals.resume")
            ->join("resume_experiences", "resumes.id", "=", "resume_experiences.resume")
            ->select("resumes.*", "resume_jobs.title as position", "resume_jobs.salary")
            ->addSelect("resume_personals.name", "resume_personals.surname", "resume_personals.city", "resume_personals.gender")
            ->addSelect("resume_experiences.has as has_experience")
            ->addSelect("resume_personals.birthday_month", "resume_personals.birthday_year", "resume_personals.birthday_day");

        if ($request->has('position') && $request->position) $resumes->where("position", "LIKE", "%{$request->position}%");

        if ($request->has('education') && $request->education) $resumes->whereIn("education_level", $request->education);
        if ($request->has('experience') && $request->experience) $resumes->whereIn("has_experience", $request->experience);
        if ($request->has('employments') && $request->employments) {
            $tempEmployments = Resume_employment::whereIn("employment", $request->employments)->select("resume")->distinct('resume')->get();
            $employments = [];
            foreach ($tempEmployments as $tempEmploy) {
                array_push($employments, $tempEmploy->resume);
            }
            $resumes = $resumes->whereIn("resumes.id", $employments);
        }
        if ($request->has('schedules') && $request->schedules) {
            $tempSchedules = Resume_schedule::whereIn("schedule", $request->schedules)->select("resume")->distinct('resume')->get();
            $schedules = [];
            foreach ($tempSchedules as $tempSchedule) {
                array_push($schedules, $tempSchedule->resume);
            }
            $resumes = $resumes->whereIn("resumes.id", $schedules);
        }
        if ($request->has('city') && $request->city) $resumes->where("city", $request->city);
        if ($request->has('skills') && $request->skills) {
            $tempSkills = Resume_skill::whereIn("skill", explode(",", $request->skills))->select("resume")->distinct('resume')->get();
            $skills = [];
            foreach ($tempSkills as $tempSkill) {
                array_push($skills, $tempSkill->resume);
            }
            $resumes = $resumes->whereIn("resumes.id", $skills);
        }
        ($request->has('sort') && $request->sort)
            ? $resumes->orderBy(explode("|", $request->sort)[0], explode("|", $request->sort)[1])
            : $resumes->orderBy("id", "DESC");





        $resumes = $resumes->get();

        $resumes->transform(function ($item) {
            $item->city = (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->hasExperience = (Experience_item::where("resume", $item->id)->first()) ? 1 : 0;
            // if()
            return $item;
        });


        $employments = Employment::all();
        $schedules = Schedule::all();

        return view("resume-search", [
            'resumes' => $resumes,

            'employments' => $employments,
            'schedules' => $schedules,
        ]);
    }

    public function aboutResponses()
    {
        return view("about.responses");
    }
}
