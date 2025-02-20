<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded p-4">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">GCash</h1>
            <p class="mb-2">Merchant: Meeco</p>
            <p class="mb-2">Promo: {{ $promo->name ?? 'N/A' }}</p>
            <p class="mb-2">Amount Due: PHP {{ $promo->price ?? 'N/A' }}</p>

            <label for="mobile" class="block mb-2">Log in to pay with GCash</label>
            <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" 
                   class="w-full border border-gray-300 p-2 rounded mb-4">

            @if(isset($promo) && $promo->promo_id)
                <a href="{{ route('upgrade.mpin', ['promo_id' => $promo->promo_id]) }}">
                    <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Next</button>
                </a>
            @else
                <p class="text-red-500">Promo not found. Please try again.</p>
            @endif
        </div>
    </div>
</x-layout>
