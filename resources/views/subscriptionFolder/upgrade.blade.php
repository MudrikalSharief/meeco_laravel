<x-layout>
    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center text-xl font-semibold">
                    PREMIUM OFFERS
                </span>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="flex-grow overflow-hidden pb-16">
            @if($promos->isEmpty())
                <p class="text-center text-gray-600 font-semibold">No Available Offers</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 py-2 px-3">
                    @foreach($promos as $promo)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 transform {{ $promo->subscribed ? 'hover:scale-105' : '' }} flex flex-col">
                        <div class="bg-blue-600 text-white text-center py-2 rounded-t-lg">
                            <span class="font-semibold text-xl px-1">{{ $promo->name }}</span>
                        </div>
                        <div class="p-4 flex-grow">
                            <p class="text-center font-bold text-3xl text-blue-700">₱{{ $promo->price }}</p>
                            <ul class="mt-2 text-blue-500 list-disc list-inside">
                                <li>{{ $promo->duration }} days access</li>
                                <li>{{ $promo->perks }}</li>
                            </ul>
                            @if($promo->subscribed)
                                <p class="mt-3 text-center text-xl text-blue-600">Active promo!</p>
                            @endif
                        </div>
                        @if($promo->subscribed)
                            <a href="{{ route('profile') }}" class="m-4 py-2 bg-blue-500 text-white rounded-lg font-semibold text-center transition-transform duration-300 transform hover:scale-105">
                                View Info
                            </a>
                            
                        @else
                            @if($promos->contains('subscribed', true))
                                <button class="m-4 py-2 bg-gray-500 text-white rounded-lg font-semibold cursor-not-allowed" disabled>
                                    Subscribe
                                </button>
                            @else
                                <button data-promo-id="{{ $promo->promo_id }}" class="m-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-800">
                                    Subscribe
                                </button>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('button[data-promo-id]');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const promoId = this.getAttribute('data-promo-id');
                    console.log('Button clicked with promo ID:', promoId);

                    // Make a POST request to the PayMongoController with the promo ID
                    fetch('/Paymongo', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ promo_id: promoId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('payment code');
                        console.log(data);
                        if (data.success) {
                            const responseData = JSON.parse(data.data);
                            const checkoutURL = responseData.checkout_url;
                            const checkoutID = data.checkoutId;
                            localStorage.setItem('checkoutID', checkoutID);
                            localStorage.setItem('promoID', promoId); // Store promo ID in local storage
                            console.log('checkoutID :', checkoutID);
                            console.log('Checkout URL:', checkoutURL);
                            window.location.href = checkoutURL;
                        } else {
                            alert("Failed to get checkout URL");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                });
            });
        });
    </script>
</x-layout>