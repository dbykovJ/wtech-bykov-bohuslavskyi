<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SUPERSELL')</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}" />
</head>
<body class="bg-white min-h-screen flex flex-col items-center justify-start">
    @include('partials.navbar')
    @yield('content')
</body>
</html>
