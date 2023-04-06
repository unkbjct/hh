@extends('layouts.main')

@section('title')
    Создание новой вакансии
@endsection



@section('scripts')
    <script>
        let token = '{{ Auth::user()->api_token }}'

        $("#create-company").click(function() {
            let formData = new FormData();
            formData.append("image", $("#image")[0].files[0])
            formData.append("token", token)
            formData.append("legal_title", $("#legal_title").val())
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
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Зарплата</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" class="form-control">
                        <span class="input-group-text">руб. на руки</span>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </section>
    </div>
@endsection
