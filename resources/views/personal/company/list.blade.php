@extends('layouts.main')

@section('main')
    <div class="container">
        <section>
            <div class="mb-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                    <div class="h2">Компании</div>
                    <a href="{{ route('personal.company.create') }}" class="btn btn-primary btn-sm">Добавить компанию на
                        сайт</a>
                </div>
                <div>Для того чтобы создать вакансию, вам необходимо сначала создать компанию.</div>
            </div>
            <div class="row gy-4">
                @forelse ($companies as $company)
                    <div class="col-lg-6">
                        @switch($company->status)
                            @case('CONFIRMED')
                                <div class="card border-primary shadow-sm h-100">
                                    <div class="card-header fw-semibold">Компания опубликована</div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none "
                                            href="{{ route('personal.company.vacancy.list', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                        <div>
                                            <a href="{{ route('personal.company.edit', ['company' => $company->id]) }}"
                                                class="btn btn-primary btn-sm"> Редактировать</a>
                                            <a href="{{ route('personal.company.vacancy.list', ['company' => $company->id]) }}"
                                                class="btn btn-danger btn-sm">Вакансий: {{ $company->count }}</a>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @case('CREATED')
                                <div class="card border-warning shadow-sm h-100">
                                    <div class="card-header"><code>Компания находится на проверки, дождитесь ее окончания</code>
                                    </div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none  disabled "
                                            href="{{ route('personal.company.vacancy.list', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                    </div>
                                </div>
                            @break

                            @case('CANCELED')
                                <div class="card border-danger shadow-sm h-100">
                                    <div class="card-header text-danger">отменена</div>
                                    <div class="card-body">
                                        <a class="btn btn-link text-decoration-none"
                                            href="{{ route('admin.company.information', ['company' => $company->id]) }}">
                                            <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                        </a>
                                        <div class="mb-2">Причина отмены: {{ $company->cancel_text }}</div>
                                        <div>
                                            <a href="{{ route('personal.company.edit', ['company' => $company->id]) }}"
                                                class="btn btn-sm btn-primary">Редактировать компанию</a>
                                            <button class="btn btn-sm btn-danger">Удалить компанию</button>
                                        </div>
                                    </div>
                                </div>
                            @break

                            @default
                        @endswitch

                    </div>
                    @empty
                    @endforelse
                </div>
            </section>
        </div>
    @endsection
