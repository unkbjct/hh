@extends('layouts.main')

@section('title')
    Резюме - Опыт работы
@endsection

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
    </script>
@endsection

@section('main')
    <div class="container">
        <section class="mb-5">
            <div class="mb-5">
                <div class="h2 mb-3">Опыт работы</div>
                <a href="{{ route('resume', ['resume' => $resume->id]) }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Опыт работы</div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input input-has-experience"
                            @if ($resume->hasExperience && $resume->hasExperience->has == 1) checked @endif type="radio" name="has" value="1"
                            id="hasExperienceTrue">
                        <label class="form-check-label" for="hasExperienceTrue">Есть опыт работы</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input input-has-experience"
                            @if ($resume->hasExperience && $resume->hasExperience->has == 0) checked @endif type="radio" name="has" value="0"
                            id="hasExperienceFalse">
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
@endsection
