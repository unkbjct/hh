@extends('layouts.main')

@section('title')
    Вакансии - управдение
@endsection

@section('main')
    <div class="container">
        <div class="mb-4">
            <h2>Управление Вакансиями</h2>
        </div>
        <section class="mb-5">
            <div>
                <button class="btn btn-danger btn-sm mb-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Фильтры
                </button>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body border-0 shadow-sm">
                        <form action="" method="get">
                            <div class="row gy-4">
                                <div class="col-lg-3">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Должность</label>
                                        <input type="text" name="position" value="{{ old('position') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Статус заявки</label>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('CREATED', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="CREATED" id="CREATED">
                                            <label class="form-check-label" for="CREATED">
                                                На проверке
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('PUBLISHED', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="PUBLISHED" id="PUBLISHED">
                                            <label class="form-check-label" for="PUBLISHED">
                                                Опубликована
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('CANCELED', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="CANCELED" id="CANCELED">
                                            <label class="form-check-label" for="CANCELED">
                                                Отмененно
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Сортировать по</label>
                                        <select class="form-select" name="sort" aria-label="Default select example">
                                            <option selected value="">-</option>
                                            <option @if (old('sort') && old('sort') == 'created_at|desc') selected @endif
                                                value="created_at|desc">
                                                Сначала новые</option>
                                            <option @if (old('sort') && old('sort') == 'created_at|asc') selected @endif
                                                value="created_at|asc">Сначала старые</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div>
                                        <a href="{{ route('admin.vacancy') }}" class="btn btn-sm btn-primary">Сбросить
                                            фильтры</a>
                                        <button class="btn btn-sm btn-primary">Применить</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </section>
        <section>
            <div class="row gy-4">
                @forelse ($vacancies as $vacancy)
                    <div class="col-lg-6">
                        @switch($vacancy->status)
                            @case('CREATED')
                                <div class="card shadow-sm border border-opacity-5 border-warning mb-3">
                                    <div class="card-header"><code>Вакансия проходит проверку</code>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap mb-2">
                                            <div class="me-auto">
                                                <a href="{{ route('personal.company.vacancy.vacancy', [
                                                    'company' => $vacancy->company,
                                                    'vacancy' => $vacancy->id,
                                                ]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                            </div>
                                            <div class="mb-3 fs-4 fw-semibold">
                                                {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
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
                                        <div class="d-flex flex-wrap mb-2">
                                            <div class="me-auto">
                                                <a href="{{ route('personal.company.vacancy.vacancy', [
                                                    'company' => $vacancy->company,
                                                    'vacancy' => $vacancy->id,
                                                ]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                            </div>
                                            <div class="mb-3 fs-4 fw-semibold">
                                                {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('HIDDEN')
                                <div class="card shadow-sm border border-opacity-5 border-primary mb-3">
                                    <div class="card-header">
                                        <div class="fw-semibold">Вакансия опубликована</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap mb-2">
                                            <div class="me-auto">
                                                <a ref="{{ route('personal.company.vacancy.vacancy', [
                                                    'company' => $vacancy->company,
                                                    'vacancy' => $vacancy->id,
                                                ]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                            </div>
                                            <div class="mb-3 fs-4 fw-semibold">
                                                {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('CANCELED')
                                <div class="card shadow-sm border border-opacity-5 border-danger mb-3">
                                    <div class="card-header">
                                        <div class="fw-semibold text-danger">Вакансия не прошла проверку</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap mb-1">
                                            <div class="me-auto">
                                                <a href="{{ route('personal.company.vacancy.vacancy', [
                                                    'company' => $vacancy->company,
                                                    'vacancy' => $vacancy->id,
                                                ]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 color-primary">{{ $vacancy->position }}</a>
                                            </div>
                                            <div class="mb-1 fs-4 fw-semibold">
                                                {{ number_format($vacancy->salary, 0, '-', ' ') }} руб.</div>
                                        </div>
                                        <div>Причина отмены: {{$vacancy->cancel_text}}</div>
                                    </div>
                                </div>
                            @break

                            @default
                        @endswitch
                    </div>

                    @empty
                        <div class="col-lg-12">
                            <div class="text-muted text-center h4">По заданным фильтрам ничего не найдено</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    @endsection
