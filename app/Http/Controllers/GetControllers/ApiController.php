<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Company_user;
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
use App\Models\User;
use App\Models\User_response;
use App\Models\Vacancy;
use App\Models\Vacancy_offer;
use App\Models\Vacancy_plus;
use App\Models\Vacancy_requirement;
use App\Models\Vacancy_responsibility;
use App\Models\Vacancy_skill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ApiController extends Controller
{
    public function cityFind(Request $request)
    {
        $cities = City::where("city", "LIKE", "%{$request->city}%")
            ->orWhere("region", "LIKE", "%{$request->city}%")
            ->orWhere("address", "LIKE", "%{$request->city}%")
            ->limit(20)
            ->orderBy("address")
            ->get();
        return $cities;
    }

    public function citySet(Request $request, City $city)
    {
        // dd($city);
        return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
            'id' => $city->id,
            'title' => ($city->city) ? $city->city : $city->region,
        ], JSON_UNESCAPED_UNICODE)));
        // return $city;
    }

    public function cityDefine(Request $request)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = \Location::get($ip);
        if (!$data) {
            $city = City::find(510);
            return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
                'id' => $city->id,
                'title' => ($city->city) ? $city->city : $city->region,
            ], JSON_UNESCAPED_UNICODE)));
        }
        $lat = (int)($data->latitude);
        $long = (int)($data->longitude);
        $city = City::where('geo_lat', "LIKE", "{$lat}%")->where('geo_lon', "LIKE", "{$long}%")->first();
        if ($city) {
            return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
                'id' => $city->id,
                'title' => ($city->city) ? $city->city : $city->region,
            ], JSON_UNESCAPED_UNICODE)));
        } else {
            $city = City::find(510);
            return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
                'id' => $city->id,
                'title' => ($city->city) ? $city->city : $city->region,
            ], JSON_UNESCAPED_UNICODE)));
        }
    }

    public function searchVacancies(Request $request)
    {
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

        $vacancies = $vacancies->get();
        // dd($request->education);

        if (Auth::check()) {
            foreach ($vacancies as $vacancy) {
                $vacancy->userResponse = (User_response::where("user", Auth::user()->id)->where("vacancy", $vacancy->id)->where("created_at", ">", Carbon::now()->subDays(1))->first())
                    ? 1 : 0;
            }
        }

        $vacancies->transform(function ($item) {
            $item->city = (City::find($item->city)->city) ? City::find($item->city)->city : City::find($item->city)->region;
            $item->company =  Company::find($item->company);
            return $item;
        });

        $employments = Employment::all();
        $schedules = Schedule::all();

        return response([
            'status' => 'success',
            'data' => [
                'vacancies' => $vacancies,
                'employments' => $employments,
                'schedules' => $schedules,
            ],
        ], 200);
    }


    public function searchResumes(Request $request)
    {
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


        return response([
            'status' => 'success',
            'data' => [
                'resumes' => $resumes,
                'employments' => $employments,
                'schedules' => $schedules,
            ],
        ], 200);
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

        return response([
            'status' => 'success',
            'data' => [
                'resume' => $resume,
            ],
        ], 200);
    }


    public function companies(Request $request)
    {
        $companies = Company_user::where("user", Auth::user()->id)
            ->join("companies", "company_users.company", "=", "companies.id")
            // ->join("users", "company_users.user", "=", "users.id")
            ->orderByDesc("companies.id")
            ->get();
        $companies->transform(function ($item) {
            $item->count = Vacancy::where("company", $item->id)->count();
            return $item;
        });
        return response([
            'status' => 'success',
            'data' => [
                'companies' => $companies,
            ],
        ], 200);
    }


    public function company(Company $company)
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



        return response([
            'status' => 'success',
            'data' => [
                'company' => $company,
                'vacancies' => $vacancies,
            ],
        ], 200);
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

        return response([
            'status' => 'success',
            'data' => [
                'company' => $company,
                'vacancy' => $vacancy,
            ],
        ], 200);
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

        return response([
            'status' => 'success',
            'data' => [
                'vacancies' => $vacancies,
            ],
        ], 200);
    }


    public function personalResumes(Request $request)
    {
        $user = User::where("api_token", $request->apiToren)->first();
        $resumes = Resume::where("user", $user->id)->orderByDesc("id")->get();

        $resumes->transform(function ($resume) {
            $resume->job = Resume_job::where("resume", $resume->id)->first();
            return $resume;
        });

        return response([
            'status' => 'success',
            'data' => [
                'resumes' => $resumes,
            ],
        ], 200);
    }

    public function vacancyInfo(Vacancy $vacancy)
    {
        $vacancy->requirements = Vacancy_requirement::where("vacancy", $vacancy->id)->get();
        $vacancy->responsibilities = Vacancy_responsibility::where("vacancy", $vacancy->id)->get();
        $vacancy->offers = Vacancy_offer::where("vacancy", $vacancy->id)->get();
        $vacancy->skills = Vacancy_skill::where("vacancy", $vacancy->id)->get();
        $vacancy->pluses = Vacancy_plus::where("vacancy", $vacancy->id)->get();
        $vacancy->employment = Employment::find($vacancy->employment);
        $vacancy->schedule = Schedule::find($vacancy->schedule);
        $vacancy->city = City::find($vacancy->city)->city;

        return response([
            'status' => 'success',
            'data' => [
                'vacancy' => $vacancy,
            ],
        ], 200);
    }
}
