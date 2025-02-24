<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-md rounded-lg p-6 w-96">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800 text-center">GCash</h1>
            <p class="mb-2 text-center">Merchant: Meeco</p>
            <p class="mb-2 text-center">Promo: {{ $promo->name ?? 'N/A' }}</p>
            <p class="mb-4 text-center">Amount Due: PHP {{ $promo->price ?? 'N/A' }}</p>

            <label for="mobile" class="block mb-2 text-center">Log in to pay with GCash</label>
            <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" 
                   class="w-full border border-gray-300 p-2 rounded mb-4 text-center">

            @if(isset($promo) && $promo->promo_id)
                <a href="{{ route('upgrade.mpin', ['promo_id' => $promo->promo_id]) }}" class="block">
                    <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">Next</button>
                </a>
            @else
                <p class="text-red-500 text-center">Promo not found. Please try again.</p>
            @endif
        </div>
    </div>
</x-layout>
