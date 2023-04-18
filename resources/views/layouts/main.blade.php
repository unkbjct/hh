<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/main.css') }}">
    <script src="{{ asset('public/js/jquery-3.6.4.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/js/main.js') }}"></script>
    @yield('components')
    <title>@yield('title')</title>
</head>

<body>
    <div class="wrapper">
        <header>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home') }}">WantWork</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vacancy.search') }}">Найти работу</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('resume.search') }}">Найти работника</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about.responses') }}">Как работают отклики?</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav mb-lg-0">
                            <li class="nav-item me-5">
                                @if (Cookie::get('city'))
                                    @if (Cookie::get('city') == 'incorrect')
                                        <a class="nav-link text-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" href="#">Не удалось определить
                                            город</a>
                                    @else
                                        <a class="nav-link text-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" href="#">
                                            <div class="border-bottom border-danger border-1">
                                                {{ json_decode(Cookie::get('city'))->title }}</div>
                                        </a>
                                    @endif
                                @else
                                    <script>
                                        window.location = '{{ route('api.city.define') }}';
                                    </script>
                                @endif
                            </li>
                            @if (!Auth::check())
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page"
                                        href="{{ route('login') }}">Авторизоваться</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('signup') }}">Создать
                                        аккаунт</a>
                                </li>
                            @else
                                @if (Auth::user()->status == 'USER')
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('personal') }}">{{ Auth::user()->surname }}
                                                    {{ Auth::user()->name }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('personal.resume.list') }}">Мои
                                                    резюме</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('personal.company.list') }}">Мои вакансии</a></li>
                                            <li>
                                                <hr class="dropdown-divider">

                                            <li><a class="dropdown-item" href="{{ route('personal.responses') }}">Мои
                                                    отклики</a></li>
                                            <li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('personal.favorites') }}">Избранное</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('logout') }}">Выйти</a></li>
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('personal') }}">{{ Auth::user()->surname }}
                                                    {{ Auth::user()->name }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('admin.resume') }}">Резюме</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.company') }}">Компании</a></li>
                                            <li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.vacancy') }}">Вакансии</a></li>
                                            <li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.users') }}">Пользователи</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('logout') }}">Выйти</a></li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="py-5 bg-secondary-subtle">


            <div class="modal fade" id="exampleModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Выберите свой город</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <input type="text" id="city-input" placeholder="Начниете вводить название города"
                                    class="form-control">
                                <div class="form-text">Выберите город из списка</div>
                            </div>
                            <ul class="list-group" id="cities-list">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="good-response">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>Успех</h3>
                            <div class="fw-semibold">Вы успешно откликнулись на вакансию:</div>
                            <code class="fs-5 fw-semibold" id="good-response-position"></code>
                            <p>Если работодателю понравиться ваше резюме, он скоро свяжется с вами</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="bad-response">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>Ошибка</h3>
                            <div class="fw-semibold">Вы не можете откилкнуться на эту вакансию:</div>
                            <code class="fs-6 fw-semibold" id="bad-response-position"></code>
                            <p>По правилам, пользователь может откилкнуться на одну и туже вакансию только один раз в
                                день</p>
                        </div>
                    </div>
                </div>
            </div>




            @yield('modals')
            @yield('main')


        </main>
        <footer class="border-top bg-danger-subtle">
            <div class="container">
                <div class="pt-5">
                    <div class="row">
                        <div class="col-6 col-md-2 mb-3">
                            <h5>WantWork</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">О компании</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Наши вакансии</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Реклама на сайте</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Требования к ПО</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Защита персональных данных</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Безопасынй WantWork</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Партнерам</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Условия оказания услуг</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Условия использования сайта</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-2 mb-3">
                            <h5>Серсвисы для соискателей</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Готовые резюме</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Профориентация</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Продвижение резюме</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Производственный календарь</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-2 mb-3">
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Поиск сотрудников</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Помошь</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Пользовательское соглашение</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Каталог компаний</a></li>
                                <li class="nav-item mb-2"><a href="#"
                                        class="nav-link p-0 text-body-secondary">Работа с процессиям</a></li>
                            </ul>
                        </div>

                        <div class="col-md-5 offset-md-1 mb-3">
                            <div class="d-flex flex-wrap">
                                <div class="display-2 fw-bold bg-danger px-4 py-3 text-white rounded-5">WantWork</div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4">
                        <div class="col-lg-4">
                            <a href="" class="text-decoration-none text-secondary me-1 mb-2">Telegram</a>
                            <a href="" class="text-decoration-none text-secondary me-1 mb-2">Vkontakte</a>
                            <a href="" class="text-decoration-none text-secondary me-1 mb-2">Одноклассники</a>
                            <a href="" class="text-decoration-none text-secondary me-1 mb-2">Gmail</a>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6">
                            <div>
                                <a href="" class="text-decoration-none">
                                    <img src="{{ asset('public/storage/images/badges/appstore.svg') }}"
                                        alt="">
                                </a>
                                <a href="" class="text-decoration-none">
                                    <img src="{{ asset('public/storage/images/badges/googleplay.svg') }}"
                                        alt="">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between pt-4 mt-4 border-top">
                        <p>© 2023 WantWork company</p>
                        <ul class="list-unstyled d-flex">
                            <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi"
                                        width="24" height="24">
                                        <use xlink:href="#twitter"></use>
                                    </svg></a></li>
                            <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi"
                                        width="24" height="24">
                                        <use xlink:href="#instagram"></use>
                                    </svg></a></li>
                            <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi"
                                        width="24" height="24">
                                        <use xlink:href="#facebook"></use>
                                    </svg></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @yield('styles')
    @yield('scripts')

</body>

</html>
