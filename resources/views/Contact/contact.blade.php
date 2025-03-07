<x-contact_layout>
    <main class="px-4 md:px-10">
        <div class="breadcrumb mb-10 px-4 md:px-10 lg:px-20 xl:pl-72 font-bold text-xl">
            <a href="{{ route('landing')}}" class="text-blue-700 no-underline">Home</a>
            <span class="text-blue-700 mx-1">></span>
            <span class="text-blue-700">Help</span>
        </div>
    
        <h2 class="Contact_Us w-full text-center text-blue-700 text-4xl font-bold mb-5">Contact Us</h2>
        <p class="description text-center text-gray-800 mb-15">Thank you for choosing Meeco. Please feel free to contact us with the channel below, we are happy to help.</p>
    
        <div class="services flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12 md:gap-24 mt-10 px-2">
            <a href="{{ route('inquiry') }}" class=" sm:w-auto mx-auto sm:mx-0">
                <div class="service flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-10 max-w-lg hover:bg-gray-200 rounded-lg p-4 sm:p-9">
                    <img class="icon w-10 h-10 object-contain mt-0 sm:mt-3" src="{{ asset('logo_icons/pictures/note.png') }}" alt="Note">
                    <div class="service-content text-center sm:text-left">
                        <h3 class="text-blue-700 mb-2 text-lg font-bold">Inquiry</h3>
                        <p class="text-gray-800 m-0 leading-6">Got questions? This is your pathway to unravel the unknown.</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('inquiry-history') }}" class=" sm:w-auto mx-auto sm:mx-0">
                <div class="service flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-10 max-w-lg hover:bg-gray-200 rounded-lg p-4 sm:p-9">
                    <img class="icon w-10 h-10 object-contain mt-0 sm:mt-3" src="{{ asset('logo_icons/pictures/history.png') }}" alt="Note">
                    <div class="service-content text-center sm:text-left">
                        <h3 class="text-blue-700 mb-2 text-lg font-bold">Inquiry History</h3>
                        <p class="text-gray-800 m-0 leading-6">See how your questions have shaped your quest for knowledge.</p>
                    </div>
                </div>
            </a>
        </div>
    </main>
</x-contact_layout>