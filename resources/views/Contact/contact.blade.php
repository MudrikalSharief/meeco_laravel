<x-contact_layout>
    <main class="px-4 w-full max-w-lg">
        <div class="breadcrumb mb-2  font-bold text-md">
            <a href="{{ route('profile') }}" class="text-gray-500 text-xl no-underline">←</a>
            <a href="{{ route('profile')}}" class="text-gray-500 no-underline">Back</a> 
        </div>
    
        <h2 class="Contact_Us w-full text-center text-blue-700 text-xl font-bold mb-5">Contact Us</h2>
        <p class="description text-center text-xs text-gray-800 mb-15 mx-10">Thank you for choosing Meeco. Please feel free to contact us with the channel below, we are happy to help.</p>
    
        <div class="services flex flex-col sm:flex-row justify-center items-center gap-4 mt-10 px-2">
            <a href="{{ route('inquiry') }}" class="w-full  bg-blue-50 hover:bg-blue-100 rounded-lg p-3 shadow-md">
                <div class="service flex flex-col  items-start  max-w-lgrounded-lg">
                    <div class="flex justify-start gap-2 items-center service-content text-center sm:text-left">
                        <img class="icon w-6 h-10 object-contain " src="{{ asset('logo_icons/pictures/note.png') }}" alt="Note">
                        <h3 class="text-blue-700 text-md font-bold">Inquiry</h3>
                    </div>
                        <p class="text-gray-800 m-0 text-sm leading-6">Got questions? This is your pathway to unravel the unknown.</p>
                </div>
            </a>
            
            <a href="{{ route('inquiry-history') }}" class="w-full  bg-blue-50 hover:bg-blue-100 rounded-lg p-3 shadow-md">
                <div class="service flex flex-col  items-start  max-w-lgrounded-lg">
                    <div class="flex justify-start gap-2 items-center service-content text-center sm:text-left">
                        <img class="icon w-6 h-10 object-contain " src="{{ asset('logo_icons/pictures/history.png') }}" alt="Note">
                        <h3 class="text-blue-700 text-md font-bold">Inquiry History</h3>
                    </div>
                    <p class="text-gray-800 m-0 text-sm leading-6">See how your questions have shaped your quest for knowledge.</p>
                </div>
            </a>
        </div>
    </main>
</x-contact_layout>