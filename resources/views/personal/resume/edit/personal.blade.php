@extends('layouts.main')

@section('title')
    Резюме - персональные данные
@endsection

@section('scripts')
    <script>
        let resumeId = {{ $resume->id }}
        let token = '{{ Auth::user()->api_token }}'

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
    </script>
@endsection

@section('main')
    <div class="container">
        <section class="mb-5">
            <div class="mb-5">
                <div class="h2 mb-3">Персональный данные</div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Имя</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="name" name="name" value="{{ $resume->personal->name }}"
                        class="form-control input-personal">
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <div class="text-secondary">Укажите ваши настоящие данные. Вы всегда можете скрыть резюме от своего
                        нынешнего работодателя в настройках видимости</div>
                </div>
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
    </div>
@endsection
