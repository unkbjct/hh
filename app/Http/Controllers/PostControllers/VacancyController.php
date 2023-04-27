<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Vacancy_offer;
use App\Models\Vacancy_plus;
use App\Models\Vacancy_requirement;
use App\Models\Vacancy_responsibility;
use App\Models\Vacancy_skill;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function create(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'requirements' => 'required',
            'responsibilities' => 'required',
        ], [], [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'position' => 'Должность',
            'salary' => 'Зарплата',
            'city' => 'Город',
            'phone' => 'Телефон',
            'requirements' => 'Требования',
            'responsibilities' => 'Обязанности',
        ]);

        $vacancy = new Vacancy();

        $vacancy->company = $company->id;
        $vacancy->position = $request->position;
        $vacancy->salary = $request->salary;
        $vacancy->surname = $request->surname;
        $vacancy->name = $request->name;
        $vacancy->phone = $request->phone;
        $vacancy->email = $request->email;
        $vacancy->address = $request->address;
        $vacancy->description = $request->desciption;
        $vacancy->experience = ($request->experience) ? $request->experience : "Не имеет значения";
        $vacancy->city = $request->city;
        $vacancy->education = ($request->education) ? $request->experience : "Не требуется";
        $vacancy->employment = ($request->employment) ? $request->employment : 1;
        $vacancy->schedule = ($request->schedule) ? $request->schedule : 1;
        $vacancy->save();

        if ($request->has('offers')) {
            foreach ($request->offers as $offer) {
                $newOffer = new Vacancy_offer();
                $newOffer->vacancy = $vacancy->id;
                $newOffer->offer = $offer;
                $newOffer->save();
            }
        }

        if ($request->has('pluses')) {
            foreach ($request->pluses as $plus) {
                $newPlus = new Vacancy_plus();
                $newPlus->vacancy = $vacancy->id;
                $newPlus->plus = $plus;
                $newPlus->save();
            }
        }

        if ($request->has('requirements')) {
            foreach ($request->requirements as $requirement) {
                $newRequirement = new Vacancy_requirement();
                $newRequirement->vacancy = $vacancy->id;
                $newRequirement->requirement = $requirement;
                $newRequirement->save();
            }
        }

        if ($request->has('responsibilities')) {
            foreach ($request->responsibilities as $responsibility) {
                $newResponsibility = new Vacancy_responsibility();
                $newResponsibility->vacancy = $vacancy->id;
                $newResponsibility->responsibility = $responsibility;
                $newResponsibility->save();
            }
        }

        if ($request->has('skills')) {
            foreach ($request->skills as $skill) {
                $newSkill = new Vacancy_skill();
                $newSkill->vacancy = $vacancy->id;
                $newSkill->skill = $skill;
                $newSkill->save();
            }
        }

        return response([
            'status' => 'success',
            'data' => [
                'url' => route('personal.company.vacancy.list', ['company' => $company->id])
            ]
        ], 200);
    }


    public function edit(Company $company, Vacancy $vacancy, Request $request)
    {

        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'phone' => 'required',
            'requirements' => 'required',
            'responsibilities' => 'required',
        ], [], [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'position' => 'Должность',
            'salary' => 'Зарплата',
            'phone' => 'Телефон',
            'requirements' => 'Требования',
            'responsibilities' => 'Ключевые навыки',
        ]);


        $vacancy->company = $company->id;
        $vacancy->position = $request->position;
        $vacancy->salary = $request->salary;
        $vacancy->surname = $request->surname;
        $vacancy->name = $request->name;
        $vacancy->phone = $request->phone;
        $vacancy->email = $request->email;
        $vacancy->city = $request->city;
        $vacancy->address = $request->address;
        $vacancy->description = $request->desciption;
        $vacancy->experience = $request->experience;
        $vacancy->education = $request->education;
        $vacancy->employment = $request->employment;
        $vacancy->schedule = $request->schedule;
        $vacancy->schedule = $request->schedule;
        if ($vacancy->status == "CANCELED") $vacancy->status = "CREATED";
        $vacancy->save();

        Vacancy_offer::where("vacancy", $vacancy->id)->delete();
        if ($request->has('offers')) {
            foreach ($request->offers as $offer) {
                $newOffer = new Vacancy_offer();
                $newOffer->vacancy = $vacancy->id;
                $newOffer->offer = $offer;
                $newOffer->save();
            }
        }

        Vacancy_plus::where("vacancy", $vacancy->id)->delete();
        if ($request->has('pluses')) {
            foreach ($request->pluses as $plus) {
                $newPlus = new Vacancy_plus();
                $newPlus->vacancy = $vacancy->id;
                $newPlus->plus = $plus;
                $newPlus->save();
            }
        }

        Vacancy_requirement::where("vacancy", $vacancy->id)->delete();
        if ($request->has('requirements')) {
            foreach ($request->requirements as $requirement) {
                $newRequirement = new Vacancy_requirement();
                $newRequirement->vacancy = $vacancy->id;
                $newRequirement->requirement = $requirement;
                $newRequirement->save();
            }
        }

        Vacancy_responsibility::where("vacancy", $vacancy->id)->delete();
        if ($request->has('responsibilities')) {
            foreach ($request->responsibilities as $responsibility) {
                $newResponsibility = new Vacancy_responsibility();
                $newResponsibility->vacancy = $vacancy->id;
                $newResponsibility->responsibility = $responsibility;
                $newResponsibility->save();
            }
        }

        Vacancy_skill::where("vacancy", $vacancy->id)->delete();
        if ($request->has('skills')) {
            foreach ($request->skills as $skill) {
                $newSkill = new Vacancy_skill();
                $newSkill->vacancy = $vacancy->id;
                $newSkill->skill = $skill;
                $newSkill->save();
            }
        }


        return response([
            'status' => 'success',
            'data' => [
                'url' => (($vacancy->status == 'CREATED') ? route('personal.company.vacancy.list', [
                    'company' => $company->id
                ]) : route('personal.company.vacancy.vacancy', [
                    'company' => $company->id,
                    'vacancy' => $vacancy->id,
                ])),
            ]
        ], 200);
    }


    public function remove(Company $company, Request $request)
    {
        $vacancy = Vacancy::find($request->vacancyId);
        Vacancy_skill::where("vacancy", $vacancy->id)->delete();
        Vacancy_offer::where("vacancy", $vacancy->id)->delete();
        Vacancy_plus::where("vacancy", $vacancy->id)->delete();
        Vacancy_responsibility::where("vacancy", $vacancy->id)->delete();
        Vacancy_requirement::where("vacancy", $vacancy->id)->delete();
        $vacancy->delete();
        return response([
            'status' => 'success',
            'data' => []
        ], 200);
    }

    public function visibility(Company $company, Request $request)
    {
        $vacancy = Vacancy::find($request->vacancyId);
        $vacancy->status = ($request->visibility == 'show') ? 'PUBLISHED' : 'HIDDEN';
        $vacancy->save();
        return response([
            'status' => 'success',
            'data' => []
        ], 200);
    }
}
