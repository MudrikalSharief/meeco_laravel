<x-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-blue-500 mb-4">Payment Process</h1>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Email Verification</h2>
            <div class="bg-white shadow-md rounded p-4">
                <p class="mb-2">Total Payment: ₱{{ $promo->price }}</p>
                <p class="mb-2">Payment Method: Gcash</p>
                <label for="email" class="block mb-2">Email Account</label>
                <input type="email" id="email" name="email" placeholder="Email Address" class="w-full border border-gray-300 p-2 rounded mb-4">
                <p class="text-sm text-gray-600 mb-4">Please fill in and confirm your email address. A confirmation email will be sent when your payment is confirmed.</p>
                <a href="{{ route('upgrade.gcashNumber', ['promo_id' => $promo->promo_id]) }}">
                    <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Verify
                    </button>
                </a>
            </div>
        </div>
    </div>
</x-layout>