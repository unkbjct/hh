@extends('layouts.main')

@if (Auth::check() && Auth::user()->status == 'ADMIN')
    @section('scripts')
        <script>
            $("#action").on("change", function() {
                let value = this.value
                if (value === 'PUBLISHED') {
                    $("#cancel-view").addClass("visually-hidden")
                } else {
                    $("#cancel-view").removeClass("visually-hidden")
                }
            })

            $("#btn-confirm").click(function() {
                $.ajax({
                    url: "{{ route('api.admin.vacancy.edit', ['vacancy' => $vacancy->id]) }}",
                    method: 'POST',
                    data: {
                        status: $("#action").val(),
                        cancelText: $("#cancelText").val(),
                    },
                    success: function(e) {
                        console.log(e)
                        window.location = "{{ route('admin.vacancy') }}"
                    },
                    error: function(e) {
                        console.log(e)
                        for (let key in e.responseJSON.errors) {
                            $("#" + key).addClass("is-invalid");
                            $("#" + key).parent().find('.invalid-feedback').remove();
                            $("#" + key).parent().append(
                                `<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                        }
                        // alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })
        </script>
    @endsection
@endif

@section('main')
    @if (Auth::check() && Auth::user()->status == 'ADMIN')
        <div class="container mb-5">
            <section>
                <p>Изучите данную вакансию, после либо опубликуйте, либо отмените публикацию и укажите причину
                    отмены</p>
                <div class="row gy-4">
                    <div class="col-lg-12">
                        <div>
                            <label for="action" class="form-label">Действие</label>
                            <select class="form-select" id="action">
                                <option value="PUBLISHED">Подтвердить публикацию</option>
                                <option value="CANCELED">Отменить публикацию</option>
                            </select>
                        </div>
                    </div>
                    <div id="cancel-view" class="col-lg-12 visually-hidden">
                        <div>
                            <label for="action" class="form-label">Укажите причину отмены</label>
                            <textarea id="cancelText" class="form-control validation"
                                placeholder="Укажите по какой именно причине вы отменяете публикацию резюме" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <button class="btn btn-danger" id="btn-confirm">Подтвердить действие</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
    <div class="container">
        <section>
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="card card-body border-0 shadow-sm mb-5">
                        <div class="mb-3">
                            <h1 class="h2 me-3">{{ $vacancy->position }}</h1>
                            @if (Auth::check() && in_array(Auth::user()->id, $vacancy->users))
                                <small><a
                                        href="{{ route('personal.company.vacancy.edit', [
                                            'company' => $company->id,
                                            'vacancy' => $vacancy->id,
                                        ]) }}">Редактировать
                                        вакансию</a></small>
                            @endif
                        </div>
                        <div class="mb-2">
                            <div class="fs-4">До {{ number_format($vacancy->salary, 0, '-', ' ') }} руб. на руки</div>
                        </div>
                        <div class="mb-2">
                            <div>Требуемый опыт работы: {{ $vacancy->experience }}</div>
                        </div>
                        <div class="mb-2">
                            <div>Уровень образования: {{ $vacancy->education }}</div>
                        </div>
                        <div class="mb-2">
                            <div>{{ $vacancy->employment }}, {{ $vacancy->schedule }}</div>
                        </div>
                        <div class="mb-4">{{ $vacancy->description }}</div>
                        <div class="d-flex align-items-center">
                            <div
                                @guest data-bs-toggle="tooltip" data-bs-title="Авторизуйтель чтобы была возможность откликнуться на вакансию" @endguest>
                                <button class="btn btn-outline-primary me-3 @guest disabled @endguest create-response"
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
                    <div class="mb-4">
                        <div class="fw-semibold fs-5 mb-3">Требования</div>
                        <div class="list-group list-group-flush ms-4">
                            @foreach ($vacancy->requirements as $requirement)
                                <div class="list-group-item bg-secondary-subtle">{{ $requirement->requirement }}</div>
                            @endforeach
                        </div>
                    </div>
                    @if ($vacancy->pluses->isNotEmpty())
                        <div class="mb-4">
                            <div class="fw-semibold fs-5 mb-3">Будет плюсом</div>
                            <div class="list-group list-group-flush ms-4">
                                @foreach ($vacancy->pluses as $plus)
                                    <div class="list-group-item  bg-secondary-subtle">{{ $plus->plus }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="mb-4">
                        <div class="fw-semibold fs-5 mb-3">Обязанности</div>
                        <div class="list-group list-group-flush ms-4">
                            @foreach ($vacancy->responsibilities as $responsibility)
                                <div class="list-group-item bg-secondary-subtle">{{ $responsibility->responsibility }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($vacancy->offers->isNotEmpty())
                        <div class="mb-4">
                            <div class="fw-semibold fs-5 mb-3">Что мы предлагаем</div>
                            <div class="list-group list-group-flush ms-4">
                                @foreach ($vacancy->offers as $offer)
                                    <div class="list-group-item bg-secondary-subtle">{{ $offer->offer }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($vacancy->skills->isNotEmpty())
                        <div class="mb-4 mt-5">
                            <div class="fw-semibold fs-4 mb-3">Ключевые навыки</div>
                            <div class="d-flex flex-wrap">
                                @foreach ($vacancy->skills as $skill)
                                    <div class="bg-white fw-semibold shadow-sm rounded py-2 px-3 me-1 mb-1">
                                        {{ $skill->skill }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($vacancy->address)
                        <div class="mb-4">
                            <div class="fw-semibold fs-3 mb-3">Адрес</div>
                            <div><span class="fw-semibold">{{ $vacancy->city }}, </span>{{ $vacancy->address }}</div>
                        </div>
                    @endif
                    <div class="text-secondary mt-5">Вакансия опубликована {{ $vacancy->created_at }}</div>

                </div>
                <div class="col-lg-4">
                    <div class="card card-body border-0 shadow-sm mb-3">
                        @if ($company->image)
                            <img class="mb-3" src="{{ asset($company->image) }}" alt="">
                        @endif
                        <a class="text-decoration-none"
                            href="{{ route('personal.company.vacancy.list', ['company' => $company->id]) }}">
                            <h4 class="mb-4">{{ $company->legal_title }}</h4>
                        </a>
                        <div class="h5">{{ $company->city->city }}</div>
                    </div>
                    <div class="mb-4">Работа в городе <span class="h5">{{ $vacancy->city }}</span></div>
                    <div class="d-flex">
                        <div
                            @guest data-bs-toggle="tooltip" data-bs-title="Авторизуйтель чтобы была возможность откликнуться на вакансию" @endguest>
                            <button class="btn btn-primary px-4 me-3 create-response @guest disabled @endguest"
                                data-vacancy-id="{{ $vacancy->id }}">Откликнуться</button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Показать контакты
                            </button>
                            <ul class="dropdown-menu">
                                <div class="px-3 py-2">
                                    <div class="mb-3 fw-semibold" style="overflow: auto;white-space: nowrap;">
                                        {{ $vacancy->surname }} {{ $vacancy->name }}
                                    </div>
                                    <div class="mb-1">{{ $vacancy->phone }}</div>
                                    <div>{{ $vacancy->email }}</div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
