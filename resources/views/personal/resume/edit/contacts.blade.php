@extends('layouts.main')

@section('title')
    Резюме - Контактная информация
@endsection

@section('scripts')
    <script>
        let resumeId = {{ $resume->id }}
        let token = '{{ Auth::user()->api_token }}'

        $(".input-contacts").on("change", function() {
            console.log(this.name, this.value)
            $.ajax({
                url: "{{ route('api.resume.edit.contacts') }}",
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
                <div class="h2 mb-3">Контактная информация</div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Телефон</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="phone" name="phone"
                        value="{{ $resume->contacts->phone }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'phone') checked @endif
                            type="radio" name="recomended" value="phone" id="recomended-phone">
                        <label class="form-check-label" for="recomended-phone">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Почта</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="email" name="email"
                        value="{{ $resume->contacts->email }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'email') checked @endif
                            type="radio" name="recomended" value="email" id="recomended-email">
                        <label class="form-check-label" for="recomended-email">Предпочетаемый вид связи</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div>Телеграм</div>
                </div>
                <div class="col-lg-3">
                    <input type="text" autocomplete="off" id="telegram" name="telegram"
                        value="{{ $resume->contacts->telegram }}" class="form-control input-contacts">
                </div>
                <div class="col-lg-7">
                    <div class="form-check">
                        <input class="form-check-input input-contacts" @if ($resume->contacts->recomended == 'telegram') checked @endif
                            type="radio" name="recomended" value="telegram" id="recomended-telegram">
                        <label class="form-check-label" for="recomended-telegram">Предпочетаумый вид связи</label>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
