<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded p-4">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">GCash</h1>
            <label for="mpin" class="block mb-2">Enter your 4-digit MPIN</label>
            <div class="flex justify-start mb-4 space-x-2"> <!-- Added space-x-2 to add padding between inputs -->
                <input type="password" id="mpin1" name="mpin1" maxlength="1" class="w-10 border border-gray-300 text-center p-2 rounded" />
                <input type="password" id="mpin2" name="mpin2" maxlength="1" class="w-10 border border-gray-300 text-center p-2 rounded" />
                <input type="password" id="mpin3" name="mpin3" maxlength="1" class="w-10 border border-gray-300 text-center p-2 rounded" />
                <input type="password" id="mpin4" name="mpin4" maxlength="1" class="w-10 border border-gray-300 text-center p-2 rounded" />
            </div>
            <a href="{{route('upgrade.payment1')}}"><button class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
                NEXT
            </button></a>
        </div>
    </div>
</x-layout>
