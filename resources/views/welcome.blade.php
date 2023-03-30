@extends('layouts.main')

@section('title')
    WantWork - найти работу легко!
@endsection


@section('main')
    <div class="container">
        <div class="">
            <div class="bg-white p-2 mb-4 border">
                <ul class="nav nav-pills" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-tab-pane"
                            type="button">Вакансии</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="worker-tab" data-bs-toggle="tab" data-bs-target="#worker-tab-pane"
                            type="button">Резюме</button>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="job-tab-pane">
                    <div class="d-flex">
                        <h1 class="h3 mb-4 me-auto">Вакансии @if (Cookie::get('city') && Cookie::get('city') != 'incorrect')
                                в городе {{ json_decode(Cookie::get('city'))->title }}
                            @else
                                по всей России
                            @endif
                        </h1>
                        <div>
                            <button class="btn btn-primary btn-sm">Расширенный поиск</button>
                        </div>
                    </div>
                    <div class="row gy-4">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="col-lg-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body">
                                        <a href="http://google.com?search={{ $i }}"
                                            class="title-link text-decoration-none">{{ $i }} Младший специалист
                                            по настройке рекламы в Директе</a>
                                        <div class="d-flex align-items-end">
                                            <div class="me-auto">
                                                <div>Яндекс Крауд</div>
                                                <div class="mb-3">Москва</div>
                                                <div class="btn-group">
                                                    <button class="btn btn-danger btn-sm">Откликнуться</button>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="favorite-btn me-2">
                                                    <svg height="100%" viewBox="0 0 48 48" width="100%">
                                                        <path clip-rule="evenodd"
                                                            d="M24.804,43.648L24,44l-0.804-0.352C12.862,37.313,2,22.893,2,14.884  C2.035,8.326,7.404,3.002,14,3.002c4.169,0,7.849,2.128,10,5.349c2.151-3.221,5.831-5.349,10-5.349c6.596,0,11.965,5.324,12,11.882  C46,22.893,35.138,37.313,24.804,43.648z M34,4.993c-3.354,0-6.469,1.667-8.335,4.46L24,11.946l-1.665-2.494  C20.469,6.66,17.354,4.993,14,4.993c-5.484,0-9.971,4.442-10,9.891c0,7.064,10.234,20.808,20,26.917  c9.766-6.109,20-19.852,20-26.907C43.971,9.435,39.484,4.993,34,4.993z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="tab-pane fade" id="worker-tab-pane">
                    <div class="">
                        <div class="mb-4">
                            <input type="text" class="form-control mb-2"
                            placeholder="Начние вводить название профессии">
                            <div class="d-flex mb-2">
                                <a href="" class="btn btn-primary btn-sm ms-auto">Расширенный поиск</a>
                            </div>
                        </div>
                        <div class="row gy-4">
                            @for ($i = 0; $i < 10; $i++)
                                <div class="col-lg-4">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <svg height="50px" viewBox="0 0 128 128" width="50px" class="me-3">
                                                    <path
                                                        d="M64.4,77h-3.2c-1.8,0-3.2,1.6-3.2,3.3v2.1c0,1.3,0.7,2.4,1.8,2.9L58.4,103h8.8L66,85.3c1.1-0.5,2-1.6,2-2.9  v-2.1C68,78.6,66.1,77,64.4,77L64.4,77z"
                                                        fill="#3B97D3" />
                                                    <path
                                                        d="M109.8,82.4c-6.6-1.8-29.7-7.6-29.7-7.6S74.1,106,67,106h-8.8c-6.4,0-11.5-27.9-13.2-31  c0,0-23.1,5.7-29.7,7.5c-6.6,1.8-8.1,16.8-8.1,27.5c0,0,14.4,8.5,55.4,8.5c40.9,0,55.3-8.5,55.3-8.5  C117.9,99.3,116.5,84.2,109.8,82.4z M11.3,107.4c0.3-14.4,3.2-20.6,5.1-21.1c4.9-1.3,19.2-4.9,26-6.6c0.2,0.9,0.5,1.8,0.7,2.7  c4.3,16.1,7.8,27.5,15.1,27.5H67c5.2,0,9.1-5.5,13.1-18.4c1.4-4.4,2.4-8.8,3.1-11.9c6.9,1.8,20.8,5.3,25.6,6.6  c1.9,0.5,4.8,6.7,5.1,21.1c-5.1,2.1-20.4,7-51.2,7C31.8,114.4,16.4,109.5,11.3,107.4z"
                                                        fill="#2C3E50" />
                                                    <path
                                                        d="M84.8,24.9C81.7,10.6,68,8.4,61.4,8.4c-8.1,0-21.2,1.8-23.4,18.8c-1.7,13.1,4.4,30,8.8,35.7  c5,6.3,9.4,9.5,15.6,9.5c5.7,0,9.6-2.8,14.9-10.4C82.1,55,87.3,37,84.8,24.9z M74,59.7c-5,7.3-7.9,8.6-11.6,8.6  c-4.5,0-7.9-2.2-12.5-8c-3.5-4.5-9.6-20.2-8-32.7c1.4-10.6,7.4-15.3,19.4-15.3c15,0,18.6,9.4,19.5,13.4C83.2,36.7,78.3,53.5,74,59.7  z"
                                                        fill="#2C3E50" />
                                                </svg>
                                                <div>
                                                    <a href="http://google.com?q={{ $i }}"
                                                        class="title-link text-decoration-none">Программист
                                                        #{{ $i }}</a>
                                                    <div class="h6">Худницкий Дмитрий</div>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="me-4 text-muted">Возраст - 36 лет</div>
                                                <div class="me-4 text-muted">Опыт работы - 2 года</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
