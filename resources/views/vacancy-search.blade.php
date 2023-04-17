@extends('layouts.main')

@section('title')
    Поиск работы
@endsection

@section('main')
    <div class="container">
        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center">
                <h1>Поиск работы</h1>
                <button class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#filtersModal">Фильтры</button>
            </div>
            <div>Количество найденых вакансий: {{ $vacancies->count() }}</div>
        </div>
        <hr>
        <div class="row gy-4">
            @forelse ($vacancies as $vacancy)
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
                            <div class="fs-5 fw-semibold">До {{ number_format($vacancy->salary, 0, 0, ' ') }} руб. на руки
                            </div>
                            <div class="d-flex align-items-end">
                                <div class="me-auto">
                                    <div>{{ $vacancy->company->legal_title }}</div>
                                    <div class="mb-3 fw-bold"><code>{{ $vacancy->city }}</code></div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mt-auto">
                                <div class="btn-group"
                                    @guest data-bs-toggle="tooltip" data-bs-title="Авторизуйтель чтобы была возможность откликнуться на вакансию" @endguest>
                                    <button
                                        class="btn @if ($vacancy->userResponse) btn-outline-danger @else btn-danger @endif btn-sm create-response @guest disabled @endguest"
                                        data-vacancy-id="{{ $vacancy->id }}">Откликнуться</button>
                                </div>
                                <button
                                    class="btn btn-outline-danger favorite-btn @if(Cookie::get('favorite_vacancy') && in_array($vacancy->id, json_decode(Cookie::get('favorite_vacancy')))) active @endif"
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
                                <div class="col-lg-4">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="city" id="city1"
                                                @if (old('city') == 'own') checked @endif value="own">
                                            <label class="form-check-label" for="city1">
                                                Искать в моем городе
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="city" value="all"
                                                @if (old('city')) @if (old('city') == 'all') checked @endif
                                            @else checked @endif
                                            id="city2">
                                            <label class="form-check-label" for="city2">
                                                Искать во всех городах
                                            </label>
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

                                <div class="col-lg-2">Уровень дохода</div>
                                <div class="col-lg-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">от</span>
                                        <input type="number" name="salary[from]"
                                            @if (isset(old('salary')['from'])) value="{{ old('salary')['from'] }}" @endif
                                            class="form-control">
                                        <span class="input-group-text" id="basic-addon1">до</span>
                                        <input type="number" name="salary[to]"
                                            @if (isset(old('salary')['to'])) value="{{ old('salary')['to'] }}" @endif
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-7"></div>

                                <div class="col-lg-2">Образование</div>
                                <div class="col-lg-4">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('Не требуется', old('education'))) checked @endif value="Не требуется"
                                                id="education1">
                                            <label class="form-check-label" for="education1">
                                                Не требуется или не указано
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('Среднее профессиональное', old('education'))) checked @endif
                                                value="Среднее профессиональное" id="education2">
                                            <label class="form-check-label" for="education2">
                                                Среднее профессиональное
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="education[]"
                                                @if (old('education') && in_array('Высшее', old('education'))) checked @endif value="Высшее"
                                                id="education3">
                                            <label class="form-check-label" for="education3">
                                                Высшее
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6"></div>

                                <div class="col-lg-2">Требуемый опыт работы</div>
                                <div class="col-lg-4">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience"
                                                @if (old('experience') == 'Не имеет значения') checked @endif
                                                value="Не имеет значения" id="experience1">
                                            <label class="form-check-label" for="experience1">
                                                Не имеет значения
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience"
                                                @if (old('experience') == 'Нет опыта') checked @endif value="Нет опыта"
                                                id="experience2">
                                            <label class="form-check-label" for="experience2">
                                                Нет опыта
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience"
                                                @if (old('experience') == 'От 1 года до 3 лет') checked @endif
                                                value="От 1 года до 3 лет" id="experience3">
                                            <label class="form-check-label" for="experience3">
                                                От 1 года до 3 лет
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience"
                                                @if (old('experience') == 'От 3 лет до 6 лет') checked @endif
                                                value="От 3 лет до 6 лет" id="experience4">
                                            <label class="form-check-label" for="experience4">
                                                От 3 до 6 лет
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="experience"
                                                @if (old('experience') == 'Более 6 лет') checked @endif value="Более 6 лет"
                                                id="experience5">
                                            <label class="form-check-label" for="experience5">
                                                Более 6 лет
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
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sort"
                                                @if (old('sort') == 'salary|desc') checked @endif value="salary|desc"
                                                id="sort3">
                                            <label class="form-check-label" for="sort3">
                                                По убыванию зарплат
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sort"
                                                @if (old('sort') == 'salary|asc') checked @endif value="salary|asc"
                                                id="sort4">
                                            <label class="form-check-label" for="sort4">
                                                По возрастанию зарплат
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
                                        <a href="{{ route('vacancy.search') }}" class="btn btn-danger">Сбросить
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
