<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-md w-full">
            <h1 class="text-2xl font-bold text-blue-500 mb-4 text-center">Email Verification</h1>
            
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2 text-center">Verification Details</h2>
                <div class="bg-gray-50 shadow-md rounded p-4">
                    <p class="mb-2">Total Payment: ₱{{ $promo->price }}</p>
                    <p class="mb-2">Payment Method: Gcash</p>
                    <label for="email" class="block mb-2">Email Account</label>
                    <input type="email" id="email" name="email" placeholder="Email Address" class="w-full border border-gray-300 p-2 rounded mb-4" required>
                    <p class="text-sm text-gray-600 mb-4 text-center">Please fill in and confirm your email address. A confirmation email will be sent when your payment is confirmed.</p>
                    <div class="text-center">
                        <a href="{{ route('upgrade.gcashNumber', ['promo_id' => $promo->promo_id]) }}" id="verify-link">
                            <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
                                Verify
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('verify-link').addEventListener('click', function(event) {
            var emailInput = document.getElementById('email');
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value) {
                event.preventDefault();
                alert('Please enter your email address.');
            } else if (!emailPattern.test(emailInput.value)) {
                event.preventDefault();
                alert('Please enter a valid email address.');
            }
        });
    </script>
</x-layout>
