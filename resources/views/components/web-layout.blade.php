<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeco - Study Smart</title>
    @vite(['resources/css/landing-page-style.css', 'resources/css/navbar-style.css',
    'resources/css/footer-style.css','resources/css/faq-style.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
            <a href="landing_page.php" style="text-decoration: none;"><img src="{{ asset('logo_icons/pictures/meeco-logo-text.png')}}" alt="Meeco Logo"></a>
            </div>
            <a href="{{ route('login')}}"><button class="login-btn">Log In</button></a>
        </div>
    </nav>
    {{ $slot }}
</body>
</html>