<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SUPERSELL')</title>
    <link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Nunito+Sans:wght@400;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/output.css') }}" />
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>