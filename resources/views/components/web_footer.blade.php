<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeco - Study Smart</title>
    @vite(['resources/css/terms.css', 'resources/css/landing-page-style.css', 'resources/css/navbar-style.css',
    'resources/css/footer-style.css','resources/css/faq-style.css', 'resources/js/app.js'])
    @vite(['resources/css/contact.css'])
</head>
<body class="h-screen w-full">
    <nav class="navbar">
        <div class="container">
            <div class="logo">
            <a href="{{ route('landing')}}" style="text-decoration: none;"><img src="{{ asset('logo_icons/pictures/meeco-logo-text.png')}}" alt="Meeco Logo"></a>
            </div>
            <a href="{{ route('login')}}"><button class="login-btn">Log In</button></a>
        </div>
    </nav>

    <div class="content mt-5">
        {{ $slot }}
    </div>
</body>
</html>