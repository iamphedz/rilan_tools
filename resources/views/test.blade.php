<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Test Component</title>
	<script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireAssets
</head>

<body class="h-screen antialiased leading-none">
        @livewire('productsearch')
        <span>esd</span>
        <div id="app"></div>
</body>
</html>