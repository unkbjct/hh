@extends('layouts.main')

@section('title')
    {{ $company->legal_title }}
@endsection

@section('scripts')
    <script>
        let token = '{{ Auth::user()->api_token }}'

        $("#edit-company").click(function() {
            let formData = new FormData();
            formData.append("image", $("#image")[0].files[0])
            formData.append("token", token)
            formData.append("legal_title", $("#legal_title").val())
            formData.append("address", $("#address").val())
            formData.append("description", $("#description").val())
            $.ajax({
                url: "{{ route('api.company.edit', ['company' => $company->id]) }}",
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(e) {
                    console.log(e)
                    const publishedModal = new bootstrap.Modal(document.getElementById(
                        'published-modal'))
                    document.getElementById('published-modal').addEventListener("hidden.bs.modal",
                        function() {
                            window.location = "{{ route('personal.company.list') }}"
                        })
                    publishedModal.show();
                    console.log(e)
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
            <div class="mb-4">
                <h2>Информация о компании</h2>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Статус</div>
                </div>
                <div class="col-lg-6">
                    @switch($company->status)
                        @case('CREATED')
                            <div>Ожидает проверки</div>
                        @break

                        @case('CONFIRMED')
                            <div>Опубликована</div>
                        @break

                        @case('CANCELED')
                            <div>Отменена</div>
                        @break;

                        @default
                        @break;
                    @endswitch
                </div>
                <div class="col-lg-4"></div>
                @if ($company->status == 'CANCELED')
                    <div class="col-lg-2">
                        <div>Причина отмены</div>
                    </div>
                    <div class="col-lg-6">
                        <div><i>{{ $company->cancel_text }}</i></div>
                    </div>
                    <div class="col-lg-4"></div>
                @endif
                <div class="col-lg-2">
                    <div>Название</div>
                </div>
                <div class="col-lg-6">
                    <div>
                        <input type="text" id="legal_title" value="{{ $company->legal_title }}"
                            class="form-control validation">
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Описание</div>
                </div>
                <div class="col-lg-6">
                    <div>
                        <textarea id="description" rows="5" class="form-control">{{ $company->description }}</textarea>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Адрес</div>
                </div>
                <div class="col-lg-6">
                    <div><input type="text" id="address" value="{{ $company->address }}" class="form-control"></div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Изображение</div>
                </div>
                <div class="col-lg-3">
                    <div>
                        <img class="w-100" src="{{ asset($company->image) }}" alt="">
                        <input type="file" id="image" class="form-control mt-2">
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-6">
                    <div>
                        <a href="{{ route('personal.company.list') }}" class="btn btn-outline-danger btn-sm">отмена</a>
                        <button class="btn btn-primary btn-sm" id="edit-company">Сохранить</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modals')
    <div class="modal fade " id="published-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Компания изменена успешно!</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Хорошо</button>
                </div>
            </div>
        </div>
    </div>
@endsection
