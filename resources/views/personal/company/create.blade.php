@extends('layouts.main')

@section('title')
    Создание новой компании
@endsection

@section('scripts')
    <script>
        let token = '{{ Auth::user()->api_token }}'

        $("#create-company").click(function() {
            let formData = new FormData();
            formData.append("image", $("#image")[0].files[0])
            formData.append("token", token)
            formData.append("legal_title", $("#legal_title").val())
            formData.append("city", $("#city").val())
            formData.append("address", $("#address").val())
            formData.append("description", $("#description").val())
            $.ajax({
                url: "{{ route('api.company.create') }}",
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(e) {
                    console.log(e)
                    window.location = "{{ route('personal.company.list') }}"
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
        <section>
            <div class="row gy-4">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <div>
                        <div class="mb-4">
                            <div class="h2">Создание новой компании</div>
                            <code class="fs-6">После создания компании она отправиться на проверку, дождитесь ее
                                окончания</code>
                        </div>
                        <div class="mb-3">
                            <label for="legal_title" class="form-label">Юридическое название компании *</label>
                            <input type="text" class="form-control validation" id="legal_title">
                        </div>
                        <div class="mb-3">
                            <label for="legal_title" class="form-label">Описание компании *</label>
                            <textarea id="description" class="form-control validation" rows="5"></textarea>
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="city-form-input" class="form-label">Город *</label>
                            <input type="hidden" name="city" id="city">
                            <input type="text" class="form-control validation" id="city-form-input">
                            <div class="list-group position-absolute w-100">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="legal_title" class="form-label">Адрес</label>
                            <input type="text" class="form-control" id="address">
                        </div>
                        <div class="mb-3">
                            <label for="legal_title" class="form-label">Лого компании</label>
                            <input type="file" class="form-control" id="image">
                        </div>
                        <div class="mb-3">
                            <div class="mb-2">* - обязательные поля</div>
                            <button class="btn btn-outline-danger" id="create-company">Создать компанию</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </section>
    </div>
@endsection
