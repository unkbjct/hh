@extends('layouts.main')

@section('title')
    Создание аккаунта
@endsection

@section('scripts')
    <script>
        $("#btn-submit").click(() => {
            $.ajax({
                url: '{{ route('api.signup') }}',
                method: 'POST',
                data: {
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    passwd: $("#passwd").val(),
                    confirmPasswd: $("#confirmPasswd").val()
                },
                success: function(e) {
                    window.location = e.data.url
                },
                error: function(e) {
                    for (let key in e.responseJSON.errors) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key).parent().find('.invalid-feedback').remove();
                        $("#" + key).parent().append(
                            `<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                    }
                }
            })
        })
    </script>
@endsection

@section('main')
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <h1 class="mb-4">Регистрация соискателя</h1>
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control validation" id="name" placeholder="Имя">
                            <label for="name">Имя</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control validation" id="surname" placeholder="Фамилия">
                            <label for="surname1">Фамилия</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control validation" id="email" placeholder="почта">
                            <label for="email">Почта</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control validation" id="phone" placeholder="Телефон">
                            <label for="phone">Телефон</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control validation" id="passwd" placeholder="Пароль">
                            <label for="passwd">Пароль</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control validation" id="confirmPasswd"
                                placeholder="Подтверждение пароля">
                            <label for="confirmPasswd">Подтверждение пароля</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" id="btn-submit">Создать аккаунт</button>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="h-100 rounded shadow"
                    style="background-image: url({{asset('public/storage/images/qwe.png')}});background-size: cover; background-position: center;">
                </div>
            </div>
        </div>
    </div>
@endsection
