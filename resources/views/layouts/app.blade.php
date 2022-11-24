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
