@extends('layouts.main')

@section('title')
    Личный кабинет - {{ Auth::user()->surname }} {{ Auth::user()->name }}
@endsection

@section('scripts')
    <script>
        $(".input-personal").on("input", function() {
            let isChanged = false;
            let isEmpty = false;
            $(".input-personal").each((index, element) => {
                if ($(element).val() && $(element).val() != $(element).data("old-value")) isChanged = true;
            });
            (isChanged) ? $("#change-personal").removeClass("disabled"): $("#change-personal").addClass("disabled");
        })
        $("#reset-personal").click(() => $("#change-personal").addClass("disabled"))
        $("#change-personal").click(() => {
            $.ajax({
                url: '{{ route('api.personal.edit') }}',
                method: 'POST',
                data: {
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                },
                success: function(e) {
                    location.reload();
                },
                error: function(e) {
                    console.log(e)
                    for (let key in e.responseJSON.errors) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key).parent().find('.invalid-feedback').remove();
                        $("#" + key).parent().append(
                            `<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                    }
                }
            })
        })



        $(".input-password").on("input", function() {
            let isEmpty = true;
            let count = 0;
            $(".input-password").each((index, element) => {
                if ($(element).val()) count++;
            });
            (count == $(".input-password").length) ? $("#change-password").removeClass("disabled"): $(
                "#change-password").addClass("disabled");
        })
        $("#reset-password").click(() => $("#change-password").addClass("disabled"))
        $("#change-password").click(() => {
            $(".alert-password-container").empty();
            $.ajax({
                url: '{{ route('api.password.edit') }}',
                method: 'POST',
                data: {
                    oldPasswd: $("#oldPasswd").val(),
                    newPasswd: $("#newPasswd").val(),
                    confirmPasswd: $("#confirmPasswd").val(),
                },
                success: function(e) {
                    // alert('success')
                    $(".alert-password-container").append('<div class="alert alert-success mb-0" role="alert">Пароль сохранен успешно</div>')
                    $(".input-password").each(function(){
                        this.value = null
                    })
                    $("#change-password").addClass("disabled")
                    // location.reload();
                },
                error: function(e) {
                    console.log(e)
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
        <div class="mb-4">
            <h1 class="h3">Личный кабинет</h1>
            <h5>{{ Auth::user()->surname }} {{ Auth::user()->name }}</h5>
        </div>
        <div class="card card-body border-0 shadow-sm mb-4">
            <form id="form-personal">
                <div class="row gy-4">
                    <div class="col-md-4">
                        <div class="form-floating position-relative">
                            <input data-old-value="{{ Auth::user()->surname }}" type="text"
                                value="{{ Auth::user()->surname }}" class="form-control validation input-personal"
                                id="surname" placeholder="Фамилия">
                            <label for="surname">Фамилия</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input data-old-value="{{ Auth::user()->name }}" type="text" value="{{ Auth::user()->name }}"
                                class="form-control validation input-personal" id="name" placeholder="Имя">
                            <label for="name">Имя</label>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input data-old-value="{{ Auth::user()->email }}" type="text"
                                value="{{ Auth::user()->email }}" class="form-control validation input-personal"
                                id="email" placeholder="Электронная почта">
                            <label for="email">Электронная почта</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input data-old-value="{{ Auth::user()->phone }}" type="text"
                                value="{{ Auth::user()->phone }}" class="form-control validation input-personal"
                                id="phone" placeholder="Телефон">
                            <label for="phone">Телефон</label>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="w-100">
                            <button type="reset" id="reset-personal" class="btn btn-sm btn-outline-danger">Отмена</button>
                            <button type="button" id="change-personal"
                                class="btn btn-sm btn-danger disabled">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card card-body border-0 shadow-sm">
            <form>
                <div class="row gy-4">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="password" class="form-control validation input-password" id="oldPasswd"
                                placeholder="Старый пароль">
                            <label for="oldPasswd">Старый пароль</label>
                        </div>
                    </div>
                    <div class="col-md-4 alert-password-container">
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="password" class="form-control validation input-password" id="newPasswd"
                                placeholder="Новый пароль">
                            <label for="newPasswd">Новый пароль</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="password" class="form-control validation input-password" id="confirmPasswd"
                                placeholder="Подтверждение пароля">
                            <label for="confirmPasswd">Подтверждение пароля</label>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="w-100">
                            <button type="reset" id="reset-password" class="btn btn-sm btn-outline-danger">Отмена</button>
                            <button type="button" id="change-password"
                                class="btn btn-sm btn-danger disabled">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
