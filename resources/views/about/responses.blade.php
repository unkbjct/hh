@extends('layouts.main')

@section('title')
    Как работают отклики
@endsection

@section('main')
    <div class="container">
        <div class="mb-5">
            <h1>Как рабютают отклики?</h1>
            <div class="text-end">
                <h2 class="text-primary">Все очень просто!</h2>
            </div>
        </div>
        <div class="mb-4">
            <div>
                Полсе того как вы зарегистрировались на сайте и авторизовались, вы можете оставить отклик на понравившуюся
                вам
                вакансию, даже если у вас еще нет резюме.
                После отклика на вакансию, вы получаете сообщение об успешном выполение действия:
            </div>
            <div>
                <img src="{{ asset('public/storage/images/response-message-success.png') }}" class="rounded my-4"
                    alt="">
            </div>
            <div class="rounded bg-danger-subtle border-5 border-start border-danger color-white ps-4 py-3 mb-4">
                <div class="fw-bold mb-2">! важно</div>
                <div>На одну вакансию можно откликнуться только один раз в день!</div>
                <div>Поэтому убедитесь что у вас есть опубликованное разюме и оно обладает всей необходимой информацией для
                    работодателя, иначе работодатель пропустит вас.</div>
            </div>
            @if (Auth::check())
                <div class="mb-2">Если у вас еще нет резюме, создайте его</div>
                <a href="{{ route('personal.resume.list') }}" class="btn btn-sm btn-danger">К моим резюме</a>
            @else
                <div class="mb-2">Если у вас еще нет аккаунта, создайте его</div>
                <a href="{{ route('signup') }}" class="btn btn-sm btn-danger mb-2">Создать аккаунт</a>
                <div class="mb-2">или авторизуйтесь</div>
                <a href="{{ route('login') }}" class="btn btn-sm btn-danger">авторизоваться</a>
            @endif
        </div>
    </div>
@endsection
