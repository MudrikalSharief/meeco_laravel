<x-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-blue-500 mb-4">Payment Process</h1>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Plan Offers</h2>
            <div class="bg-white shadow-md rounded p-4">
                <label class="block mb-2">
                    <input type="radio" name="plan" value="casual" class="mr-2" checked>
                    Casual
                </label>
                <label class="block mb-2">
                    <input type="radio" name="plan" value="regular" class="mr-2">
                    Regular
                </label>
                <label class="block">
                    <input type="radio" name="plan" value="vip" class="mr-2">
                    VIP
                </label>
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
        
        <a href="{{ route('upgrade.paymentEmail')}}"><button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Continue
        </button></a>
    </div>
    
</x-layout>