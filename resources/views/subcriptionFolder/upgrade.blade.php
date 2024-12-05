<x-layout>
    <div class="px-4 py-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <button class="text-xl hover:text-gray-200 transition-colors duration-300">&larr;</button>
                <span class="flex items-center text-xl font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.094 6.26L22 9.25l-4.758 4.642L18.508 22 12 18.618 5.492 22l1.266-8.108L2 9.25l6.906-1.99L12 2z" />
                    </svg>
                    PREMIUM OFFERS
                </span>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="grid gap-6 lg:grid-cols-3 sm:grid-cols-1">
            <!-- Casual Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 transform hover:scale-105">
                <div class="bg-blue-600 text-white text-center py-2 rounded-t-lg">
                    <span class="font-semibold text-lg">Casual</span>
                </div>
                <div class="p-4">
                    <p class="text-center font-bold text-gray-700">ONLY ₱27 for a week</p>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>A week access to all features</li>
                        <li>Lorem ipsum</li>
                    </ul>
                </div>
                <div class="flex justify-center pb-4">
                    <button id="open-casual-modal" class="bg-blue-500 text-white rounded-lg py-2 px-6 font-semibold hover:bg-blue-800">
                        Subscribe to Casual
                    </button>
                </div>
            </div>

            <!-- Regular Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 transform hover:scale-105">
                <div class="bg-blue-600 text-white text-center py-2 rounded-t-lg">
                    <span class="font-semibold text-lg">Regular</span>
                </div>
                <div class="p-4">
                    <p class="text-center font-bold text-gray-700">ONLY ₱99 for a month</p>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>A month access to all features</li>
                        <li>Lorem ipsum</li>
                    </ul>
                </div>
                <div class="flex justify-center pb-4">
                    <button id="open-regular-modal" class="bg-blue-500 text-white rounded-lg py-2 px-6 font-semibold hover:bg-blue-800">
                        Subscribe to Regular
                    </button>
                </div>
            </div>

            <!-- VIP Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 transform hover:scale-105">
                <div class="bg-blue-600 text-white text-center py-2 rounded-t-lg">
                    <span class="font-semibold text-lg">VIP</span>
                </div>
                <div class="p-4">
                    <p class="text-center font-bold text-gray-700">ONLY ₱999 annually</p>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>A year access to all features</li>
                        <li>Lorem ipsum benefits</li>
                    </ul>
                </div>
                <div class="flex justify-center pb-4">
                    <button id="open-vip-modal" class="bg-blue-500 text-white rounded-lg py-2 px-6 font-semibold hover:bg-blue-800">
                        Subscribe to VIP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Section -->
    <!-- Casual Modal -->
    <div id="casual-subscription-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-80 rounded-lg shadow-lg relative p-6 px-4 transform transition-all duration-300">
            <button id="close-casual-modal-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-xl font-semibold text-blue-500 mt-4">Casual</h3> <!-- Title color updated -->
            <p class="text-gray-600 mt-2">Pay once in advance. Does not auto-renew.</p>
            <p class="text-gray-900 font-bold text-lg mt-4">₱ 27 for 1 week</p>
            <div class="mt-4">
                <a href="{{ route('upgrade.payment') }}">
                    <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg w-full mt-6 hover:bg-blue-600 transition-colors">
                        Subscribe
                    </button>
                </a>
            </div>
        </div>
    </div>

    <!-- Regular Modal -->
    <div id="regular-subscription-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-80 rounded-lg shadow-lg relative p-6 px-4">
            <button id="close-regular-modal-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-xl font-semibold text-blue-500 mt-4">Regular</h3> <!-- Title color updated -->
            <p class="text-gray-600 mt-2">Pay once in advance. Does not auto-renew.</p>
            <p class="text-gray-900 font-bold text-lg mt-4">₱ 99 for 1 month</p>
            <div class="mt-4">
                <a href="{{ route('upgrade.payment') }}">
                    <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg w-full mt-6 hover:bg-blue-600 transition-colors">
                        Subscribe
                    </button>
                </a>
            </div>
        </div>
    </div>

    <!-- VIP Modal -->
    <div id="vip-subscription-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-80 rounded-lg shadow-lg relative p-6 px-4">
            <button id="close-vip-modal-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-xl font-semibold text-blue-500 mt-4">VIP</h3> <!-- Title color updated -->
            <p class="text-gray-600 mt-2">Pay once in advance. Does not auto-renew.</p>
            <p class="text-gray-900 font-bold text-lg mt-4">₱ 999 annually</p>
            <div class="mt-4">
                <a href="{{ route('upgrade.payment') }}">
                    <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg w-full mt-6 hover:bg-blue-600 transition-colors">
                        Subscribe
                    </button>
                </a>
            </div>
        </div>
    </div>

    @vite(['resources/js/subscription.js'])
</x-layout>
