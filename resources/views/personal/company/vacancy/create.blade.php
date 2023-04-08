@extends('layouts.main')

@section('title')
    Создание новой вакансии
@endsection



@section('scripts')
    <script>
        let token = '{{ Auth::user()->api_token }}'

        $("#btn-publish").click(function() {

            let requirements = [],
                pluses = [],
                responsibilities = [],
                offers = [],
                skills = [];

            $("#requirements-list").children().each((index, element) => {
                requirements.push(element.textContent)
            })
            $("#plus-list").children().each((index, element) => {
                pluses.push(element.textContent)
            })
            $("#responsibilities-list").children().each((index, element) => {
                responsibilities.push(element.textContent)
            })
            $("#offers-list").children().each((index, element) => {
                offers.push(element.textContent)
            })

            $("#skills-list").children().each((index, element) => {
                skills.push($(element).children()[0].textContent)
            })

            $.ajax({
                url: "{{ route('api.company.vacancy.create', ['company' => $company->id]) }}",
                method: 'POST',
                data: {
                    position: $("#position").val(),
                    salary: $("#salary").val(),
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    phone: $("#phone").val(),
                    email: $("#email").val(),
                    address: $("#address").val(),
                    desciption: $("#desciption").val(),
                    experience: $(".experience").val(),
                    education: $(".education").val(),
                    employment: $(".employment").val(),
                    schedule: $(".schedule").val(),

                    requirements: requirements,
                    pluses: pluses,
                    responsibilities: responsibilities,
                    offers: offers,
                    skills: skills
                },
                success: function(e) {
                    console.log(e)
                    const publishedModal = new bootstrap.Modal(document.getElementById(
                        'published-modal'))
                    document.getElementById('published-modal').addEventListener("hidden.bs.modal",
                        function() {
                            window.location = e.data.url
                        })
                    publishedModal.show();
                },
                error: function(e) {
                    console.log(e)
                    window.scrollTo(0, 0)
                    for (let key in e.responseJSON.errors) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key).parent().find('.invalid-feedback').remove();
                        $("#" + key).parent().append(
                            `<div class="invalid-feedback">${e.responseJSON.errors[key]}</div>`);
                    }
                }
            })
        })

        $(".interact-list").click(function(e) {
            if (!e.target.classList.contains("btn-close")) return;
            $(e.target).parent().remove();
        })

        $("#requirement-add").click(function() {
            if (!$("#requirements").val()) return;
            $("#requirements-list").append(
                `<li class="list-group-item fw-semibold d-flex justify-content-between align-items-start">${$("#requirements").val()}<button type="button" class="btn-close ms-auto"></button></li>`
            )
        })

        $("#plus-add").click(function() {
            if (!$("#plus-value").val()) return;
            $("#plus-list").append(
                `<li class="list-group-item fw-semibold d-flex justify-content-between align-items-start">${$("#plus-value").val()}<button type="button" class="btn-close ms-auto"></button></li>`
            )
        })

        $("#responsibilities-add").click(function() {
            if (!$("#responsibilities").val()) return;
            $("#responsibilities-list").append(
                `<li class="list-group-item fw-semibold d-flex justify-content-between align-items-start">${$("#responsibilities").val()}<button type="button" class="btn-close ms-auto"></button></li>`
            )
        })

        $("#offers-add").click(function() {
            if (!$("#offers-value").val()) return;
            $("#offers-list").append(
                `<li class="list-group-item fw-semibold d-flex justify-content-between align-items-start">${$("#offers-value").val()}<button type="button" class="btn-close ms-auto"></button></li>`
            )
        })

        $("#skills-add").click(function() {
            if (!$("#skills-value").val()) return;
            $("#skills-list").append(
                `<div class="bg-white shadow-sm rounded py-2 px-3 me-1 mb-1 d-flex align-items-start"><span>${$("#skills-value").val()}</span><button type="button" class="btn-close ms-2"></div>`
            )
        })
    </script>
@endsection

@section('main')
    <div class="container">
        <section>
            <div class="mb-5">
                <div class="h2">{{ $company->legal_title }} - новая вакансия</div>
                <code class="fs-6">После создания вакансии она отправиться на проверку, дождитесь ее
                    окончания</code>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Должность</div>
                </div>
                <div class="col-lg-4">
                    <div>
                        <input type="text" class="form-control validation" id="position">
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Зарплата</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" id="salary" class="form-control validation">
                        <span class="input-group-text">руб. на руки</span>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Имя человека для связи</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <input type="text" id="name" class="form-control validation">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Фамилия человека для связи</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <input type="text" class="form-control validation" id="surname">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Номер человека для связи</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <input type="text" class="form-control validation" id="phone">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Почта человека для связи</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <input type="text" class="form-control  " id="email">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Адрес</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <input type="text" class="form-control" id="address">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Подробное описание</div>
                </div>
                <div class="col-lg-4">
                    <div>
                        <textarea id="desciption" rows="5" placeholder="Опишите рабоу в целом или в чем будут обязанности работника"
                            class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Требуемый опыт работы</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input experience" type="radio" name="experience" id="experience1"
                                value="Не имеет значения">
                            <label class="form-check-label" for="experience1">
                                Не имеет значения
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input experience" type="radio" name="experience" id="experience2"
                                value="Нет опыта">
                            <label class="form-check-label" for="experience2">
                                Нет опыта
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input experience" type="radio" name="experience" id="experience3"
                                value="От 1 года до 3 лет">
                            <label class="form-check-label" for="experience3">
                                От 1 года до 3 лет
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input experience" type="radio" name="experience" id="experience4"
                                value="От 3 лет до 6 лет">
                            <label class="form-check-label" for="experience4">
                                От 3 лет до 6 лет
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input experience" type="radio" name="experience" id="experience5"
                                value="Более 6 лет">
                            <label class="form-check-label" for="experience5">
                                Более 6 лет
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Образование</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input education" type="radio" name="education" id="education1"
                                value="Не требуется">
                            <label class="form-check-label" for="education1">
                                Не требуется
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input education" type="radio" name="education" id="education2"
                                value="Среднее профессиональное">
                            <label class="form-check-label" for="education2">
                                Среднее профессиональное
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input education" type="radio" name="education" id="education3"
                                value="Высшее">
                            <label class="form-check-label" for="education3">
                                Высшее
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Тип занятости</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        @foreach ($employments as $employ)
                            <div class="form-check">
                                <input class="form-check-input employment" type="radio" name="employments"
                                    value="{{ $employ->id }}" id="employment{{ $employ->id }}">
                                <label class="form-check-label" for="employment{{ $employ->id }}">
                                    {{ $employ->employment }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>График работы</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        @foreach ($schedules as $schedule)
                            <div class="form-check">
                                <input class="form-check-input schedule" type="radio" name="schedules"
                                    value="{{ $schedule->id }}" id="schedules{{ $schedule->id }}">
                                <label class="form-check-label" for="schedules{{ $schedule->id }}">
                                    {{ $schedule->schedule }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Требования</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <div class="input-group mb-3">
                            <input placeholder="Добавить требование" data-add-list="true" type="text"
                                id="requirements" class="form-control validation">
                            <button class="btn btn-secondary rounded-end" id="requirement-add">+</button>
                        </div>
                        <ul class="list-group interact-list" id="requirements-list">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-2">
                    <div>Будет плюсом</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <div class="input-group mb-3">
                            <input placeholder="Добавить" type="text" id="plus-value" class="form-control">
                            <button class="btn btn-secondary" id="plus-add">+</button>
                        </div>
                        <ul class="list-group interact-list" id="plus-list">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-2">
                    <div>Обязанности</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <div class="input-group mb-3">
                            <input placeholder="Добавить обязанность" data-add-list="true" type="text"
                                id="responsibilities" class="form-control validation">
                            <button class="btn btn-secondary rounded-end" id="responsibilities-add">+</button>
                        </div>
                        <ul class="list-group interact-list" id="responsibilities-list">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-2">
                    <div>Что мы предлагаем</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <div class="input-group mb-3">
                            <input placeholder="Добавить" type="text" id="offers-value" class="form-control">
                            <button class="btn btn-secondary" id="offers-add">+</button>
                        </div>
                        <ul class="list-group interact-list" id="offers-list">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-2">
                    <div>Ключевые навыки</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <div class="input-group mb-3">
                            <input placeholder="Добавить навык" type="text" id="skills-value" class="form-control">
                            <button class="btn btn-secondary" id="skills-add">+</button>
                        </div>
                        <div class="d-flex flex-wrap interact-list" id="skills-list">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5">
                    <div>
                        <button class="btn btn-primary btn-lg" id="btn-publish">Сохранить и опубликовать</button>
                    </div>
                </div>
                <div class="col-lg-5"></div>
            </div>
        </section>
    </div>
@endsection

@section('modals')
    <div class="modal fade " id="published-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Вакансия отправлена на модерацию!</h3>
                    <p>Дождитесь окончания модерации вашей вакансии, вы можете увидеть статус на странице компании</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Хорошо</button>
                </div>
            </div>
        </div>
    </div>
@endsection
