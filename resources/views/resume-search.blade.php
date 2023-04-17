@extends('layouts.main')

@section('title')
    Поиск работника
@endsection

@section('main')
    <div class="container">
        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center">
                <h1>Поиск работника</h1>
                <button class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#filtersModal">Фильтры</button>
            </div>
            <div>Количество найденых вакансий: {{ $resumes->count() }}</div>
        </div>
        <hr>
        <div class="row gy-4">
            @forelse ($resumes as $resume)
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">

                                @if ($resume->gender === 'MALE')
                                    <div
                                        class="border-primary-subtle me-2 border border-2 rounded shadow-sm rounde me-2d-1">
                                        <svg fill="#636363" height="60px" viewBox="0 0 50 50" width="60px">
                                            <rect fill="none" height="50" width="50" />
                                            <path
                                                d="M30.933,32.528c-0.146-1.612-0.09-2.737-0.09-4.21c0.73-0.383,2.038-2.825,2.259-4.888c0.574-0.047,1.479-0.607,1.744-2.818  c0.143-1.187-0.425-1.855-0.771-2.065c0.934-2.809,2.874-11.499-3.588-12.397c-0.665-1.168-2.368-1.759-4.581-1.759  c-8.854,0.163-9.922,6.686-7.981,14.156c-0.345,0.21-0.913,0.878-0.771,2.065c0.266,2.211,1.17,2.771,1.744,2.818  c0.22,2.062,1.58,4.505,2.312,4.888c0,1.473,0.055,2.598-0.091,4.21C19.367,37.238,7.546,35.916,7,45h38  C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="border-primary-subtle me-2 border border-2 rounded shadow-sm rounde me-2d-1">
                                        <svg fill="#636363" height="60px" viewBox="0 0 50 50" width="60px">
                                            <rect fill="none" height="50" width="50" />
                                            <path
                                                d="M30.933,32.528c-0.026-0.287-0.045-0.748-0.06-1.226c4.345-0.445,7.393-1.487,7.393-2.701  c-0.012-0.002-0.011-0.05-0.011-0.07c-3.248-2.927,2.816-23.728-8.473-23.306c-0.709-0.6-1.95-1.133-3.73-1.133  c-15.291,1.157-8.53,20.8-12.014,24.508c-0.002,0.001-0.005,0.001-0.007,0.001c0,0.002,0.001,0.004,0.001,0.006  c0,0.001-0.001,0.002-0.001,0.002s0.001,0,0.002,0.001c0.014,1.189,2.959,2.212,7.178,2.668c-0.012,0.29-0.037,0.649-0.092,1.25  C19.367,37.238,7.546,35.916,7,45h38C44.455,35.916,32.685,37.238,30.933,32.528z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('resume', ['resume' => $resume->id]) }}"
                                        class="title-link text-decoration-none">{{ $resume->position }}</a>
                                    <div class="h6">{{ $resume->surname }} {{ $resume->name }}</div>
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

            @empty

                <div class="col-lg-12">
                    <div class="text-center py-4 text-muted h4">По заданным фильтрам ничего не найдено</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection


@section('modals')
    <!-- Modal -->
    <form action="">
        <div class="modal fade" id="filtersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Расширенный поиск</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-5">
                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-2">Город поиска</div>
                                <div class="col-lg-3">
                                    <div class="position-relative">
                                        <input type="hidden" class="input-personal" name="city" id="city">
                                        <input type="text" class="form-control validation" name="city_title"
                                            value="{{ old('city_title') }}" id="city-form-input">
                                        <div class="list-group position-absolute w-100 z-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">Должность</div>
                                <div class="col-lg-4">
                                    <input type="text" name="position" class="form-control"
                                        value="{{ old('position') }}">
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">Ключевые навыки</div>
                                <div class="col-lg-4">
                                    <textarea name="skills" id="skills" rows="2" class="form-control">{{old('skills')}}</textarea>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <div>Укажите ключевые навыки через запятую, пример:</div>
                                        <div>
                                            <code>работа в команде, целеустремленность, javascript, ...</code>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">Уровень образования</div>
                                <div class="col-lg-3">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('secondary', old('education'))) checked @endif value="secondary"
                                                id="education1">
                                            <label class="form-check-label" for="education1">
                                                Среднее
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('special_secondary', old('education'))) checked @endif value="special_secondary"
                                                id="education2">
                                            <label class="form-check-label" for="education2">
                                                Среднее специальное
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('unfinished_higher', old('education'))) checked @endif value="unfinished_higher"
                                                id="education3">
                                            <label class="form-check-label" for="education3">
                                                Неоконченное высшее
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('higher', old('education'))) checked @endif value="higher"
                                                id="education4">
                                            <label class="form-check-label" for="education4">
                                                Высшее
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('bachelor', old('education'))) checked @endif value="bachelor"
                                                id="education5">
                                            <label class="form-check-label" for="education5">
                                                Бакалавр
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('master', old('education'))) checked @endif value="master"
                                                id="education6">
                                            <label class="form-check-label" for="education6">
                                                Магистр
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('candidate', old('education'))) checked @endif value="candidate"
                                                id="education7">
                                            <label class="form-check-label" for="education7">
                                                Кандидат наук
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('doctor', old('education'))) checked @endif value="doctor"
                                                id="education8">
                                            <label class="form-check-label" for="education8">
                                                Доктор наук
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7"></div>

                                <div class="col-lg-2">Опыт работы</div>
                                <div class="col-lg-4">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience[]"
                                                @if (old('experience') && in_array('1', old('experience'))) checked @endif value="1"
                                                id="experience1">
                                            <label class="form-check-label" for="experience1">
                                                Есть
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience[]"
                                                @if (old('experience') && in_array('0', old('experience'))) checked @endif value="0"
                                                id="experience2">
                                            <label class="form-check-label" for="experience2">
                                                Нет
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">Тип занятости</div>
                                <div class="col-lg-4">
                                    <div>
                                        @foreach ($employments as $employ)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="employments[]"
                                                    @if (old('employments') && in_array($employ->id, old('employments'))) checked @endif
                                                    value="{{ $employ->id }}" id="employ-{{ $employ->id }}">
                                                <label class="form-check-label" for="employ-{{ $employ->id }}">
                                                    {{ $employ->employment }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">График работы</div>
                                <div class="col-lg-4">
                                    <div>
                                        @foreach ($schedules as $schedule)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="schedules[]"
                                                    @if (old('schedules') && in_array($schedule->id, old('schedules'))) checked @endif
                                                    value="{{ $schedule->id }}" id="schedule-{{ $schedule->id }}">
                                                <label class="form-check-label" for="schedule-{{ $schedule->id }}">
                                                    {{ $schedule->schedule }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">Сортировка</div>
                                <div class="col-lg-4">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sort"
                                                @if (old('sort') == 'created_at|desc') checked @endif value="created_at|desc"
                                                id="sort1">
                                            <label class="form-check-label" for="sort1">
                                                Сначала новые
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sort"
                                                @if (old('sort') == 'created_at|asc') checked @endif value="created_at|asc"
                                                id="sort2">
                                            <label class="form-check-label" for="sort2">
                                                Сначала старые
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                            </div>
                        </div>
                    </div>
                    <div class="py-3 border-top">
                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <div>
                                        <a href="{{ route('resume.search') }}" class="btn btn-danger">Сбросить
                                            фильтры</a>
                                        <button class="btn btn-primary">Найти</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
