@extends('layouts.main')

@section('title')
    Создание нового резюме
@endsection

@if (Auth::user()->status != 'ADMIN')
    @section('scripts')
        <script>
            let resumeId = {{ $resume->id }}
            let token = '{{ Auth::user()->api_token }}'

            $(".input-resume").on("change", function() {
                if (this.name == 'has_car') {
                    this.value = (this.checked) ? 1 : 0;
                }
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $(".input-personal").on("change", function() {
                if (this.name != 'patronymic' && !this.value) return;
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit.personal') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $(".input-contacts").on("change", function() {
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit.contacts') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $(".input-job").on("change", function() {
                if (!this.value) return;
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit.job') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $(".input-has-experience").on("change", function() {
                (this.value == 1) ? $(".hasnt-experience").removeClass("visually-hidden"): $(".hasnt-experience")
                    .addClass("visually-hidden");
                if (!this.value) return;
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit.experience') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $("#add-experience-item").click(function() {
                $.ajax({
                    url: "{{ route('api.resume.edit.experience.item') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        epxerienceItemId: this.dataset.itemId,
                        company: $("#company").val(),
                        position: $("#position").val(),
                        responsibilities: $("#responsibilities").val(),
                        start_day: $("#start_day").val(),
                        start_year: $("#start_year").val(),
                        end_day: $("#end_day").val(),
                        end_year: $("#end_year").val(),
                    },
                    success: function(e) {
                        console.log(e)
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

            $(".edit-experience-item").click(function() {
                let item = JSON.parse(this.dataset.item);
                for (let key in item) {
                    console.log(key)
                    if (key == 'id') $("#add-experience-item").attr("data-item-id", item[key])
                    $("#" + key).val(item[key])
                    // console.log(item[key])
                }
            })

            $(".remove-experience-item").click(function() {
                let id = JSON.parse(this.dataset.itemId);
                $.ajax({
                    url: "{{ route('api.resume.edit.experience.item.remove') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        epxerienceItemId: this.dataset.itemId,
                    },
                    success: function(e) {
                        console.log(e)
                        location.reload();
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $("#btn-add-experience-item").click(function() {
                $("#experience-item").find("input").each((index, element) => {
                    element.value = null;
                })
                $("#experience-item").find("textarea").each((index, element) => {
                    element.value = null;
                })
                $("#experience-item").find("select").each((index, element) => {
                    element.value = null;
                })
            })


            $("#education_level").on("change", function() {
                (this.value == 'secondary') ? $(".education-hidden").addClass("visually-hidden"): $(".education-hidden")
                    .removeClass("visually-hidden");
            })

            $("#add-education-item").click(function() {
                $.ajax({
                    url: "{{ route('api.resume.edit.education.item') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                    },
                    success: function(e) {
                        console.log(e)
                        location.reload();
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $(".input-education-item").on("change", function() {
                console.log(this.name, this.value)
                $.ajax({
                    url: "{{ route('api.resume.edit.education.item.edit') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        educationItemId: this.dataset.educationId,
                        [this.name]: this.value,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $(".remove-education-item").click(function() {
                $.ajax({
                    url: "{{ route('api.resume.edit.education.item.remove') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        educationItemId: this.dataset.educationId,
                    },
                    success: function(e) {
                        console.log(e)
                        location.reload();
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $("#resume-publish").click(function() {
                $.ajax({
                    url: "{{ route('api.resume.publish') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                    },
                    success: function(e) {
                        const publishedModal = new bootstrap.Modal(document.getElementById(
                            'published-modal'))
                        document.getElementById('published-modal').addEventListener("hidden.bs.modal",
                            function() {
                                window.location = e.data.url
                            })
                        publishedModal.show();
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $(".append-item").click(function() {
                $("." + this.dataset.view).removeClass("visually-hidden")
                $("." + this.dataset.view).appendTo("#append-list")
                $(this).addClass("visually-hidden")
            })

            $(".btn-hide-additional").click(function() {
                $("#" + this.dataset.btnId).removeClass("visually-hidden")
                $("." + this.dataset.view).addClass("visually-hidden")

                let url = (this.dataset.urlClear === 'has_car') ? "{{ route('api.resume.edit') }}" : this.dataset
                    .urlClear;
                let data = (this.dataset.urlClear === 'has_car') ? {
                    resumeId: resumeId,
                    token: token,
                    has_car: 0,
                } : {
                    resumeId: resumeId,
                    token: token,
                };
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                    },
                    success: function(e) {
                        location.reload();
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })

            })

            $(".input-employments").on('change', function() {
                let array = [];
                $(".input-employments").each((index, element) => {
                    if (element.checked) array.push(element.value)
                })
                console.log(array)
                $.ajax({
                    url: "{{ route('api.resume.edit.employments') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        employments: array,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })


            $(".input-drive-category").on('change', function() {
                let array = [];
                $(".input-drive-category").each((index, element) => {
                    if (element.checked) array.push(element.value)
                })
                console.log(array)
                $.ajax({
                    url: "{{ route('api.resume.edit.driving-categories') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        categories: array,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $(".input-schedules").on('change', function() {
                let array = [];
                $(".input-schedules").each((index, element) => {
                    if (element.checked) array.push(element.value)
                })
                console.log(array)
                $.ajax({
                    url: "{{ route('api.resume.edit.schedules') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        schedules: array,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })

            $("#add-skill").click(function() {
                let value = $("#input-skill").val();
                let repeat = false;
                $(".skills-item").each((index, element) => {
                    if ($(element).children()[0].textContent == value) repeat = true;
                })
                if (repeat) return;

                $("#skills-list").prepend(
                    `<div class="skills-item shadow-sm bg-white mb-1 me-1 p-2 rounded d-flex"><div class="me-2">${$("#input-skill").val()}</div><button type="button" id="add-skill" class="btn-close" aria-label="Close"></button></div>`
                )

                let skills = [];
                $(".skills-item").each((index, element) => {
                    skills.push($(element).children()[0].textContent)
                })

                $.ajax({
                    url: "{{ route('api.resume.edit.skills') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        skills: skills,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })

                // console.log()

            })

            $("#skills-list").click(function(e) {
                if (!e.target.classList.contains("btn-close")) return;
                $(e.target).parent().remove()

                let skills = [];
                $(".skills-item").each((index, element) => {
                    skills.push($(element).children()[0].textContent)
                })

                $.ajax({
                    url: "{{ route('api.resume.edit.skills') }}",
                    method: 'POST',
                    data: {
                        resumeId: resumeId,
                        token: token,
                        skills: skills,
                    },
                    success: function(e) {
                        console.log(e)
                    },
                    error: function(e) {
                        console.log(e)
                        alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })
        </script>
    @endsection
@else
    @section('scripts')
        <script>
            $("#action").on("change", function() {
                let value = this.value
                if (value === 'PUBLISHED') {
                    $("#cancel-view").addClass("visually-hidden")
                } else {
                    $("#cancel-view").removeClass("visually-hidden")
                }
            })

            $("#btn-confirm").click(function() {
                $.ajax({
                    url: "{{ route('api.admin.resume.edit', ['resume' => $resume->id]) }}",
                    method: 'POST',
                    data: {
                        status: $("#action").val(),
                        cancelText: $("#cancelText").val(),
                    },
                    success: function(e) {
                        console.log(e)
                        window.location = "{{ route('admin.resume') }}"
                    },
                    error: function(e) {
                        console.log(e)
                        for (let key in e.responseJSON.errors) {
                            $("#" + key).addClass("is-invalid");
                            $("#" + key).parent().find('.invalid-feedback').remove();
                            $("#" + key).parent().append(
                                `<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                        }
                        // alert('Что-то пошло не так \nПопробуйте позже')
                    }
                })
            })
        </script>
    @endsection

@endif

@section('main')
    @if (Auth::user()->status == 'ADMIN')
        <div class="container mb-5">
            <a href="{{ route('admin.resume') }}" class="btn btn-outline-danger btn-sm mb-4">Назад</a>
            <section>
                <p>Изучите данное резюме, после либо опубликуйте, либо отмените публикацию и укажите причину отмены</p>
                <div class="row gy-4">
                    <div class="col-lg-12">
                        <div>
                            <label for="action" class="form-label">Действие</label>
                            <select class="form-select" id="action">
                                <option value="PUBLISHED">Подтвердить публикацию</option>
                                <option value="CANCELED">Отменить публикацию</option>
                            </select>
                        </div>
                    </div>
                    <div id="cancel-view" class="col-lg-12 visually-hidden">
                        <div>
                            <label for="action" class="form-label">Укажите причину отмены</label>
                            <textarea id="cancelText" class="form-control validation"
                                placeholder="Укажите по какой именно причине вы отменяете публикацию резюме" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <button class="btn btn-danger" id="btn-confirm">Подтвердить действие</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
    <div class="container @if (Auth::user()->status == 'ADMIN') bg-white opacity-75 pe-none p-2 rounded @endif">
        <div class="d-flex flex-wrap mb-4">
            <div class="me-auto">
                <h1 class="h2">
                    @if (Auth::user()->status == 'ADMIN')
                        Информация о резюме
                    @else
                        Ваше резюме
                    @endif
                </h1>
            </div>
        </div>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Персональный данные</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Имя</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="name" name="name" value="{{ $resume->personal->name }}"
                        class="form-control input-personal">
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Фамилия</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="surname" name="surname" value="{{ $resume->personal->surname }}"
                        class="form-control input-personal">
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Отчество</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="patronymic" name="patronymic" value="{{ $resume->personal->patronymic }}"
                        placeholder="Не обязательно" class="form-control input-personal">
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Город проживания</div>
                </div>
                <div class="col-lg-3">
                    <div class="position-relative">
                        <input type="hidden" class="input-personal" name="city" id="city">
                        <input type="text" class="form-control validation" value="{{$resume->personal->city}}" id="city-form-input">
                        <div class="list-group position-absolute w-100 z-3">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Дата рождения</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" id="birthdayDay" name="birthdayDay"
                            @if ($resume->personal->birthday_day) value="{{ $resume->personal->birthday_day }}" @endif
                            placeholder="День" class="form-control input-personal">
                        <select name="birthdayMonth" placeholde="Месяц" id="birthdayMonth"
                            class="form-control input-personal">
                            <option value="" disabled selected>Месяц</option>
                            <option @if ($resume->personal->birthday_month == '1') selected @endif value="1">Январь</option>
                            <option @if ($resume->personal->birthday_month == '2') selected @endif value="2">Февраль</option>
                            <option @if ($resume->personal->birthday_month == '3') selected @endif value="3">Март</option>
                            <option @if ($resume->personal->birthday_month == '4') selected @endif value="4">Апрель</option>
                            <option @if ($resume->personal->birthday_month == '5') selected @endif value="5">Май</option>
                            <option @if ($resume->personal->birthday_month == '6') selected @endif value="6">Июнь</option>
                            <option @if ($resume->personal->birthday_month == '7') selected @endif value="7">Июль</option>
                            <option @if ($resume->personal->birthday_month == '8') selected @endif value="8">Август</option>
                            <option @if ($resume->personal->birthday_month == '9') selected @endif value="9">Сентябрь</option>
                            <option @if ($resume->personal->birthday_month == '10') selected @endif value="10">Октябрь</option>
                            <option @if ($resume->personal->birthday_month == '11') selected @endif value="11">Ноябрь</option>
                            <option @if ($resume->personal->birthday_month == '12') selected @endif value="12">Декабрь</option>
                        </select>
                        <input type="number" id="birthdayYear" name="birthdayYear"
                            @if ($resume->personal->birthday_year) value="{{ $resume->personal->birthday_year }}" @endif
                            placeholder="Год" class="form-control input-personal">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Пол</div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->gender == 'MALE') checked @endif
                            type="radio" name="gender" value="male" id="genderMale">
                        <label class="form-check-label" for="genderMale">Мужской</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->gender == 'FEMALE') checked @endif
                            type="radio" name="gender" value="female" id="genderFemale">
                        <label class="form-check-label" for="genderFemale">Женский</label>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Переезд</div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->moving == 'cant') checked @endif
                            type="radio" name="moving" value="cant" id="moving-cant">
                        <label class="form-check-label" for="moving-cant">Невозможен</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->moving == 'can') checked @endif
                            type="radio" name="moving" value="can" id="moving-can">
                        <label class="form-check-label" for="moving-can">Возможен</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->moving == 'hope') checked @endif
                            type="radio" name="moving" value="hope" id="moving-hope">
                        <label class="form-check-label" for="moving-hope">Желателен</label>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Готовность к командировкам</div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->trips == 'never') checked @endif
                            type="radio" name="trips" value="never" id="trip-never">
                        <label class="form-check-label" for="trip-never">Никогда</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->trips == 'ready') checked @endif
                            type="radio" name="trips" value="ready" id="trip-ready">
                        <label class="form-check-label" for="trip-ready">Готов</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-personal" @if ($resume->personal->trips == 'sometimes') checked @endif
                            type="radio" name="trips" value="sometimes" id="trip-sometimes">
                        <label class="form-check-label" for="trip-sometimes">Иногда</label>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Контактная информация</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Телефон</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="phone" name="phone"
                        value="{{ $resume->contacts->phone }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'phone') checked @endif
                            type="radio" name="recomended" value="phone" id="recomended-phone">
                        <label class="form-check-label" for="recomended-phone">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Почта</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="email" name="email"
                        value="{{ $resume->contacts->email }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'email') checked @endif
                            type="radio" name="recomended" value="email" id="recomended-email">
                        <label class="form-check-label" for="recomended-email">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Телеграм</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="telegram" name="telegram"
                        value="{{ $resume->contacts->telegram }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'telegram') checked @endif
                            type="radio" name="recomended" value="telegram" id="recomended-telegram">
                        <label class="form-check-label" for="recomended-telegram">Предпочетаумый вид связи</label>
                    </div>
                </div>
            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Специальность</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Желаемая должность</div>
                </div>
                <div class="col-lg-4">
                    <input type="text" name="title" class="form-control input-job"
                        @if ($resume->job) value="{{ $resume->job->title }}" @endif>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Желаемая зарплата</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" name="salary" class="form-control input-job"
                            @if ($resume->job) value="{{ $resume->job->salary }}" @endif>
                        <span class="input-group-text">руб. на руки</span>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Опыт работы</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Опыт работы</div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input input-has-experience"
                            @if ($resume->hasExperience && $resume->hasExperience->has == 1) checked @endif type="radio" name="has"
                            value="1" id="hasExperienceTrue">
                        <label class="form-check-label" for="hasExperienceTrue">Есть опыт работы</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-has-experience"
                            @if ($resume->hasExperience && $resume->hasExperience->has == 0) checked @endif type="radio" name="has"
                            value="0" id="hasExperienceFalse">
                        <label class="form-check-label" for="hasExperienceFalse">Нет опыта работы</label>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif">
                    <div>Место работы</div>
                </div>

                @foreach ($resume->experiences as $expItem)
                    <div class="col-lg-5 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif">
                        <div class="card card-body border-0 shadow-sm">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <div class="fs-5 fw-semibold">{{ $expItem->position }}</div>
                                    <div class=" fs-6 text-muted">{{ date('Y') - $expItem->start_year }} лет</div>
                                </div>
                                <code class="fs-5">{{ $expItem->company }}</code>
                                <hr>
                                <div class="">{{ $expItem->responsibilities }}</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-danger edit-experience-item"
                                    data-item="{{ $expItem }}" data-bs-toggle="modal"
                                    data-bs-target="#experience-item">Изменить</button>
                                <button class="btn btn-sm btn-danger remove-experience-item"
                                    data-item-id="{{ $expItem->id }}">Удалить</button>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif">
                    </div>
                    <div class="col-lg-2 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif">
                    </div>
                @endforeach



                <div class="col-lg-4 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif">
                    <div>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#experience-item"
                            id="btn-add-experience-item">Добавить место работы</button>
                    </div>
                </div>
                <div class="col-lg-6 hasnt-experience @if ($resume->hasExperience && !$resume->hasExperience->has) visually-hidden @endif"></div>
                <div class="col-lg-2">
                    <div>О себе</div>
                </div>
                <div class="col-lg-5">
                    <textarea name="about" rows="5"
                        placeholder="Расскажите о своих качествах, знаниях, увлечениях, которые, как вам кажется, будут полезны работодателю"
                        class="form-control input-resume">{{ $resume->about }}</textarea>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Образование</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Уровень</div>
                </div>
                <div class="col-lg-5">
                    <select name="education_level" id="education_level" class="form-control input-resume">
                        <option @if ($resume->education_level == 'secondary') selected @endif value="secondary">Среднее</option>
                        <option @if ($resume->education_level == 'special_secondary') selected @endif value="special_secondary">Среднее
                            специальное</option>
                        <option @if ($resume->education_level == 'unfinished_higher') selected @endif value="unfinished_higher">Неоконченное
                            высшее</option>
                        <option @if ($resume->education_level == 'higher') selected @endif value="higher">Высшее</option>
                        <option @if ($resume->education_level == 'bachelor') selected @endif value="bachelor">Бакалавр</option>
                        <option @if ($resume->education_level == 'master') selected @endif value="master">Магистр</option>
                        <option @if ($resume->education_level == 'candidate') selected @endif value="candidate">Кандидат наук
                        </option>
                        <option @if ($resume->education_level == 'doctor') selected @endif value="doctor">Доктор наук</option>
                    </select>
                </div>
                <div class="col-lg-5"></div>

                <div class="col-lg-7 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">

                    @foreach ($resume->educations as $eduItem)
                        <div class="card card-body shadow-sm border-0 mb-3">
                            <div class="row gy-4">
                                <div class="col-lg-4">
                                    <div class="d-flex h-100">
                                        <div class="my-auto">Учебное заведение</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" placeholder="Название или аббревиатура" name="college"
                                        data-education-id="{{ $eduItem->id }}" class="form-control input-education-item"
                                        value="{{ $eduItem->college }}">
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex h-100">
                                        <div class="my-auto">Факультет заведение</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" name="faculty" data-education-id="{{ $eduItem->id }}"
                                        class="form-control input-education-item" value="{{ $eduItem->faculty }}">
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex h-100">
                                        <div class="my-auto">Специальзация</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" name="specialitty" data-education-id="{{ $eduItem->id }}"
                                        class="form-control input-education-item" value="{{ $eduItem->specialitty }}">
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex h-100">
                                        <div class="my-auto">Год окончания</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3"><input type="number" placeholder="Год" name="year_end"
                                                data-education-id="{{ $eduItem->id }}"
                                                class="form-control input-education-item"
                                                value="{{ $eduItem->year_end }}">
                                        </div>
                                        <small class="text-muted">Если учитесь в настоящее время укажите год
                                            предполагаемого
                                            окончания</small>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                </div>
                                <div class="col-lg-8">
                                    <div class="">
                                        <button class="btn btn-sm btn-danger ms-auto remove-education-item"
                                            data-education-id="{{ $eduItem->id }}">Удалить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-5"></div>

                @if ($resume->educations->isNotEmpty())
                    <div class="col-lg-2 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                    </div>
                    <div class="col-lg-5 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                        <div>
                            <button class="btn btn-danger btn-sm" id="add-education-item">Указать еще одно место
                                обучения</button>
                        </div>
                    </div>
                    <div class="col-lg-4 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                    </div>
                @else
                    <div class="col-lg-2 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                        <div>Учебное заведение</div>
                    </div>
                    <div class="col-lg-5 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                        <div>
                            <button class="btn btn-danger btn-sm" id="add-education-item">Указать место обучения</button>
                        </div>
                    </div>
                    <div class="col-lg-4 education-hidden @if ($resume->education_level == 'secondary') visually-hidden @endif">
                    </div>
                @endif




            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Другая важная информация</div>
            </div>
            <div class="row gy-4 mb-3" id="append-list">
                <div class="col-lg-2 employment-view @if ($resume->employments->isEmpty()) visually-hidden @endif">
                    <div>Занятость</div>
                </div>
                <div class="col-lg-5 employment-view @if ($resume->employments->isEmpty()) visually-hidden @endif">
                    @foreach ($employments as $employ)
                        <div class="form-check">
                            <input class="form-check-input input-employments"
                                @foreach ($resume->employments as $reEmploy) @if ($reEmploy->employment === $employ->id) checked @endif @endforeach
                                type="checkbox" name="employments[]" value="{{ $employ->id }}"
                                id="employment-{{ $employ->id }}">
                            <label class="form-check-label" for="employment-{{ $employ->id }}">
                                {{ $employ->employment }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-5 employment-view @if ($resume->employments->isEmpty()) visually-hidden @endif">
                    <div><button class="btn btn-sm btn-primary btn-hide-additional" data-btn-id="add-employment"
                            data-url-clear="{{ route('api.resume.edit.employments.clear') }}"
                            data-view="employment-view">Отмена</button></div>
                </div>
                <div class="col-lg-2 schedule-view @if ($resume->schedules->isEmpty()) visually-hidden @endif">
                    <div>График работы</div>
                </div>
                <div class="col-lg-5 schedule-view @if ($resume->schedules->isEmpty()) visually-hidden @endif">
                    @foreach ($schedules as $schedule)
                        <div class="form-check">
                            <input class="form-check-input input-schedules" type="checkbox" name="schedules[]"
                                @foreach ($resume->schedules as $reSchedule) @if ($reSchedule->schedule === $schedule->id) checked @endif @endforeach
                                value="{{ $schedule->id }}" id="schedules-{{ $schedule->id }}">
                            <label class="form-check-label" for="schedules-{{ $schedule->id }}">
                                {{ $schedule->schedule }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-5 schedule-view @if ($resume->schedules->isEmpty()) visually-hidden @endif">
                    <div><button class="btn btn-sm btn-primary btn-hide-additional" data-btn-id="add-schedule"
                            data-url-clear="{{ route('api.resume.edit.schedules.clear') }}"
                            data-view="schedule-view">Отмена</button></div>
                </div>
                <div class="col-lg-2 driving-categories-view @if ($resume->drivingCategories->isEmpty()) visually-hidden @endif">
                    <div>Категория прав</div>
                </div>
                <div class="col-lg-5 driving-categories-view @if ($resume->drivingCategories->isEmpty()) visually-hidden @endif">
                    <div>
                        <div class="btn-group">
                            @foreach ($drivingCategories as $category)
                                <input type="checkbox" class="btn-check input-drive-category" name="driveCategories[]"
                                    @foreach ($resume->drivingCategories as $reDrivingCategory) @if ($reDrivingCategory->category === $category->id) checked @endif @endforeach
                                    value="{{ $category->id }}" id="drive-category-{{ $category->id }}"
                                    autocomplete="off">
                                <label class="btn btn-outline-primary"
                                    for="drive-category-{{ $category->id }}">{{ $category->category }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 driving-categories-view @if ($resume->drivingCategories->isEmpty()) visually-hidden @endif">
                    <div><button class="btn btn-sm btn-primary btn-hide-additional" data-btn-id="add-drive-category"
                            data-url-clear="{{ route('api.resume.edit.driving-categories.clear') }}"
                            data-view="driving-categories-view">Отмена</button></div>
                </div>
                <div class="col-lg-2 has-car-view @if (!$resume->has_car) visually-hidden @endif">
                    <div>Наличие авто</div>
                </div>
                <div class="col-lg-5 has-car-view @if (!$resume->has_car) visually-hidden @endif">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input input-resume" @if ($resume->has_car) checked @endif
                                name="has_car" type="checkbox" id="has-car">
                            <label class="form-check-label" for="has-car">Есть личный автомобиль</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 has-car-view @if (!$resume->has_car) visually-hidden @endif">
                    <div><button class="btn btn-sm btn-primary btn-hide-additional" data-btn-id="add-employment"
                            data-url-clear="has_car" data-view="has-car-view">Отмена</button></div>
                </div>
                <div class="col-lg-2 skills-view @if ($resume->skills->isEmpty()) visually-hidden @endif">
                    <div>Ключевые навыки</div>
                </div>
                <div class="col-lg-5 skills-view @if ($resume->skills->isEmpty()) visually-hidden @endif">
                    <div>
                        <div class="input-group mb-2">
                            <input id="input-skill" type="text" class="form-control"
                                placeholder="Введие навык и добавьте его">
                            <button id="add-skill" class="btn btn-secondary">+</button>
                        </div>
                        <div id="skills-list" class="d-flex flex-wrap">
                            @foreach ($resume->skills as $skill)
                                <div class="skills-item shadow-sm bg-white mb-1 me-1 p-2 rounded d-flex">
                                    <div class="me-2">{{ $skill->skill }}</div>
                                    <button type="button" id="add-skill"class="btn-close" aria-label="Close"></button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 skills-view @if ($resume->skills->isEmpty()) visually-hidden @endif">
                    <div><button class="btn btn-sm btn-primary btn-hide-additional" data-btn-id="add-skills"
                            data-url-clear="{{ route('api.resume.edit.skills.clear') }}"
                            data-view="skills-view">Отмена</button></div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap">
                        <div>
                            <button
                                class="btn btn-outline-danger me-1 mb-1 btn-sm append-item @if ($resume->employments && $resume->employments->isNotEmpty()) visually-hidden @endif"
                                data-view="employment-view" id="add-employment">Занятость</button>
                        </div>
                        <div>
                            <button
                                class="btn btn-outline-danger me-1 mb-1 btn-sm append-item @if ($resume->schedules && $resume->schedules->isNotEmpty()) visually-hidden @endif"
                                data-view="schedule-view" id="add-schedule">График
                                работы</button>
                        </div>
                        <div>
                            <button
                                class="btn btn-outline-danger me-1 mb-1 btn-sm append-item @if ($resume->drivingCategories && $resume->drivingCategories->isNotEmpty()) visually-hidden @endif"
                                data-view="driving-categories-view" id="add-drive-category">Категория прав</button>
                        </div>
                        <div>
                            <button
                                class="btn btn-outline-danger me-1 mb-1 btn-sm append-item  @if ($resume->has_car) visually-hidden @endif"
                                data-view="has-car-view" id="add-has-car">Наличие
                                авто</button>
                        </div>
                        <div>
                            <button
                                class="btn btn-outline-danger me-1 mb-1 btn-sm append-item @if ($resume->skills->isNotEmpty()) visually-hidden @endif"
                                data-view="skills-view" id="add-skills">Ключевые
                                навыки</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6"></div>
            </div>
        </section>
        <section class="mb-5">
            <div class="row gy-4">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap">
                        <button class="btn btn-lg btn-primary @if (Auth::user()->status == 'ADMIN') visually-hidden @endif"
                            id="resume-publish">Сохранить и опубликовать</button>
                    </div>
                </div>
                <div class="col-lg-6"></div>
            </div>
        </section>
    </div>
@endsection


@section('modals')
    <div class="modal fade" id="experience-item">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="exampleModalLabel">Опыт работы</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <label for="exampleInputEmail1" class="form-label fw-semibold">Начало работы</label>
                            <div class="input-group">
                                <select name="birthdayMonth" placeholde="Месяц" name="start_day" id="start_day"
                                    class="form-control input-experience-item validation">
                                    <option value="" disabled selected>Месяц</option>
                                    <option value="1">Январь</option>
                                    <option value="2">Февраль</option>
                                    <option value="3">Март</option>
                                    <option value="4">Апрель</option>
                                    <option value="5">Май</option>
                                    <option value="6">Июнь</option>
                                    <option value="7">Июль</option>
                                    <option value="8">Август</option>
                                    <option value="9">Сентябрь</option>
                                    <option value="10">Октябрь</option>
                                    <option value="11">Ноябрь</option>
                                    <option value="12">Декабрь</option>
                                </select>
                                <input type="number" name="start_year" id="start_year" placeholder="Год"
                                    class="form-control input-experience-item validation">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="exampleInputEmail1" class="form-label fw-semibold">Окончание работы</label>
                            <div class="input-group">
                                <select name="birthdayMonth" id="end_day" placeholde="Месяц" name="end_day"
                                    class="form-control input-experience-item">
                                    <option value="" disabled selected>Месяц</option>
                                    <option value="1">Январь</option>
                                    <option value="2">Февраль</option>
                                    <option value="3">Март</option>
                                    <option value="4">Апрель</option>
                                    <option value="5">Май</option>
                                    <option value="6">Июнь</option>
                                    <option value="7">Июль</option>
                                    <option value="8">Август</option>
                                    <option value="9">Сентябрь</option>
                                    <option value="10">Октябрь</option>
                                    <option value="11">Ноябрь</option>
                                    <option value="12">Декабрь</option>
                                </select>
                                <input type="number" name="end_year" id="end_year" placeholder="Год"
                                    class="form-control input-experience-item">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="exampleInputEmail1" class="form-label fw-semibold">Организация</label>
                                <input type="text" id="company"
                                    class="form-control input-experience-item validation" name="company"
                                    placeholder="Название компании">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="exampleInputEmail1" class="form-label fw-semibold">Должность</label>
                                <input type="text" id="position"
                                    class="form-control input-experience-item validation" name="position"
                                    placeholder="Название компании">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="exampleInputEmail1" class="form-label fw-semibold">Обязанности</label>
                                <textarea rows="5" id="responsibilities" class="form-control input-experience-item validation"
                                    name="responsibilities"
                                    placeholder="Опишите, что вы делали на работе. Работодатели часто ищут резюме по ключевым навыкам, например, «работа в торговом зале», «работа с документами», «сопровождение первых лиц», «управление коллективом» и т.д."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-outline-danger ms-auto" data-bs-toggle="modal"
                            data-bs-dismiss="true">Отмена</button>
                        <button class="btn btn-danger ms-3" id="add-experience-item">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="published-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Резюме отправлено на модерацию!</h3>
                    <p>Дождитесь окончания модерации вашего резюме, вы можете увидеть статус на странице всех резюме</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Хорошо</button>
                </div>
            </div>
        </div>
    </div>
@endsection
