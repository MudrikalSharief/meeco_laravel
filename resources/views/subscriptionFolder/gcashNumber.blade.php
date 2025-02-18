<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded p-4">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">GCash</h1>            <p class="mb-2">Merchant: Meeco</p>
            <p class="mb-2">Amount Due: PHP 27.00</p>
            <label for="mobile" class="block mb-2">Log in to pay with GCash</label>
            <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" class="w-full border border-gray-300 p-2 rounded mb-4">
            <a href="{{route('upgrade.authentication')}}"><button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Next</button></a>
        </div>
    </div>
    
    
</x-layout>