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
                    window.location = e.data.url;
                },
                error: function(e) {
                    console.log(e)
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
                            <div class="d-flex align-items-center mb-4">
                                <div>
                                    <div class="mb-2">
                                        @if ($resume->specifity)
                                            <a href="http://google.com?q="
                                                class="title-link text-decoration-none fs-4">{{ $resume->specifity }}</a>
                                        @else
                                            <a href="http://google.com?q="
                                                class="secondary-title-link text-decoration-none fs-4">Должность не
                                                указана</a>
                                        @endif
                                    </div>
                                    @if ($resume->status == 'CREATED')
                                        <div class="mb-2">
                                            <div class="fw-semibold">Резюме не опубликовано</div>
                                        </div>
                                        <div class="mb-2">
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="btn me-2 btn-primary btn-sm">Дополнить резюме</a>
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="btn me-2 btn-outline-primary btn-sm">232032 ваканский</a>
                                        </div>
                                        <div>
                                            <a href="{{ route('personal.resume.applicant', ['resume' => $resume->id]) }}"
                                                class="link-danger">Удалить</a>
                                        </div>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-4 text-muted">Возраст - 36 лет</div>
                                <div class="me-4 text-muted">Опыт работы - 2 года</div>
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
