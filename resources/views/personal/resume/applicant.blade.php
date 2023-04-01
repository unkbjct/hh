@extends('layouts.main')

@section('title')
    Создание нового резюме
@endsection

@section('scripts')
    <script>
        let resumeId = {{ $resume->id }}
        $(".input-personal").on("change", function() {
            if (!this.value) return;
            console.log(this.name, this.value)
            $.ajax({
                url: "{{ route('api.resume.edit.personal') }}",
                method: 'POST',
                data: {
                    resumeId: 12,
                    [this.name]: this.value,
                },
                success: function(e) {
                    // location.reload();
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

@section('main')
    <div class="container">
        <div class="d-flex flex-wrap mb-4">
            <div class="me-auto">
                <h1 class="h2">Ваше резюме</h1>
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
                    <div class="input-group">
                        <input type="text" id="city" name="city"
                            value="{{ json_decode(Cookie::get('city'))->title }}" class="form-control">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"
                            class="border-start-0 btn btn-light border bg-white">
                            <svg width="16" height="16" fill="silver" viewBox="0 0 32 32">
                                <path
                                    d="M26,16c0,1.104-0.896,2-2,2H8c-1.104,0-2-0.896-2-2s0.896-2,2-2h16C25.104,14,26,14.896,26,16z"
                                    id="XMLID_314_" />
                                <path
                                    d="M26,8c0,1.104-0.896,2-2,2H8c-1.104,0-2-0.896-2-2s0.896-2,2-2h16C25.104,6,26,6.896,26,8z"
                                    id="XMLID_315_" />
                                <path
                                    d="M26,24c0,1.104-0.896,2-2,2H8c-1.104,0-2-0.896-2-2s0.896-2,2-2h16C25.104,22,26,22.896,26,24z"
                                    id="XMLID_316_" />
                            </svg>
                        </button>
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
                    <input type="text" id="phone" name="phone" value="" class="form-control">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recomended" value="phone"
                            id="recomended-phone">
                        <label class="form-check-label" for="recomended-phone">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Почта</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="phone" name="phone" value="" class="form-control">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recomended" value="email"
                            id="recomended-email">
                        <label class="form-check-label" for="recomended-email">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Телеграм</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="phone" name="phone" value="" class="form-control">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recomended" value="telegram"
                            id="recomended-telegram">
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
                    <input type="text" name="spesiality" class="form-control">
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Желаемая зарплата</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" name="salary" class="form-control">
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
                        <input class="form-check-input" type="radio" name="hasExperience" value="true"
                            id="hasExperienceTrue">
                        <label class="form-check-label" for="hasExperienceTrue">Есть опыт работы</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="hasExperience" value="false"
                            id="hasExperienceFalse">
                        <label class="form-check-label" for="hasExperienceFalse">Нет опыта работы</label>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2">
                    <div>Место работы</div>
                </div>
                <div class="col-lg-4">
                    <div>
                        <button class="btn btn-danger btn-sm">Добавить место работы</button>
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>О себе</div>
                </div>
                <div class="col-lg-5">
                    <textarea name="aboutMe" rows="5"
                        placeholder="Расскажите о своих качествах, знаниях, увлечениях, которые, как вам кажется, будут полезны работодателю"
                        class="form-control"></textarea>
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
                    <select name="education" class="form-control">
                        <option value="s">Среднее</option>
                        <option value="s">Среднее специальное</option>
                        <option value="s">Неоконченное высшее</option>
                    </select>
                </div>
                <div class="col-lg-5"></div>

                <div class="col-lg-7">
                    <div class="card card-body shadow-sm border-0">
                        <div class="row gy-4">
                            <div class="col-lg-4">
                                <div class="d-flex h-100">
                                    <div class="my-auto">Учебное заведение</div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" placeholder="Название или аббревиатура" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <div class="d-flex h-100">
                                    <div class="my-auto">Факультет заведение</div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <div class="d-flex h-100">
                                    <div class="my-auto">Специальзация</div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <div class="d-flex h-100">
                                    <div class="my-auto">Год окончания</div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3"><input type="number" placeholder="Год" class="form-control">
                                    </div>
                                    <small class="text-muted">Если учитесь в настоящее время укажите год предполагаемого
                                        окончания</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5"></div>

                <div class="col-lg-2"></div>
                <div class="col-lg-5">
                    <div>
                        <button class="btn btn-danger btn-sm">Указать еще одно место обучения</button>
                    </div>
                </div>
                <div class="col-lg-4"></div>



                <div class="col-lg-2">
                    <div>Учебное заведение</div>
                </div>
                <div class="col-lg-5">
                    <div>
                        <button class="btn btn-danger btn-sm">Указать место обучения</button>
                    </div>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </section>
        <section class="mb-5">
            <div class="d-flex mb-3">
                <div class="h5">Другая важная информация</div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap">
                        <div><button class="btn btn-outline-danger me-1 mb-1 btn-sm">Переезд</button></div>
                        <div><button class="btn btn-outline-danger me-1 mb-1 btn-sm">Занятость</button></div>
                        <div><button class="btn btn-outline-danger me-1 mb-1 btn-sm">Разрешение на работу</button></div>
                        <div><button class="btn btn-outline-danger me-1 mb-1 btn-sm">Категория прав</button></div>
                        <div><button class="btn btn-outline-danger me-1 mb-1 btn-sm">Наличине авто</button></div>
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
                        <button class="btn btn-lg btn-primary">Сохранить и опублиновать</button>
                    </div>
                </div>
                <div class="col-lg-6"></div>
            </div>
        </section>
    </div>
@endsection
