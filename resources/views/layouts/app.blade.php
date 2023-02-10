<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- @yield('javascript') --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
</head>

<body class="bg-white">
    <div id="app">
        @auth
            <nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-dark">
                <div class="container-fluid">
                    @isset($calendar)
                        <input type="hidden" id="js_header_calendar_date"
                            value="{{ $calendar->getHeaderCalendarYearAndMonth() }}">
                        <input type="hidden" id="js_header_calendar_initialize"
                            value="{{ route('header-calendar-initialize') }}">
                        <a class="navbar-brand me-5" href="{{ route('month') }}">{{ config('app.name', 'Laravel') }}</a>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown me-3" id="header_dropdown">
                                <a class="nav-link active dropdown-toggle" aria-current="page" href="#"
                                    id="dropdownMenuClickableInside" role="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
                                    {{ $header_date }}
                                </a>
                                <div class="dropdown-menu position-absolute" aria-labelledby="dropdownMenuClickableInside">
                                    {!! $calendar->renderHeaderCalendar($calendar->getHeaderCalendarYearAndMonth(), $calendar_type) !!}
                                </div>
                            </li>
                        </ul>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarCollapse">
                            <ul class="ps-0 me-3 list-unstyled navbar-nav flex-fill d-none d-lg-flex">
                                <li class="nav-item d-flex align-items-center me-3">
                                    <a class="nav-link border rounded px-3 py-1" href="{{ route($calendar_type) }}">今日</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 fs-4 py-0"
                                        href="{{ route($calendar_type) . '/' . $previous }}">&lt</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 fs-4 py-0" href="{{ route($calendar_type) . '/' . $next }}">&gt</a>
                                </li>
                            </ul>
                            <ul class="flex ps-0 me-3 list-unstyled navbar-nav flex-fill d-lg-none">
                                <li class="nav-item d-flex align-items-center me-3">
                                    <a class="nav-link py-1" href="#">今日</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-4 py-0" href="#">&lt</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-4 py-0" href="#">&gt</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav me-3 mb-md-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('day') . '/' . $request_date_path }}">日</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('week') . '/' . $request_date_path }}">週</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('month') . '/' . $request_date_path }}">月</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('year') . '/' . $request_date_path }}">年</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav me-5">
                                <li class="nav-item">
                                    <a href="{{ route('trash-can') }}" class="nav-link block">
                                        ゴミ箱
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav mb-2 mb-md-0">
                                <li>
                                    <a class="nav-link" href="{{ route('user.edit') }}">プロフィール</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        {{ __('ログアウト') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endisset
                    @isset($discarded_tasks)
                        <div class="d-flex">
                            <button class="btn btn-dark border-0 me-3" type="button"
                                onClick="history.back();">&#8656;</button>
                            <span class="navbar-text text-white fs-6">ゴミ箱</span>
                        </div>
                    @endisset
                    @isset($user_profile)
                        <div class="d-flex">
                            <button class="btn btn-dark border-0 me-3" type="button"
                                onClick="history.back();">&#8656;</button>
                            <span class="navbar-text text-white fs-6">プロフィール編集</span>
                        </div>
                    @endisset
                </div>
            </nav>
        @endauth

        <main class="py-3">
            @yield('content')
        </main>
    </div>
    @isset($calendar)
        <script>
            const calendar = @json($calendar);
        </script>
    @endisset
</body>

</html>
