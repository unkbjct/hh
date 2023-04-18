@extends('layouts.main')

@section('title')
    WantWork - найти работу легко!
@endsection


@section('main')
    <div class="container">
        <section class="mb-5">
            <h2 class="mb-4">Найти работу - <code class="fs-1">просто</code></h2>
            <div class="mb-4 text-center fw-semibold fs-4">Для того чтобы найти работу, нужно выполнить 4 простых шага</div>
            <div class="row gy-5 text-center py-5">
                <div class="col-lg-6">
                    <div class="card card-body bg-primary-subtle shadow border-0">
                        <div class="display-6">
                            Зарегистрируйся на данном интернет ресурсе
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 m-0"></div>
                <div class="col-lg-6 m-0"></div>
                <div class="col-lg-6">
                    <div class="card card-body bg-danger-subtle shadow border-0">
                        <div class="display-6">
                            Создай резюме
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body bg-danger-subtle shadow border-0">
                        <div class="display-6">
                            Откликнись на понравившуюся вакансию
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 m-0"></div>
                <div class="col-lg-6 m-0"></div>
                <div class="col-lg-6">
                    <div class="card card-body bg-primary-subtle shadow border-0">
                        <div class="display-6">
                            Дождись ответа работодателя
                        </div>
                    </div>
                </div>
                <div class="text-start text-muted">
                    Подробнее о том, как работают октлики, читай <a href="{{ route('about.responses') }}">здесь.</a>
                </div>
            </div>
        </section>
        <section class="mb-5">
            <div class="">
                <div class="bg-white p-2 mb-4 border">
                    <ul class="nav nav-pills" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="job-tab" data-bs-toggle="tab"
                                data-bs-target="#job-tab-pane" type="button">Вакансии</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="worker-tab" data-bs-toggle="tab" data-bs-target="#worker-tab-pane"
                                type="button">Резюме</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="job-tab-pane">
                        <div class="d-flex">
                            <h1 class="h3 mb-4 me-auto">Вакансии @if ($city != false)
                                    в городе {{ json_decode(Cookie::get('city'))->title }}
                                @else
                                    по всей России
                                @endif
                            </h1>
                            <div>
                                <a href="{{ route('vacancy.search') }}" class="btn btn-primary btn-sm">Расширенный поиск</a>
                            </div>
                        </div>
                        <div class="row gy-4">
                            @foreach ($vacancies as $vacancy)
                                <div class="col-lg-6">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body h-100 d-flex flex-column">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="me-4">
                                                    <a target="true"
                                                        href="{{ route('personal.company.vacancy.vacancy', [
                                                            'company' => $vacancy->company->id,
                                                            'vacancy' => $vacancy->id,
                                                        ]) }}"
                                                        class="title-link text-decoration-none">{{ $vacancy->position }}</a>
                                                </div>
                                            </div>
                                            <div class="fs-5 fw-semibold">До
                                                {{ number_format($vacancy->salary, 0, 0, ' ') }} руб. на руки
                                            </div>
                                            <div class="d-flex align-items-end">
                                                <div class="me-auto">
                                                    <div>{{ $vacancy->company->legal_title }}</div>
                                                    <div class="mb-3 fw-bold"><code>{{ $vacancy->city }}</code></div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex flex-wrap align-items-center justify-content-between mt-auto">
                                                <div class="btn-group"
                                                    @guest data-bs-toggle="tooltip" data-bs-title="Авторизуйтель чтобы была возможность откликнуться на вакансию" @endguest>
                                                    <button
                                                        class="btn @if ($vacancy->userResponse) btn-outline-danger @else btn-danger @endif btn-sm create-response @guest disabled @endguest"
                                                        data-vacancy-id="{{ $vacancy->id }}">Откликнуться</button>
                                                </div>
                                                <button
                                                    class="btn btn-outline-danger favorite-btn @if (Cookie::get('favorite_vacancy') && in_array($vacancy->id, json_decode(Cookie::get('favorite_vacancy')))) active @endif"
                                                    data-type="favorite_vacancy" data-id="{{ $vacancy->id }}">
                                                    <svg viewBox="0 0 16 16" fill="currentColor" class="bi bi-heart-fill">
                                                        <path fill-rule="evenodd"
                                                            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="worker-tab-pane">
                        <div class="">
                            <div class="mb-4">

                                <div class="d-flex mb-2 align-items-center">
                                    <h2 class="h3 mb-4 me-auto">Резюме со всей России
                                    </h2>
                                    <a href="{{ route('resume.search') }}"
                                        class="btn btn-primary btn-sm ms-auto">Расширенный
                                        поиск</a>
                                </div>
                            </div>
                            <div class="row gy-4">
                                @foreach ($resumes as $resume)
                                    <div class="col-lg-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">

                                                    @if ($resume->gender === 'MALE')
                                                        <div
                                                            class="border-primary-subtle me-2 border border-2 rounded shadow-sm rounde me-2d-1">
                                                            <svg fill="#636363" height="60px" viewBox="0 0 50 50"
                                                                width="60px">
                                                                <rect fill="none" height="50" width="50" />
                                                                <path
                                                                    d="M30.933,32.528c-0.146-1.612-0.09-2.737-0.09-4.21c0.73-0.383,2.038-2.825,2.259-4.888c0.574-0.047,1.479-0.607,1.744-2.818  c0.143-1.187-0.425-1.855-0.771-2.065c0.934-2.809,2.874-11.499-3.588-12.397c-0.665-1.168-2.368-1.759-4.581-1.759  c-8.854,0.163-9.922,6.686-7.981,14.156c-0.345,0.21-0.913,0.878-0.771,2.065c0.266,2.211,1.17,2.771,1.744,2.818  c0.22,2.062,1.58,4.505,2.312,4.888c0,1.473,0.055,2.598-0.091,4.21C19.367,37.238,7.546,35.916,7,45h38  C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="border-primary-subtle me-2 border border-2 rounded shadow-sm rounde me-2d-1">
                                                            <svg fill="#636363" height="60px" viewBox="0 0 50 50"
                                                                width="60px">
                                                                <rect fill="none" height="50" width="50" />
                                                                <path
                                                                    d="M30.933,32.528c-0.026-0.287-0.045-0.748-0.06-1.226c4.345-0.445,7.393-1.487,7.393-2.701  c-0.012-0.002-0.011-0.05-0.011-0.07c-3.248-2.927,2.816-23.728-8.473-23.306c-0.709-0.6-1.95-1.133-3.73-1.133  c-15.291,1.157-8.53,20.8-12.014,24.508c-0.002,0.001-0.005,0.001-0.007,0.001c0,0.002,0.001,0.004,0.001,0.006  c0,0.001-0.001,0.002-0.001,0.002s0.001,0,0.002,0.001c0.014,1.189,2.959,2.212,7.178,2.668c-0.012,0.29-0.037,0.649-0.092,1.25  C19.367,37.238,7.546,35.916,7,45h38C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('resume', ['resume' => $resume->id]) }}"
                                                            class="title-link text-decoration-none">{{ $resume->position }}</a>
                                                        <div class="h6">{{ $resume->surname }} {{ $resume->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-1">{{ $resume->city }}</div>
                                                <div class="d-flex">
                                                    <div class="me-4 text-muted">Возраст - @if ($resume->birthday_year)
                                                            {{ date('Y') - $resume->birthday_year }} лет
                                                        @else
                                                            не указано
                                                        @endif
                                                    </div>
                                                    <div class="me-4 text-muted">Опыт работы - @if ($resume->hasExperience)
                                                            Есть
                                                        @else
                                                            Нет
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
