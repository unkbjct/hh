@extends('layouts.main')

@section('title')
    Резюме {{ $resume->job->title }}
@endsection

@auth
    @section('scripts')
        <script>
            let resumeId = {{ $resume->id }}
            let token = '{{ Auth::user()->api_token }}'

            $("#change-image").on("change", function() {
                console.log(this.name, this.files)
                let formData = new FormData();
                formData.append("image", this.files[0])
                formData.append("resumeId", resumeId)
                formData.append("token", token)
                $.ajax({
                    url: "{{ route('api.resume.edit.image') }}",
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(e) {
                        console.log(e)
                        location.reload();
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })
        </script>
    @endsection
@endauth


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
                                <span>{{ $resume->city }}, </span>
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
                                    @endswitch.
                                </span>
                                @if (Auth::check() && $resume->user === Auth::user()->id)
                                    <div><small><a
                                                href="{{ route('personal.resume.edit.personal', ['resume' => $resume->id]) }}">Редактировать</a></small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="">
                            @if ($resume->image)
                                <img class="border-primary-subtle border border-2 w-100 rounded-1 shadow"
                                    style="max-width: 130px" src="{{ asset($resume->image) }}" alt="">
                            @else
                                @if ($resume->personal->gender === 'MALE')
                                    <div class="border-primary-subtle border border-2 shadow-sm rounded-1">
                                        <svg fill="#636363" height="130px" viewBox="0 0 50 50" width="130px">
                                            <rect fill="none" height="50" width="50" />
                                            <path
                                                d="M30.933,32.528c-0.146-1.612-0.09-2.737-0.09-4.21c0.73-0.383,2.038-2.825,2.259-4.888c0.574-0.047,1.479-0.607,1.744-2.818  c0.143-1.187-0.425-1.855-0.771-2.065c0.934-2.809,2.874-11.499-3.588-12.397c-0.665-1.168-2.368-1.759-4.581-1.759  c-8.854,0.163-9.922,6.686-7.981,14.156c-0.345,0.21-0.913,0.878-0.771,2.065c0.266,2.211,1.17,2.771,1.744,2.818  c0.22,2.062,1.58,4.505,2.312,4.888c0,1.473,0.055,2.598-0.091,4.21C19.367,37.238,7.546,35.916,7,45h38  C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="border-primary-subtle border border-2 shadow-sm rounded-1">
                                        <svg fill="#636363" height="130px" viewBox="0 0 50 50" width="130px">
                                            <rect fill="none" height="50" width="50" />
                                            <path
                                                d="M30.933,32.528c-0.026-0.287-0.045-0.748-0.06-1.226c4.345-0.445,7.393-1.487,7.393-2.701  c-0.012-0.002-0.011-0.05-0.011-0.07c-3.248-2.927,2.816-23.728-8.473-23.306c-0.709-0.6-1.95-1.133-3.73-1.133  c-15.291,1.157-8.53,20.8-12.014,24.508c-0.002,0.001-0.005,0.001-0.007,0.001c0,0.002,0.001,0.004,0.001,0.006  c0,0.001-0.001,0.002-0.001,0.002s0.001,0,0.002,0.001c0.014,1.189,2.959,2.212,7.178,2.668c-0.012,0.29-0.037,0.649-0.092,1.25  C19.367,37.238,7.546,35.916,7,45h38C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                        </svg>
                                    </div>
                                @endif
                            @endif
                            @if (Auth::check() && $resume->user === Auth::user()->id)
                                <input type="file" class="visually-hidden" name="" id="change-image">
                                <div>
                                    <a class="link"><small><label style="cursor: pointer" for="change-image">Изменить
                                                фото</label></small></a>
                                </div>
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
                                <h3>{{ number_format($resume->job->salary, 0, 0, ' ') }} руб. на руки</h3>
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
                                    <a href="{{ route('personal.resume.edit.skills', ['resume' => $resume->id]) }}"
                                        class="link">
                                        <div class="mb-1">Добавить навыки</div>
                                    </a>
                                @endif
                                @if ($resume->drivingCategories->isEmpty())
                                    <a href="{{ route('personal.resume.edit.driving-experience', ['resume' => $resume->id]) }}"
                                        class="link">
                                        <div class="mb-1">Добавить категории прав/наличие авто</div>
                                    </a>
                                @endif
                                @if (!$resume->about)
                                    <a href="{{ route('personal.resume.edit.experience', ['resume' => $resume->id]) }}"
                                        class="link">
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
                                    <a href="{{ route('personal.resume.edit.education', ['resume' => $resume->id]) }}"
                                        class="link">
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
