<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if( Auth::check() )
    <meta name="token" content="{{ auth()->user()->api_token }}">

    <meta name="echo-host" content="{{ env('ECHO_HOST') }}">

    <script>
        window.INITIAL_STATE = {!! isset($initial_state) ? $initial_state->toJson() : '{}' !!}
    </script>
    @endif

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
        @if( Auth::check() )

        <reset-password></reset-password>

        <batch-update-selected></batch-update-selected>
        <flash message="{{ session('flash') }}"></flash>
        @endif
    </div>


    <!-- Scripts -->
    @if(config('broadcasting.default') === 'redis')
    <script src="//{{ config('app.echo_host') }}:6001/socket.io/socket.io.js"></script>
    @endif
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
