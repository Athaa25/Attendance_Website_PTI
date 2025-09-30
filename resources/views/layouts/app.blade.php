<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <!-- CSS global untuk semua halaman -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Placeholder CSS khusus halaman -->
    @yield('head')
</head>

<body>
    @yield('content')
</body>

</html>
