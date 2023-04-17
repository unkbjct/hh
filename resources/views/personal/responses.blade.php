@extends('layouts.main')

@section('title')
    Мои отклики
@endsection

@section('main')
    <div class="container">
        <h1 class="mb-5">Мои отклики</h1>
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
                                    class="btn btn-outline-danger favorite-btn @if (Cookie::get('favorite_vacancy') && in_array($vacancy->id, json_decode(Cookie::get('favorite_vacancy')))) active @endif"
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
                    <div class="text-center py-4 text-muted h4">Вы еще не откликались ни на одну вакансию</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
