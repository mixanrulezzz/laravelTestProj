<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @yield('head')
</head>
<body>
    @yield('beforeContent')
    @yield('content')
    @yield('afterContent')
</body>
</html>
