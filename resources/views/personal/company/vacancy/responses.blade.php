@extends('layouts.main')

@section('title')
    Отклики - {{ $vacancy->position }}
@endsection

@section('main')
    <div class="container">
        <h1 class="h3 mb-5">Отклики на вакансию {{ $vacancy->position }}</h1>
        <div class="row gy-5 pt-5">
            @forelse ($responses as $response)
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="fw-semibold h4 mb-3">Откликнулся {{ $response->surname }} {{ $response->name }}
                                </div>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                                    data-bs-target="#resumes-{{ $response->responseId }}">Показать резюме
                                    пользователя</button>
                            </div>
                            <div>Почта пользователя <code>{{ $response->email }}</code></div>
                            <div>Телефон пользователя <code>{{ $response->phone }}</code></div>
                            <div class="collapse" id="resumes-{{ $response->responseId }}">
                                <div class="pt-4">
                                    @forelse ($response->resumes as $resume)
                                        <div class="card card-body mb-4">
                                            <div class="fs-4 fw-semibold">Резюме <a
                                                    href="{{ route('resume', ['resume' => $resume->id]) }}"
                                                    class="">{{ $resume->position }}</a></div>
                                            <div class="fw-semibold">{{ $resume->surname }} {{ $resume->name }}</div>
                                        </div>
                                    @empty
                                    <div class="text-center text-secondary h4">У пользователя еще нет созданных резюме</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 text-end">
                            Откликнулись: {{ $response->created_at }}
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
@endsection
