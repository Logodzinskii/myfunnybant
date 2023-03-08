<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>

</head>
<body class="col-12 d-flex justify-content-start flex-wrap">
    <section class="col-3">
        @include('admin/leftMenu')
    </section>
    <section class="col-9">
        @yield('content')
    </section>
</body>
</html>