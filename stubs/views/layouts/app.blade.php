<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

<div id="app">
    @include('layouts.nav')
    @include('layouts.sidebar')
    <section class="app-main">
        <div class="container is-fluid is-marginless app-content">
            @yield('body')
        </div>
    </section>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>