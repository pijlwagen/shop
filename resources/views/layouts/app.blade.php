<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
@yield('seo')

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<div id="app">
    @if ((Auth::user() && Auth::user()->isAdmin()))
        @include('partials.header', ['admin' => false])
        {{--        @include('partials.header-admin')--}}
    @else
        @include('partials.header')
    @endif


    <main class="py-5 mt-5">
        <div class="container-fluid px-5">
            @foreach(['danger', 'success', 'warning', 'info', 'primary', 'success'] as $alert)
                @if (session()->has($alert))
                    @if (is_array(session()->get($alert)))
                        @foreach(session()->get($alert) as $text)
                            <div class="alert alert-{{ $alert }}" role="alert">
                                {!! $text !!}
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-{{ $alert }}" role="alert">
                            {!! session()->get($alert) !!}
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
        <noscript>
            <h1 class="text-center mt-5 pt-5">This site will not work without JavaScript</h1>
            <h2 class="text-center mb-5 pb-5">Please enable JavaScript to continue</h2>
        </noscript>
        @yield('content')
    </main>

    @include('partials.footer')
</div>
<!-- Scripts -->
<script src="{{ asset('libs/jquery.js') }}"></script>
<script src="{{ asset('libs/popper.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/vue.min.js') }}"></script>
@stack('js')
</body>
</html>
