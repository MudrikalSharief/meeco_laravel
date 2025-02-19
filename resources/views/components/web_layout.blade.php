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
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>About us</h3>
                    <ul>
                        <li><a href="#">Mission</a></li>
                        <li><a href="#">Vision</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Privacy and Terms</h3>
                    <ul>
                        <li><a href="#">Community guidelines</a></li>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Privacy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Help and Support</h3>
                    <ul>
                        <li><a href="{{route('faq')}}">FAQ</a></li>
                        <li><a href="{{route('contact')}}">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>