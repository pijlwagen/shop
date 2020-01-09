<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} Backend</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div id="app">
    @include('partials.header-admin')

    <main class="py-4 mt-5">
        @foreach(['danger', 'success', 'warning', 'info', 'primary', 'secondary'] as $alert)
            @if (Session::has($alert))
                <div class="container">
                    <div class="alert alert-{{ $alert }}" role="alert">
                        {!! Session::get($alert) !!}
                    </div>
                </div>
            @endif
        @endforeach
        @yield('content')
    </main>

</div>
<!-- Scripts -->
<script src="{{ asset('libs/jquery.js') }}"></script>
<script src="{{ asset('libs/popper.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/vue.min.js') }}"></script>
<script src="{{ asset('libs/tagsinput.js') }}"></script>
@stack('js')
</body>
</html>
