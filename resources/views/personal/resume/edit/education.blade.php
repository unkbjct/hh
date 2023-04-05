@extends('layouts.main')

@section('title')
    Резюме - Образование
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
    </script>
@endsection

@section('main')
    <div class="container">
        <section class="mb-5">
            <div class="mb-5">
                <div class="h2 mb-3">Образование</div>
                <a href="{{ route('resume', ['resume' => $resume->id]) }}" class="btn btn-sm btn-danger">Вернуться назад</a>
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
                                                class="form-control input-education-item" value="{{ $eduItem->year_end }}">
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
    </div>
@endsection
