@extends('layouts.main')

@section('title')
    Резюме - Опыт вождения
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


        $(".input-drive-category").on('change', function() {
            let array = [];
            $(".input-drive-category").each((index, element) => {
                if (element.checked) array.push(element.value)
            })
            console.log(array)
            $.ajax({
                url: "{{ route('api.resume.edit.driving-categories') }}",
                method: 'POST',
                data: {
                    resumeId: resumeId,
                    token: token,
                    categories: array,
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
                <div class="h2 mb-3">Опыт вождения</div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Вернуться назад</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-2 driving-categories-view">
                    <div>Категория прав</div>
                </div>
                <div class="col-lg-5 driving-categories-view">
                    <div>
                        <div class="btn-group">
                            @foreach ($drivingCategories as $category)
                                <input type="checkbox" class="btn-check input-drive-category" name="driveCategories[]"
                                    @foreach ($resume->drivingCategories as $reDrivingCategory) @if ($reDrivingCategory->category === $category->id) checked @endif @endforeach
                                    value="{{ $category->id }}" id="drive-category-{{ $category->id }}" autocomplete="off">
                                <label class="btn btn-outline-primary"
                                    for="drive-category-{{ $category->id }}">{{ $category->category }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 driving-categories-view"></div>
                <div class="col-lg-2 has-car-view">
                    <div>Наличие авто</div>
                </div>
                <div class="col-lg-5 has-car-view">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input input-resume" @if ($resume->has_car) checked @endif
                                name="has_car" type="checkbox" id="has-car">
                            <label class="form-check-label" for="has-car">Есть личный автомобиль</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 has-car-view"></div>
            </div>
        </section>
    </div>
@endsection
