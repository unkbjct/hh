@extends('layouts.main')

@section('title')
    Резюме {{ $resume->job->title }}
@endsection

@section('main')
    <div class="container">
        <section>
            <div class="row gy-4">
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2 class="mb-4">{{ $resume->personal->surname }} {{ $resume->personal->name }}</h2>
                            <div class="mb-5">
                                <span>
                                    @if ($resume->personal->gender == 'MALE')
                                        Мужчина
                                    @else
                                        Девушка
                                    @endif,
                                </span>
                                <span>{{ date('Y') - $resume->personal->birthday_year }} лет,</span>
                                <span>
                                    @if ($resume->personal->gender == 'MALE')
                                        родился
                                    @else
                                        родилась
                                    @endif
                                    {{ $resume->personal->birthday_day }}.{{ $resume->personal->birthday_month }}.{{ $resume->personal->birthday_year }}
                                </span>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a href="">Редактировать</a></small></div>
                                @endif
                            </div>
                            <div class="mb-5">
                                <div class="mb-1"><small class="text-muted">Контакты</small></div>
                                <div>
                                    @if ($resume->contacts->phone)
                                        <div>{{ $resume->contacts->phone }} @if ($resume->contacts->recomended == 'phone')
                                                - предпочитаемый вид связи
                                            @endif
                                        </div>
                                    @endif
                                    @if ($resume->contacts->email)
                                        <div>{{ $resume->contacts->email }} @if ($resume->contacts->recomended == 'email')
                                                - предпочитаемый вид связи
                                            @endif
                                        </div>
                                    @endif
                                    @if ($resume->contacts->telegram)
                                        <div>Телеграм - {{ $resume->contacts->telegram }} @if ($resume->contacts->recomended == 'telegram')
                                                - предпочитаемый вид связи
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a href="">Редактировать</a></small></div>
                                @endif
                            </div>
                            <div class="mb-5">
                                <span>{{ $resume->city->city }}, </span>
                                <span>
                                    @switch($resume->personal->moving)
                                        @case('cant')
                                            к переезду не готов
                                        @break

                                        @case('can')
                                            может переехать
                                        @break

                                        @default
                                            желает переехать
                                    @endswitch,
                                </span>
                                <span>
                                    @switch($resume->personal->trips)
                                        @case('never')
                                            не готов к командировкам
                                        @break

                                        @case('ready')
                                            готов к командировкам
                                        @break

                                        @default
                                            иногда может ездить в командировки
                                    @endswitch,
                                </span>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a href="">Редактировать</a></small></div>
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <img class="w-100 rounded-1 shadow" style="max-width: 130px"
                                src="https://img.hhcdn.ru/photo/712419954.jpeg?t=1680691978&h=9NcnW1snnhVplCp-8k8njA"
                                alt="">
                            @if (Auth::check() && $resume->user === Auth::user()->id)
                                <div><small><a href="">Изменить фото</a></small></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="h-100 border-start border-danger border-opacity-25">

                    </div>
                </div>
            </div>
        </section>
        <hr class="border-danger m-0">
        <section>
            <div class="row gy-4">
                <div class="col-lg-9">
                    <div class="pt-5">
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-3">
                                <h2 class="me-auto">{{ $resume->job->title }}</h2>
                                <h3>{{ $resume->job->salary }} руб. на руки</h3>
                            </div>
                            <div class="mb-3">
                                <div>Занятость: @if (!$resume->employments)
                                        не указано
                                    @else
                                    @endif
                                </div>
                                <div>График работы: @if (!$resume->schedule)
                                        не указано
                                    @else
                                    @endif
                                </div>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a href="">Редактировать</a></small></div>
                                @endif
                            </div>
                        </div>
                        @if ($resume->hasExperience && $resume->hasExperience->has)
                            <div class="mb-5">
                                <div class="mb-3">
                                    <span class="h4 text-secondary">Опыт работы</span>
                                    @if (Auth::check() && $resume->user === Auth::user()->id)
                                        <small class="ms-3"><a href="">Редактировать</a></small>
                                    @endif
                                </div>
                                <div>
                                    <div class="row gy-4">
                                        @foreach ($resume->experiences as $expItem)
                                            <div class="col-lg-7">
                                                <div class="card card-body border-0 shadow-sm">
                                                    <div class="mb-4">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="fs-5 fw-semibold">{{ $expItem->position }}</div>
                                                            <div class=" fs-6 text-muted">
                                                                {{ date('Y') - $expItem->start_year }} лет</div>
                                                        </div>
                                                        <code class="fs-5">{{ $expItem->company }}</code>
                                                        <hr>
                                                        <div class="">{{ $expItem->responsibilities }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5"></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($resume->about)
                            <div class="mb-5">
                                <div class="mb-3">
                                    <span class="h4 text-secondary">Обо мне</span>
                                    @if (Auth::check() && $resume->user === Auth::user()->id)
                                        <small class="ms-3"><a href="">Редактировать</a></small>
                                    @endif
                                </div>
                                <div>{{ $resume->about }}</div>
                            </div>
                        @endif
                        <div class="mb-5">
                            <div class="mb-3">
                                <span class="h4 text-secondary">Образование -
                                    @switch($resume->education_level)
                                        @case('secondary')
                                            Среднее
                                        @break

                                        @case('special_secondary')
                                            Средне еспециальное
                                        @break

                                        @case('unfinished_higher')
                                            Неоконченное высшее
                                        @break

                                        @case('higher')
                                            Высшее
                                        @break

                                        @case('bachelor')
                                            Бакалавр
                                        @break

                                        @case('master')
                                            Магистр
                                        @break

                                        @case('candidate')
                                            Кандидат наук
                                        @break

                                        @case('doctor')
                                            Доктор наук
                                        @break
                                    @endswitch
                                </span>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <small class="ms-3"><a href="">Редактировать</a></small>
                                @endif
                            </div>
                            <div>
                                <div class="row gy-4">
                                    @foreach ($resume->educations as $eduItem)
                                        <div class="col-lg-7">
                                            <div class="card card-body border-0 shadow-sm">
                                                <code class="fw-semibold fs-5">{{ $eduItem->college }}</code>
                                                <div>{{ $eduItem->faculty }}</div>
                                                <div class="fw-semibold">{{ $eduItem->specialitty }}</div>
                                                <div>Год окончания: {{ $eduItem->year_end }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="h-100 border-start border-danger border-opacity-25 bg-white">
                        @if (Auth::check() && $resume->user === Auth::user()->id)
                            <div class="p-3 pt-5">
                                <div class="fs-5 fw-semibold mb-3">Видимость резюме</div>
                                <div class="mb-3">
                                    @if ($resume->status == 'PUBLISHED')
                                        <div class="text-muted">Сейчас резюме всем видно</div>
                                    @else
                                        <div class="text-muted">Сейчас резюме никому не видно</div>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Изменить видимость
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Показывать резюме</a></li>
                                        <li><a class="dropdown-item" href="#">Скрывать резюме</a></li>
                                    </ul>
                                </div>
                                <hr class="mb-4">
                                <div class="fs-6 fw-semibold mb-2">Вы можете еще добавить</div>
                                @if (!$resume->skills)
                                    <a href="" class="link">
                                        <div class="mb-1">навыки</div>
                                    </a>
                                @endif
                                @if (!$resume->skills)
                                    <a href="" class="link">
                                        <div class="mb-1">Наличине прав</div>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
