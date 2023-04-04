@extends('layouts.main')

@section('title')
    Мои резюме
@endsection

@section('scripts')
    <script>
        $("#resume-create").click(() => {
            $.ajax({
                url: '{{ route('api.resume.create') }}',
                method: 'POST',
                success: function(e) {
                    console.log(e)
                    window.location = e.data.url;
                },
                error: function(e) {
                    console.log(e)
                }
            })
        })

        $(".remove-resume").click(function() {
            $.ajax({
                url: "{{ route('api.resume.remove') }}",
                method: 'POST',
                data: {
                    resumeId: this.dataset.resumeId,
                    token: "{{ Auth::user()->api_token }}",
                },
                success: function(e) {
                    location.reload();
                    console.log(e)
                },
                error: function(e) {
                    console.log(e)
                    alert('Что-то пошло не так \nПопробуйте позже')
                }
            })
        })
    </script>
@endsection

@section('main')
    <div class="container">
        <div class="row gy-4 mb-5">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="d-flex flex-wrap">
                    <div class="me-auto">
                        <h1 class="h3">Мои резюме</h1>
                    </div>
                    <div>
                        <button id="resume-create" class="btn btn-danger btn-sm">Создать резюме</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @forelse ($resumesList as $resume)
                <div class="col-md-2"></div>
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    @switch($resume->status)
                                        @case('CREATED')
                                            <div class="mb-2">
                                                @if ($resume->job)
                                                    <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                        class="secondary-title-link text-decoration-none fs-4">{{ $resume->job->title }}</a>
                                                @else
                                                    <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                        class="secondary-title-link text-decoration-none fs-4">Должность не
                                                        указана</a>
                                                @endif
                                            </div>
                                            <div class="mb-2">
                                                <div class="fw-semibold">Резюме не опубликовано</div>
                                            </div>
                                            <div class="mb-2">
                                                <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                    class="btn btn-sm btn-primary me-3">Дополнить резюме</a>
                                                <a data-resume-id="{{ $resume->id }}"
                                                    class="btn btn-sm btn-danger remove-resume">Удалить</a>
                                            </div>
                                        @break

                                        @case('EXPECTING')
                                            <div class="mb-2">
                                                @if ($resume->job)
                                                    <a
                                                        class="secondary-title-link text-decoration-none fs-4">{{ $resume->job->title }}</a>
                                                @else
                                                    <a class="secondary-title-link text-decoration-none fs-4">Должность не
                                                        указана</a>
                                                @endif
                                            </div>
                                            <div class="">
                                                <div class="fw-semibold text-danger">Резюме проходит проверку, дождитесь ее
                                                    окончания</div>
                                            </div>
                                        @break

                                        @case('PUBLISHED')
                                            <div class="mb-2">
                                                <a href="{{ route('resume', ['resume' => $resume->id]) }}"
                                                    class="text-decoration-none fw-semibold fs-4 color-primary">{{ $resume->job->title }}</a>

                                            </div>
                                            <div class="mb-2">
                                                <div class="fw-semibold">Резюме опубликовано</div>
                                            </div>
                                            <div class="mb-2">
                                                <a href="{{ route('resume', ['resume' => $resume->id]) }}"
                                                    class="btn btn-sm btn-primary me-2">Посмотреть</a>
                                                <button class="btn btn-sm btn-outline-primary me-2">23432 Вакансий</button>
                                                <button class="btn btn-sm btn-outline-primary">12 Просмотров</button>
                                            </div>
                                            <div>
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
                                            </div>
                                        @break

                                    @break;

                                    @default
                                @endswitch
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top-0">
                        <div class="d-flex">
                            <div class="ms-auto text-muted">Создано: {{ $resume->created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
            @empty
            @endforelse

        </div>
    </div>
@endsection
