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
                            <h2 class="mb-4">{{ $resume->personal->surname }} {{ $resume->personal->name }}
                                {{ $resume->personal->patronymic }}</h2>
                            <div class="mb-4">
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
                                    <div><small><a
                                                href="{{ route('personal.resume.edit.personal', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-4">
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
                                    <div><small><a
                                                href="{{ route('personal.resume.edit.contacts', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    </div>
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
                                    <div><small><a
                                                href="{{ route('personal.resume.edit.personal', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    </div>
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
                                <div class="mb-3"><span class="fw-semibold">Занятость:</span>
                                    @forelse ($resume->employments as $employ)
                                        <div class="ms-4">{{ $employ->employment }}</div>
                                    @empty
                                        не указано
                                    @endforelse
                                </div>
                                <div><span class="fw-semibold">График работы:</span>
                                    @forelse ($resume->schedules as $schedule)
                                        <div class="ms-4">{{ $schedule->schedule }}</div>
                                    @empty
                                        не указано
                                    @endforelse
                                </div>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a
                                                href="{{ route('personal.resume.edit.job', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($resume->drivingCategories->isNotEmpty())
                            <div class="mb-5">
                                <div class="mb-3">
                                    <span class="h4 text-secondary">Категория прав</span>
                                    @if (Auth::check() && $resume->user === Auth::user()->id)
                                        <small class="ms-3"><a
                                                href="{{ route('personal.resume.edit.driving-experience', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap mb-2">
                                    @foreach ($resume->drivingCategories as $drivingCategory)
                                        <h4><span
                                                class="badge bg-primary me-1 mb-1">{{ $drivingCategory->category }}</span>
                                        </h4>
                                    @endforeach
                                </div>
                                @if ($resume->has_car)
                                    <div class="fw-semibold">Если личный автобомиль</div>
                                @endif
                            </div>
                        @endif
                        @if ($resume->hasExperience && $resume->hasExperience->has)
                            <div class="mb-5">
                                <div class="mb-3">
                                    <span class="h4 text-secondary">Опыт работы</span>
                                    @if (Auth::check() && $resume->user === Auth::user()->id)
                                        <small class="ms-3"><a
                                                href="{{ route('personal.resume.edit.experience', ['resume' => $resume->id]) }}">Редактировать</a></small>
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
                                        <small class="ms-3"><a
                                                href="{{ route('personal.resume.edit.experience', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    @endif
                                </div>
                                <div>{{ $resume->about }}</div>
                            </div>
                        @endif
                        @if ($resume->skills->isNotEmpty())
                            <div class="mb-5">
                                <div class="mb-3">
                                    <span class="h4 text-secondary">Ключевае навыки</span>
                                    @if (Auth::check() && $resume->user === Auth::user()->id)
                                        <small class="ms-3"><a
                                                href="{{ route('personal.resume.edit.skills', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach ($resume->skills as $skill)
                                        <div class="skills-item shadow-sm bg-white mb-1 me-1 py-2 px-4 rounded d-flex">
                                            <div class="fw-semibold">{{ $skill->skill }}</div>
                                        </div>
                                    @endforeach
                                </div>
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
                                    <small class="ms-3"><a
                                            href="{{ route('personal.resume.edit.education', ['resume' => $resume->id]) }}">Редактировать</a></small>
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
                    <div class="h-100 border-start border-danger border-opacity-25 bg-white shadow-sm">
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
                                {{-- <div class="fs-6 fw-semibold mb-2">Вы можете еще добавить</div> --}}
                                @if ($resume->skills->isEmpty())
                                    <a href="{{ route('personal.resume.edit.skills', ['resume' => $resume->id]) }}" class="link">
                                        <div class="mb-1">Добавить навыки</div>
                                    </a>
                                @endif
                                @if ($resume->drivingCategories->isEmpty())
                                    <a href="{{ route('personal.resume.edit.driving-experience', ['resume' => $resume->id]) }}" class="link">
                                        <div class="mb-1">Добавить категории прав/наличие авто</div>
                                    </a>
                                @endif
                                @if (!$resume->about)
                                    <a href="{{ route('personal.resume.edit.experience', ['resume' => $resume->id]) }}" class="link">
                                        <div class="mb-1">Добавить обо мне</div>
                                    </a>
                                @endif
                                @if ($resume->experiences->isEmpty())
                                    <a href="{{ route('personal.resume.edit.experience', ['resume' => $resume->id]) }}"
                                        class="link">
                                        <div class="mb-1">Добавить опыт работы</div>
                                    </a>
                                @endif
                                @if ($resume->educations->isEmpty())
                                    <a href="{{ route('personal.resume.edit.education', ['resume' => $resume->id]) }}" class="link">
                                        <div class="mb-1">Добавить место учебы</div>
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
