@extends('layouts.main')

@section('title')
    {{ $company->legal_title }}
@endsection

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
                url: "{{ route('api.admin.company.edit', ['company' => $company->id]) }}",
                method: 'POST',
                data: {
                    status: $("#action").val(),
                    cancelText: $("#cancelText").val(),
                },
                success: function(e) {
                    console.log(e)
                    window.location = "{{ route('admin.company') }}"
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


@section('main')
    @if (Auth::user()->status == 'ADMIN')
        <div class="container mb-5">
            <a href="{{route('admin.company')}}" class="btn btn-outline-danger btn-sm mb-4">Назад</a>
            <section>
                <p>Изучите данную компанию/организацию, после либо опубликуйте, либо отмените публикацию и укажите причину
                    отмены</p>
                <div class="row gy-4">
                    <div class="col-lg-12">
                        <div>
                            <label for="action" class="form-label">Действие</label>
                            <select class="form-select" id="action">
                                <option value="CONFIRMED">Подтвердить публикацию</option>
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
                    <div>{{ $company->legal_title }}</div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Описание</div>
                </div>
                <div class="col-lg-6">
                    <div>
                        @if ($company->description)
                            {{ $company->description }}
                        @else
                            нет описания
                        @endif
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Город</div>
                </div>
                <div class="col-lg-6">
                    <div>{{ $company->city->city }}</div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Адрес</div>
                </div>
                <div class="col-lg-6">
                    <div>{{ $company->address }}</div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-2">
                    <div>Изображение</div>
                </div>
                <div class="col-lg-2">
                    <div>
                        <img class="w-100" src="{{ asset($company->image) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-8"></div>

            </div>
        </section>
    </div>
@endsection
