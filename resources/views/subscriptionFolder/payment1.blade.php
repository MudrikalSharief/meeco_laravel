<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">GCash</h1>
            <div class="mb-4">
                <p class="text-lg font-semibold">Merchant: <span class="font-normal">Meèco</span></p>
            </div>
            <div class="mb-4">
                <p class="text-lg font-semibold">Pay with</p>
                <p class="text-lg font-normal">Gcash PHP 1000.00 <br><span class="text-sm text-gray-600">Available Balance</span></p>
            </div>
            <div class="mb-4">
                <p class="text-lg font-semibold">You're about to pay</p>
                <p class="text-lg font-normal">Amount: PHP 27.00 <br>Total: PHP 27.00</p>
            </div>
            <div class="mb-6">
                <p class="text-sm text-gray-700 mb-4">Please review to ensure that the details are correct before you proceed.</p>
                <a href="{{route('upgrade.receipt')}}"><button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    NEXT
                </button></a>
            </div>
        </div>
    </div>
    
    
</x-layout>