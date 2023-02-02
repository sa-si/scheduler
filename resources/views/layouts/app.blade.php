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

<body>
    <div id="app">
        @auth
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <button class="hamburger" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                    id="js_sidebar_toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="{{ route('month') }}" class="">{{ config('app.name', 'Laravel') }}</a>
                @isset($calendar_type)
                    <a href="{{ route($calendar_type) }}">今日ボタン</a>
                    <a href="{{ route($calendar_type) . '/' . $previous }}">&lt;</a>
                    <a href="{{ route($calendar_type) . '/' . $next }}">&gt;</a>
                    <p>{{ $header_date }}</p>
                    <a href="{{ route('day') . '/' . $request_path }}">日</a>
                    <a href="{{ route('week') . '/' . $request_path }}">週</a>
                    <a href="{{ route('month') . '/' . $request_path }}">月</a>
                    <a href="{{ route('year') . '/' . $request_path }}">年</a>
                @endisset
                <div>
                    <a href="{{ route('user.edit') }}">プロフィール</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('ログアウト') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </nav>

            {{-- <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand me-5" href="#">Scheduler</a>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link active dropdown-toggle" aria-current="page" href="#"
                                id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $header_date }}
                            </a>
                            <div class="dropdown-menu position-absolute" aria-labelledby="navbarDropdown">
                                {!! $calendar->renderHeaderCalendar() !!}
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
                                <a class="nav-link border rounded px-3 py-1" href="#">今日</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 fs-4 py-0" href="#">&lt</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 fs-4 py-0" href="#">&gt</a>
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
                                <a class="nav-link" href="#">日</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">週</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">月</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">年</a>
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
                </div>
            </nav> --}}
        @endauth
        <div class="flex">
            <div class="sidebar display-none" id="js_sidebar">
                @include('layouts.sidebar')
            </div>
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
