<x-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-blue-500 mb-4">Payment Process</h1>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Plan Offers</h2>
            <div class="bg-white shadow-md rounded p-4">
                <p class="text-lg font-semibold">{{ $promo->name }}</p>
                <p class="text-lg font-normal">Price: ₱{{ $promo->price }}</p>
                <p class="text-lg font-normal">Duration: {{ $promo->duration }} days</p>
                <p class="text-lg font-normal">Perks: {{ $promo->perks }}</p>
            </div>
        </div>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Payment Method</h2>
            <div class="bg-white shadow-md rounded p-4">
                <label class="block">
                    <input type="radio" name="payment_method" value="gcash" class="mr-2" checked>
                    Gcash
                </label>
            </div>
        </div>
        
        <a href="{{ route('upgrade.paymentEmail', ['promo_id' => $promo->promo_id]) }}">
            <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Continue
            </button>
        </a>
    </div>
</x-layout>