<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SUPERSELL')</title>
    <link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Nunito+Sans:wght@400;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/output.css') }}" />
</head>
<body class="bg-white min-h-screen flex flex-col items-center justify-start pt-[136px]">
    @yield('content')
</body>
</html>
