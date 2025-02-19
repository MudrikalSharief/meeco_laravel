<x-web_footer>
    <main>
        <div class="breadcrumb mb-10 pl-25 font-bold text-xl">
            <a href="{{ route('landing')}}" class="text-blue-700 no-underline">Home</a>
            <span class="text-blue-700 mx-1">></span>
            <span class="text-blue-700">Help and Support</span>
        </div>
    
        <h2 class="Contact_Us w-full text-center text-blue-700 text-4xl font-bold mb-5">Contact Us</h2>
        <p class="description">Thank you for choosing Meeco. Please feel free to contact us with the channel below, we are happy to help.</p>
    
        <div class="services">
            <a href="{{ route('inquiry') }}">
                <div class="service">
                        <img class="Note w-20 h-20 object-contain mx-auto mt-3" src="{{ asset('logo_icons/pictures/note.png') }}" alt="Note">
                        <div class="service-content">
                            <h3 class="font-bold text-lg">Inquiry</h3>
                            <p>Got questions? This is your pathway to unravel the unknown.</p>
                        </div>
                </div>
            </a>
            
            <a href="{{ route('inquiry-history') }}">
                <div class="service">
                    <img class="Note w-20 h-20 object-contain mx-auto mt-3" src="{{ asset('logo_icons/pictures/history.png') }}" alt="Note">
                    <div class="service-content">
                        <h3 class="font-bold text-lg">Inquiry History</h3>
                        <p>See how your questions have shaped your quest for knowledge.</p>
                    </div>
                </div>
            </a>
        </div>

    </main>
</x-web_footer>