@extends('layouts.main')

@section('title')
    Резюме - Ключевые навыки
@endsection

@section('scripts')
    <script>
        let resumeId = {{ $resume->id }}
        let token = '{{ Auth::user()->api_token }}'

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

@section('main')
    <div class="container">
        <section class="mb-5">
            <div class="mb-5">
                <div class="h2 mb-3">Ключевые навыки</div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2 skills-view">
                    <div>Ключевые навыки</div>
                </div>
                <div class="col-lg-5 skills-view">
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
                <div class="col-lg-5 skills-view"></div>
            </div>
        </section>
    </div>
@endsection
