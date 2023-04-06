@extends('layouts.main')

@section('main')
    <div class="container">
        <section>
            <div class="mb-5">
                <div class="h2">Компании</div>
                <a href="{{ route('personal.company.create') }}" class="btn btn-info">Создать компанию</a>
                <div>Для того чтобы создать вакансию, вам необходимо сначала создать компанию.</div>
            </div>
            <div class="row gy-4">
                @forelse ($companies as $company)
                    <div class="col-lg-6">
                        <div class="card card-body @if ($company->status == 'CREATED') border-warning @else border-primary @endif shadow-sm h-100">
                            <div>
                                <a class="btn btn-link text-decoration-none @if ($company->status == 'CREATED') disabled @endif"
                                    href="{{route('personal.company.vanancy.list', ['company' => $company->id])}}">
                                    <h4 class="text-primary">{{ $company->legal_title }}</h4>
                                </a>
                                @if ($company->status == 'CREATED')
                                    <div><code>Компания находится на проверки, дождитесь ее окончания</code></div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </section>
    </div>
@endsection
