<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SUPERSELL')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    @stack('styles')
</head>
<body>
    @include('partials.banner')
    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <script src="{{ asset('js/shared/close.js') }}"></script>
    <script src="{{ asset('js/shared/burger.js') }}"></script>
    <script src="{{ asset('js/shared/scrollTo.js') }}"></script>
    @stack('scripts')
</body>
</html>
