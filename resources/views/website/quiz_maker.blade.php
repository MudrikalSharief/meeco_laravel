<x-web_layout>
    <div class="relative right-0 top-0 pt-3 pl-3">
        <a href="{{ route('landing')}}"><</a>
        <!-- <a href="{{ route('landing')}}"><img class="w-5" src="{{ asset('logo_icons/x.svg')}}" alt=""></a> -->
    </div>
    <main>
        <section class="quick-efficient">
            <div class="container">
                <div class="content-split">
                    <div class="text-content">
                        <h2 class="subtitle">Personalized Quiz Maker</h2>
                        <p style="font-size:18px; font-weight:500; margin-bottom: 32px;">Generate tailored questions that challenge your knowledge and reinforce learning, ensuring you’re well-prepared for any assessment.
                        </p>
                        <a href="../login/login.php" style="text-decoration: none;">
                            <button class="btn-subtitle">Start Your Journey</button>
                        </a>    
                    </div>
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/convert-image-8.png')}}" alt="level-up" class="convert-image-meeco">
                    </div>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 class="text-how-it-works"><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span>How it <span class="highlight-blue">works?</span><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span></h2>
                <p class="section-desc" style="font-size: 20px; font-weight:500; margin-bottom: 32px;">Just Make It.</p>
            </div>
        </section>

        <section class="quick-efficient">
            <div class="container">
                <div class="content-split">
                    <div class="text-content">
                        <h2 class="subtitle">Set up your quiz</h2>
                        <p style="font-size:18px; font-weight:500; margin-bottom: 32px;">Build the bridge to understanding.</p>
                        <button class="btn-subtitle">Learn more</button>
                        <p style="padding-top:30px; margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>Quiz name</span></p>
                        <p style="margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>Number of Questions</span></p>
                    </div>
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/convert-image-6.png')}}" alt="level-up" class="convert-image-meeco">
                    </div>
                </div>
            </div>
        </section>


        <section class="quick-efficient">
            <div class="container">
                <div class="content-split">
                    <div class="text-content">
                        <h2 class="subtitle">Choose your quiz</h2>
                        <p style="font-size:18px; font-weight:500; margin-bottom: 32px;">Unlock new doors with every question.</p>
                        <button class="btn-subtitle">Learn more</button>
                        <p style="padding-top:30px; margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>Multiple choice</span></p>
                        <p style="margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>Number of Questions</span></p>
                        <p style="margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>True or False</span></p>
                        <p style="margin-bottom: 4px;"><img src="{{asset('logo_icons/pictures/check.png')}}" alt="check" class="check"></span>Mixed</span></p>
                    </div>
                    <div class="image-content">
                        <img src="{{asset('logo_icons/pictures/convert-image-7.png')}}" alt="level-up" class="convert-image-meeco">
                    </div>
                </div>
            </div>
        </section>


        <section class="how-it-works">
            <div class="container">
                <h2 class="text-how-it-works"><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span> <span class="highlight-blue">Explore more features</span><img src="{{asset('logo_icons/pictures/sparkling.png')}}" alt="sparkle" class="sparkle"></span></h2>
                <p class="section-desc" style="margin-bottom: 32px; font-size: 20px; font-weight:500;">We have a lot of surprises in store...</p>
                <div class="more-features-grid">
                    <div class="box">
                        <h2 class="text-more-features"  style="text-align: justify; margin-left: 14px;
                         margin-right: 14px;">Quick. Convenient. Efficient</h2>
                        <p class="more-features-p"style="text-align: justify;">Turn your photos of notes into text instantly for quick, and generate reviewers. Save time and boost productivity with just a snap!
                        </p>
                        <a href="{{route('convert_image')}}" style="text-decoration: none;">
                        <p class="see-more-p" style="text-align: left; margin-top: 170px; margin-left: 14px;
                         margin-right: 14px;">See more ></p>
                         </a>
                    </div>
                    <div class="box">
                        <h2 class="text-more-features"  style="text-align: justify; margin-left: 14px;
                        margin-right: 14px;">Your Info Digest & Reviewer</h2>
                        <p class="more-features-p"style="text-align: justify;">After gathering all the text, it condenses the information into a clear summary, helping you grasp key points quickly. By highlighting essential concepts, it streamlines your study sessions and enhances retention, making exam preparation efficient and engaging.</p>
                       <a href="{{route('summarizer_and_reviewer')}}" style="text-decoration: none;">
                            <p class="see-more-p" style="text-align: left; margin-top: 65px; margin-left: 14px;
                            margin-right: 14px;" >See more ></p>
                        </a>
                    </div>
            </div>
        </section>

    </main>

</x-web_layout>