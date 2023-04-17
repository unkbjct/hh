@extends('layouts.main')

@section('title')
    Пользователи - управление
@endsection

@section('scripts')
    <script>
        $(".set-status").on("change", function() {
            $.ajax({
                url: "{{ route('api.admin.user.edit') }}",
                method: "POST",
                data: {
                    userId: this.dataset.user,
                    status: this.value,
                },
                success: function(e) {
                    console.log(e);
                    location.reload();
                },
                error: function(e) {
                    console.log(e)
                    alert("Что-то пошло не так \попробуйте позже");
                }
            });
        })
    </script>
@endsection

@section('main')
    <div class="container">
        <div class="h2 mb-4">Управление пользователями</div>
        <section class="mb-5">
            <div>
                <button class="btn btn-danger btn-sm mb-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Фильтры
                </button>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body border-0 shadow-sm">
                        <form action="" method="get">
                            <div class="row gy-2">
                                <div class="col-lg-3">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Имя или фамилия</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Почта</label>
                                        <input type="text" name="email" value="{{ old('email') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Телефон</label>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Роль</label>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('ADMIN', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="ADMIN" id="ADMIN">
                                            <label class="form-check-label" for="ADMIN">
                                                Администратор
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (old('status') && in_array('USER', old('status'))) checked @endif
                                                name="status[]" type="checkbox" value="USER" id="USER">
                                            <label class="form-check-label" for="USER">
                                                Пользователь
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <label for="" class="form-label fw-semibold">Сортировать по</label>
                                        <select class="form-select" name="sort" aria-label="Default select example">
                                            <option selected value="">-</option>
                                            <option @if (old('sort') && old('sort') == 'created_at|desc') selected @endif
                                                value="created_at|desc">
                                                Сначала новые</option>
                                            <option @if (old('sort') && old('sort') == 'created_at|asc') selected @endif
                                                value="created_at|asc">Сначала старые</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div>
                                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">Сбросить
                                            фильтры</a>
                                        <button class="btn btn-sm btn-primary">Применить</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </section>
        <section>
            <div class="row gy-4">
                @forelse ($users as $user)
                    <div class="col-lg-6">
                        <div class="card border-opacity-25 border-danger shadow-sm h-100">
                            <div class="card-header border-0 d-flex justify-content-between flex-wrap">
                                @switch($user->status)
                                    @case('ADMIN')
                                        <div>Администратор</div>
                                    @break

                                    @case('USER')
                                        <div>Пользователь</div>
                                    @break

                                    @default
                                @endswitch
                                <div>Дата регистрации: {{ $user->created_at }}</div>
                            </div>
                            <div class="card-body">
                                <div class="fw-semibold">{{ $user->surname }} {{ $user->name }}</div>
                                <div class="row gy-4">
                                    <div class="col-lg-4">
                                        <div>{{ $user->email }}</div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>{{ $user->phone }}</div>
                                    </div>
                                    <div class="col-lg-4">
                                        @if ($user->id == Auth::user()->id)
                                            <code>Вы не можете изменить себя</code>
                                        @else
                                            <select class="form-select form-select-sm set-status"
                                                data-bs-title="Роль пользователя измениться сразу после выбора"
                                                data-user="{{ $user->id }}" data-bs-toggle="tooltip">
                                                <option @if ($user->status == 'ADMIN') selected @endif value="ADMIN">
                                                    Администратор</option>
                                                <option @if ($user->status == 'USER') selected @endif value="USER">
                                                    Пользователь</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </section>
        </div>
    @endsection
