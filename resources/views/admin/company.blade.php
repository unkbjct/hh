@extends('layouts.main')

@section('title')
    Компании - управление
@endsection

@section('main')
    <div class="container">
        <div class="mb-4">
            <h2>Управление компаниями</h2>
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
                                        <label for="" class="form-label fw-semibold">Название компании</label>
                                        <input type="text" name="title" value="{{ old('title') }}"
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
                                            <input class="form-check-input" @if (old('status') && in_array('CONFIRMED', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="CONFIRMED" id="CONFIRMED">
                                            <label class="form-check-label" for="CONFIRMED">
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
                                        <a href="{{ route('admin.company') }}" class="btn btn-sm btn-primary">Сбросить
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
                @forelse ($companies as $company)
                    @switch($company->status)
                        @case('CREATED')
                            <div class="col-lg-6">
                                <div class="card border-warning shadow-sm h-100">
                                    <div class="card-header">на проверке</div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none"
                                            href="{{ route('admin.company.information', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                        <div><code>Компания находится на проверки, дождитесь ее окончания</code></div>
                                    </div>
                                </div>
                            </div>
                        @break

                        @case('CONFIRMED')
                            <div class="col-lg-6">
                                <div class="card border-primary shadow-sm h-100">
                                    <div class="card-header fw-semibold">опубликована</div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none"
                                            href="{{ route('admin.company.information', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @break

                        @case('CANCELED')
                            <div class="col-lg-6">
                                <div class="card border-danger shadow-sm h-100">
                                    <div class="card-header text-danger">отменена</div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none"
                                            href="{{ route('admin.company.information', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                        <div>Причина отмены: {{ $company->cancel_text }}</div>
                                    </div>
                                </div>
                            </div>
                        @break
                    @endswitch

                    @empty
                        <div class="col-lg-12">
                            <div class="text-muted text-center h4">По заданным фильтрам ничего не найдено</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    @endsection
