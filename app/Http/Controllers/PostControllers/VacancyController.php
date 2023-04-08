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
        $vacancy->experience = $request->experience;
        $vacancy->education = $request->education;
        $vacancy->employment = $request->employment;
        $vacancy->schedule = $request->schedule;
        $vacancy->schedule = $request->schedule;
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
}
