@extends('layouts.main')

@section('title')
    Резюме - Желаемая должность и зарплата
@endsection

@section('scripts')
    <script>
        let resumeId = {{ $resume->id }}
        let token = '{{ Auth::user()->api_token }}'

        $(".input-job").on("change", function() {
            if (!this.value) return;
            console.log(this.name, this.value)
            $.ajax({
                url: "{{ route('api.resume.edit.job') }}",
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

        $(".input-employments").on('change', function() {
            let array = [];
            $(".input-employments").each((index, element) => {
                if (element.checked) array.push(element.value)
            })
            console.log(array)
            $.ajax({
                url: "{{ route('api.resume.edit.employments') }}",
                method: 'POST',
                data: {
                    resumeId: resumeId,
                    token: token,
                    employments: array,
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

        $(".input-schedules").on('change', function() {
            let array = [];
            $(".input-schedules").each((index, element) => {
                if (element.checked) array.push(element.value)
            })
            console.log(array)
            $.ajax({
                url: "{{ route('api.resume.edit.schedules') }}",
                method: 'POST',
                data: {
                    resumeId: resumeId,
                    token: token,
                    schedules: array,
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
                <div class="h2 mb-3">Желаемая должность и зарплата</div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2">
                    <div>Желаемая должность</div>
                </div>
                <div class="col-lg-4">
                    <input type="text" name="title" class="form-control input-job"
                        @if ($resume->job) value="{{ $resume->job->title }}" @endif>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2">
                    <div>Желаемая зарплата</div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" name="salary" class="form-control input-job"
                            @if ($resume->job) value="{{ $resume->job->salary }}" @endif>
                        <span class="input-group-text">руб. на руки</span>
                    </div>
                </div>
                <div class="col-lg-7"></div>
                <div class="col-lg-2 employment-view">
                    <div>Занятость</div>
                </div>
                <div class="col-lg-5 employment-view">
                    @foreach ($employments as $employ)
                        <div class="form-check">
                            <input class="form-check-input input-employments"
                                @foreach ($resume->employments as $reEmploy) @if ($reEmploy->employment === $employ->id) checked @endif @endforeach
                                type="checkbox" name="employments[]" value="{{ $employ->id }}"
                                id="employment-{{ $employ->id }}">
                            <label class="form-check-label" for="employment-{{ $employ->id }}">
                                {{ $employ->employment }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-5 employment-view"></div>
                <div class="col-lg-2 schedule-view">
                    <div>График работы</div>
                </div>
                <div class="col-lg-5 schedule-view">
                    @foreach ($schedules as $schedule)
                        <div class="form-check">
                            <input class="form-check-input input-schedules" type="checkbox" name="schedules[]"
                                @foreach ($resume->schedules as $reSchedule) @if ($reSchedule->schedule === $schedule->id) checked @endif @endforeach
                                value="{{ $schedule->id }}" id="schedules-{{ $schedule->id }}">
                            <label class="form-check-label" for="schedules-{{ $schedule->id }}">
                                {{ $schedule->schedule }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-5 schedule-view"></div>
            </div>
        </section>
    </div>
@endsection
