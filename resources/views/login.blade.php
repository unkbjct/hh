@extends('layouts.main')

@section('title')
    Авторизация
@endsection

@section('scripts')
    <script>
        $("#btn-submit").click(() => {
            $.ajax({
                url: '{{ route('api.login') }}',
                method: 'POST',
                data: {
                    login: $("#login").val(),
                    passwd: $("#passwd").val(),
                },
                success: function(e) {
                    window.location = e.data.url
                },
                error: function(e) {
                    for (let key in e.responseJSON.errors) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key).parent().find('.invalid-feedback').remove();
                        $("#" + key).parent().append(`<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                    }
                }
            })
        })
    </script>
@endsection

@section('main')
    <div class="container">
        <div class="mx-auto" style="max-width: 500px;">
            <h1 class="mb-5">Авторизация</h1>
            <div class="">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control validation" id="login" placeholder="Почта или телефон">
                    <label for="login">Почта или телефон</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control validation" id="passwd" placeholder="Пароль">
                    <label for="passwd">Пароль</label>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" id="btn-submit">Войти</button>
                </div>
            </div>
        </div>
    </div>
@endsection
