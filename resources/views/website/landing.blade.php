<x-web-layout>
    
    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-left">
                        <img src="{{asset('logo_icons/pictures/hi-meeco.png')}}" alt="Meeco Mascot" class="mascot-img">
                    </div>
                    <div class="hero-right">
                        <h1>Elevate your study game, effortlessly</h1>
                        <div class="cta-buttons">
                            <button class="btn-primary">Get Started</button>
                            <a href="#" class="account-link">I ALREADY HAVE AN ACCOUNT</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 class="text-how-it-works"><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span>How it <span class="highlight-blue">works?</span><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span></h2>
                <p class="section-desc" style="margin-bottom: 32px;">Effortlessly capture and organize your study materials from start to finish.</p>
                <div class="features-grid">
                <a href="convert-image.php" style="text-decoration: none;"><button class="feature-btn">Convert Image</button></a>
                <a href="quiz-maker.php" style="text-decoration: none;"><button class="feature-btn">Quiz maker</button></a>
                <a href="summarizer-and-reviewer.php" style="text-decoration: none;"><button class="feature-btn">Summarizer & reviewer</button></a>
                </div>
            </div>
        </section>

        <section class="quick-efficient">
            <div class="container">
                <div class="content-split">
                    <div class="text-content">
                        <h2>Quick. Convenient. Efficient.</h2>
                        <p style="margin-bottom: 32px;">Turn your photos of notes into text instantly for quick, and generate reviewers. Save time and boost productivity with just a snap!</p>
                        <a href="convert-image.php" style="text-decoration: none;">
                            <button class="btn-secondary">Learn more</button>
                        </a>
                    </div>
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/up-meeco.png')}}" alt="level-up" class="up-meeco">
                    </div>
                </div>
            </div>
        </section>

        <section class="info-digest">
            <div class="container">
                <div class="content-split">
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/present.png')}}" alt="Info Digest Mascot" class="present-png">
                    </div>
                    <div class="text-content">
                        <h2>Your Info Digest</h2>
                        <p style="margin-bottom: 32px;">After gathering all the text, it condenses the information into a clear and concise summary, helping you grasp the key points quickly</p>
                        <a href="summarizer-and-reviewer.php" style="text-decoration: none;">
                            <button class="btn-secondary">Learn more</button>
                        </a>
                    </div>
                </div>
            </div>
        </section>


    
        <section class="backed-by-reviewer">
            <div class="container">
                <div class="content-split">
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/laptop.png')}}" alt="Reviewer Mascot" class="laptop-img">
                    </div>
                    <div class="text-content">
                        <h2>Backed by Reviewer</h2>
                        <p style="margin-bottom: 32px;">Transform your study sessions with a powerful tool designed to streamline your learning. By highlighting essential concepts and enhancing retention, it makes exam preparation not just effective, but also engaging and efficient.</p>
                        <a href="summarizer-and-reviewer.php" style="text-decoration: none;">
                            <button class="btn-secondary">Learn more</button>
                        </a>
                    </div>
                    
                </div>
            </div>
        </section>

        <section class="personalized-quiz-maker">
            <div class="container">
                <div class="content-split">
                    <div class="text-content">
                        <h2>Personalized Quiz Maker</h2>
                        <p style="margin-bottom: 32px;">Generate tailored questions that challenge your knowledge and reinforce learning, ensuring you’re well-prepared for any assessment.</p>
                        <a href="quiz-maker.php" style="text-decoration: none;">
                            <button class="btn-secondary">Learn more</button>
                        </a>
                    </div>
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/head-scratch.png')}}" alt="Quiz Maker Mascot" class="head-scratch-png">
                    </div>
                </div>
            </div>
            
        </section>
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
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>

</x-web-layout>