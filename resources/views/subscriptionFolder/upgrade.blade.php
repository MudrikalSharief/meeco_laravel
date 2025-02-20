<x-layout>
    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <button class="text-xl hover:text-gray-200 transition-colors duration-300">&larr;</button>
                <span class="flex items-center text-xl font-semibold">
                    PREMIUM OFFERS
                </span>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="flex-grow overflow-auto pb-16">
            <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 justify-items-center">
                @foreach($promos as $promo)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 transform hover:scale-105 w-full sm:w-56 mx-1">
                    <div class="bg-blue-600 text-white text-center py-2 rounded-t-lg">
                        <span class="font-semibold text-lg">{{ $promo->name }}</span>
                    </div>
                    <div class="p-4">
                        <p class="text-center font-bold text-gray-700">ONLY ₱{{ $promo->price }}</p>
                        <ul class="mt-2 text-gray-600 list-disc list-inside">
                            <li>{{ $promo->duration }} days access</li>
                            <li>{{ $promo->perks }}</li>
                        </ul>
                    </div>
                    <div class="flex justify-center pb-4">
                        <a href="{{ route('upgrade.payment', ['promo_id' => $promo->promo_id]) }}" class="bg-blue-500 text-white rounded-lg py-2 px-6 font-semibold hover:bg-blue-800">
                            Subscribe to {{ $promo->name }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @foreach($promos as $promo)
    <!-- Modal for each promo -->
    <div id="modal-{{ $promo->promo_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-80 rounded-lg shadow-lg relative p-6">
            <button onclick="closeModal('{{ $promo->promo_id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-xl font-semibold text-blue-500 mt-4">{{ $promo->name }}</h3>
            <p class="text-gray-600 mt-2">Pay once in advance. Does not auto-renew.</p>
            <p class="text-gray-900 font-bold text-lg mt-4">₱ {{ $promo->price }}</p>
            <div class="mt-4">
                <a href="{{ route('upgrade.payment', ['promo_id' => $promo->promo_id]) }}">
                    <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg w-full mt-6 hover:bg-blue-600 transition-colors">
                        Subscribe
                    </button>
                </a>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        function openModal(promoId) {
            document.getElementById('modal-' + promoId).classList.remove('hidden');
        }

        function closeModal(promoId) {
            document.getElementById('modal-' + promoId).classList.add('hidden');
        }
    </script>
</x-layout>
