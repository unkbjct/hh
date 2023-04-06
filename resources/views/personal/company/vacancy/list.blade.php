@extends('layouts.main')

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
                            <p>Нет открытых ваканский</p>
                        </div>
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
                                <a href="{{ route('personal.company.vacancy.create', ['company' => $company->id]) }}"
                                    class="btn btn-primary btn-sm">Добавить вакансию</a>
                            </div>
                            <div class="text-center text-secondary fs-5 py-5">На данный момент у компании нет открытых
                                вакансий</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </section>
    </div>
@endsection
