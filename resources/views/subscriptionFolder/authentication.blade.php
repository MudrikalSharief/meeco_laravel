<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded p-4">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">GCash</h1>            <label for="code" class="block mb-2">Enter the 6-digit code sent to your registered mobile number</label>
            <input type="text" id="code" name="code" placeholder="6-digit code" class="w-full border border-gray-300 p-2 rounded mb-4">
            <p class="text-sm text-gray-600 mb-4">If you didn't receive the code, you can resend it in 250 seconds.</p>
           <a href="{{route('upgrade.mpin')}}"> <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Next
        </button></a>
        </div>
    </div>
    
</x-layout>