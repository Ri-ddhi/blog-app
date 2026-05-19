<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>
    @stack('styles')
</head>
<body>

<header>
    <nav>
        <a href="#">Home</a> | <a href="#">About</a>
    </nav>
</header>

<main>
    @yield('content')
</main>

<footer>
    <p>&copy; {{ date('Y') }} My Laravel App. All rights reserved.</p>
</footer>

</body>
</html>
