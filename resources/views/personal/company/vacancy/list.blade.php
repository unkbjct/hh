@extends('layouts.main')

@section('title')
    {{ $company->legal_title }}
@endsection

@section('scripts')
    <script>
        // alert(123)
        $(".btn-visibility").click(function() {
            $.ajax({
                url: "{{ route('api.company.vacancy.visibility', ['company' => $company->id]) }}",
                method: 'POST',
                data: {
                    vacancyId: this.dataset.vacancyId,
                    visibility: this.dataset.visibility,
                },
                success: function(e) {
                    console.log(e)
                    location.reload();
                },
                error: function(e) {
                    console.log(e)
                    alert("Что-то пошло не так \nПопробуйте позже");
                }
            })
        })

        $(".btn-remove").click(function() {
            $.ajax({
                url: "{{ route('api.company.vacancy.remove', ['company' => $company->id]) }}",
                method: 'POST',
                data: {
                    vacancyId: this.dataset.vacancyId,
                },
                success: function(e) {
                    console.log(e)
                    location.reload();
                },
                error: function(e) {
                    console.log(e)
                    alert("Что-то пошло не так \nПопробуйте позже");
                }
            })

        })
    </script>
@endsection

@section('main')
    <div class="container">
        <section>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div class="card card-body border-0 bg-white shadow-sm">
                        <div class="mb-4">
                            <img class="w-100" src="{{ asset($company->image) }}" alt="">
                        </div>
                        <div class="mb-4 h5">{{ $company->city->city }}</div>
                        <div class="mb-4">
                            <div class="fw-semibold">Вакансии</div>
                            @if ($company->count)
                                <p>Открытых вакансий: {{ $company->count }}</p>
                            @else
                                <p>Нет открытых ваканский</p>
                            @endif
                        </div>
                        @if (Auth::check() && in_array(Auth::user()->id, $company->users))
                            <a href="{{ route('personal.company.edit', ['company' => $company->id]) }}">Редактировать</a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8">
                    <div>
                        <div>
                            <div class="h2 mb-3">{{ $company->legal_title }}</div>
                            <div>{{ $company->description }}</div>
                        </div>
                        <hr class="my-5">
                        <div>
                            <div class="d-flex flex-wrap mb-4">
                                <div class="h5 me-auto">Вакансии компании</div>
                                @if (Auth::check() && in_array(Auth::user()->id, $company->users))
                                    <a href="{{ route('personal.company.vacancy.create', ['company' => $company->id]) }}"
                                        class="btn btn-primary btn-sm">Добавить вакансию</a>
                                @endif
                            </div>
                            @forelse ($vacancies as $vacancy)
                                @switch($vacancy->status)
                                    @case('CREATED')
                                        @if (!Auth::check() || !in_array(Auth::user()->id, $company->users))
                                        @break
                                    @endif

                                    <div class="card shadow-sm border border-opacity-5 border-warning mb-3">
                                        <div class="card-header"><code>Вакансия проходит проверку</code>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap mb-1">
                                                <div class="me-auto">
                                                    <a
                                                        class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                                </div>
                                                <div class="mb-1 fs-4 fw-semibold">
                                                    {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                            </div>
                                            <div>
                                                <p>Дождитесь окончания проверки</p>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('PUBLISHED')
                                    <div class="card shadow-sm border border-opacity-5 border-primary mb-3">
                                        <div class="card-header">
                                            <div class="fw-semibold">Вакансия опубликована</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap">
                                                <div class="me-auto">
                                                    <a href="{{ route('personal.company.vacancy.vacancy', [
                                                        'company' => $company->id,
                                                        'vacancy' => $vacancy->id,
                                                    ]) }}"
                                                        class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                                </div>
                                                <div class="mb-3 fs-4 fw-semibold">
                                                    {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                            </div>
                                            <div class="fw-semibold mb-3">
                                                {{ $vacancy->city }}
                                            </div>

                                            @if (Auth::check() && in_array(Auth::user()->id, $company->users))
                                                <div class="d-flex flex-wrap">
                                                    <a href="{{ route('personal.company.vacancy.responses', [
                                                        'company' => $company->id,
                                                        'vacancy' => $vacancy->id,
                                                    ]) }}"
                                                        class="btn btn-outline-danger btn-sm me-2">Откликов:
                                                        {{ $vacancy->responses }}</a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            Изменить видимость
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item btn-visibility"
                                                                    data-vacancy-id="{{ $vacancy->id }}"
                                                                    data-visibility="show">Показывать резюме</button></li>
                                                            <li><button class="dropdown-item btn-visibility"
                                                                    data-vacancy-id="{{ $vacancy->id }}"
                                                                    data-visibility="hidden">Скрывать резюме</button></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @break

                                @case('HIDDEN')
                                    @if (!Auth::check() || !in_array(Auth::user()->id, $company->users))
                                    @break
                                @endif

                                <div class="card shadow-sm border border-opacity-5 border-secondary mb-3">
                                    <div class="card-header">
                                        <div class="fw-semibold">Вакансия скрыта для всех</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap mb-2">
                                            <div class="me-auto">
                                                <a href="{{ route('personal.company.vacancy.vacancy', [
                                                    'company' => $company->id,
                                                    'vacancy' => $vacancy->id,
                                                ]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 text-secondary">{{ $vacancy->position }}</a>
                                            </div>
                                            <div class="mb-3 fs-4 fw-semibold">
                                                {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <a href="{{ route('personal.company.vacancy.responses', [
                                                'company' => $company->id,
                                                'vacancy' => $vacancy->id,
                                            ]) }}"
                                                class="btn btn-outline-danger btn-sm me-2">Откликов:
                                                {{ $vacancy->responses }}</a>
                                            <button class="btn btn-outline-danger btn-sm me-2 btn-remove"
                                                data-vacancy-id="{{ $vacancy->id }}">Удалить вакансию</button>
                                            <div class="dropdown">
                                                <button class="btn btn-danger btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Изменить видимость
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><button class="dropdown-item btn-visibility"
                                                            data-vacancy-id="{{ $vacancy->id }}"
                                                            data-visibility="show">Показывать резюме</button></li>
                                                    <li><button class="dropdown-item btn-visibility"
                                                            data-vacancy-id="{{ $vacancy->id }}"
                                                            data-visibility="hidden">Скрывать резюме</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('CANCELED')
                                @if (!Auth::check() || !in_array(Auth::user()->id, $company->users))
                                @break
                            @endif
                            <div class="card shadow-sm border border-opacity-5 border-danger mb-3">
                                <div class="card-header">
                                    <div class="fw-semibold text-danger">Вакансия не прошла проверку</div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap mb-1">
                                        <div class="me-auto">
                                            <a href="{{ route('personal.company.vacancy.edit', [
                                                'company' => $vacancy->company,
                                                'vacancy' => $vacancy->id,
                                            ]) }}"
                                                class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                        </div>
                                        <div class="mb-1 fs-4 fw-semibold">
                                            {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                    </div>
                                    <div class="mb-2">Причина отмены: {{ $vacancy->cancel_text }}</div>
                                    <div><a href="{{ route('personal.company.vacancy.edit', [
                                        'company' => $vacancy->company,
                                        'vacancy' => $vacancy->id,
                                    ]) }}"
                                            class="btn btn-sm btn-outline-primary">Редактировать вакансию</a>
                                        <button class="btn btn-outline-danger btn-sm me-2 btn-remove"
                                            data-vacancy-id="{{ $vacancy->id }}">Удалить вакансию</button>
                                    </div>
                                </div>
                            </div>
                        @break

                        @default
                    @endswitch
                    @empty
                        <div class="text-center text-secondary fs-5 py-5">На данный момент у компании нет открытых
                            вакансий</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
</section>
</div>
@endsection
