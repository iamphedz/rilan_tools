<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ mix('js/admin_app.js') }}" defer></script>

    <!--<title>{{ config('app.name', 'Laravel') }}</title>-->
    <title>Rilan Tool Supply</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>

<body class="h-screen antialiased leading-none">
    <form id="logout-form" action="/logout" method="POST" style="display: none;">@csrf</form>
    <div id="app">
        @yield('content')
    </div>

    @livewireAssets
</body>