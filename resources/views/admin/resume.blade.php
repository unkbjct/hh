@extends('layouts.main')

@section('title')
    Резюме - управление
@endsection

@section('main')
    <div class="container">
        <div class="mb-4">
            <h2>Управление Резюме</h2>
        </div>
        <section class="mb-5">
            <div>
                <button class="btn btn-danger btn-sm mb-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Фильтры
                </button>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body border-0 shadow-sm">
                        <form action="">
                            <div class="row gy-4">
                                <div class="col-lg-3">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Название должности</label>
                                        <input type="text" name="position" value="{{ old('position') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Статус заявки</label>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('EXPECTING', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="EXPECTING" id="EXPECTING">
                                            <label class="form-check-label" for="EXPECTING">
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
                                <div class="col-lg-3">
                                    <div>
                                        <label for="name" class="form-label fw-semibold">Имя или Фамилия указанные в
                                            резюме</label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control">
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
                                        <a href="{{ route('admin.resume') }}" class="btn btn-sm btn-primary">Сбросить
                                            фильтры</a>
                                        <button class="btn btn-sm btn-primary">Применить</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row gy-4">
                @forelse ($resumes as $resume)
                    @switch($resume->status)
                        @case('EXPECTING')
                            <div class="col-lg-6">
                                <div class="card border-warning">
                                    <div class="card-header border-0"><code>Резюме на проверке</code></div>
                                    <div class="card-body">
                                        @if ($resume->job)
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="text-decoration-none fs-4">{{ $resume->job->title }}</a>
                                        @else
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="text-decoration-none fs-4">Должность не
                                                указана</a>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex">
                                            <div class="ms-auto text-muted">Создано: {{ $resume->created_at }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @break

                        @case('PUBLISHED')
                            <div class="col-lg-6">
                                <div class="card border-primary">
                                    <div class="card-header border-0 fw-semibold">Резюме на проверке</div>
                                    <div class="card-body">
                                        @if ($resume->job)
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="text-decoration-none fs-4">{{ $resume->job->title }}</a>
                                        @else
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="text-decoration-none fs-4">Должность не
                                                указана</a>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex">
                                            <div class="ms-auto text-muted">Создано: {{ $resume->created_at }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @break

                        @case('CANCELED')
                            <div class="col-lg-6">
                                <div class="card border-danger">
                                    <div class="card-header border-0 text-danger">Резюме не прошло проверку</div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            @if ($resume->job)
                                                <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                    class="text-decoration-none fs-4">{{ $resume->job->title }}</a>
                                            @else
                                                <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                    class="text-decoration-none fs-4">Должность не
                                                    указана</a>
                                            @endif
                                        </div>
                                        <div>Причина отмены: {{ $resume->cancel_text }}</div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex">
                                            <div class="ms-auto text-muted">Создано: {{ $resume->created_at }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @break

                        @default
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
