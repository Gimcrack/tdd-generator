<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="token" content="{{ auth()->user()->api_token }}">
    <meta name="echo-host" content="{{ config('broadcasting.echo.host') }}">
    <meta name="echo-port" content="{{ config('broadcasting.echo.port') }}">


    <script>
        window.INITIAL_STATE = {!! isset($initial_state) ? $initial_state->toJson() : '{}' !!}
    </script>
    @endauth

    @guest
    <script>
        window.INITIAL_STATE = { user : { name : 'Guest', default_group : 'Guests' } }
    </script>
    @endguest

    <title>
        {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="light-theme {{ isset($body_class) ? $body_class : '' }}">
    <div id="app">
        @include('tdd-generator::partials.nav')

        @yield('content')
        @auth

        <reset-password></reset-password>

        <batch-update-selected></batch-update-selected>
        <flash message="{{ session('flash') }}"></flash>
        @endauth
    </div>


    <!-- Scripts -->
    @if(config('broadcasting.default') === 'redis')
    <script src="//{{ config('broadcasting.echo.host') }}:{{ config('broadcasting.echo.port') }}/socket.io/socket.io.js"></script>
    @endif
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
