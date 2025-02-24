<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-md rounded-lg p-6 w-96">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-600 text-center">GCash</h1>
            <label for="mpin" class="block mb-2 text-center">Enter your 4-digit MPIN</label>
            
            <div class="flex justify-center mb-4 space-x-2">
                <input type="password" id="mpin1" name="mpin1" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="moveFocus(1)" />
                <input type="password" id="mpin2" name="mpin2" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="moveFocus(2)" />
                <input type="password" id="mpin3" name="mpin3" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="moveFocus(3)" />
                <input type="password" id="mpin4" name="mpin4" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="moveFocus(4)" />
            </div>

            <a href="{{ route('upgrade.payment1', ['promo_id' => $promo->promo_id]) }}" class="block">
                <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
                    NEXT
                </button>
            </a>
        </div>
    </div>

    <script>
        function moveFocus(current) {
            const nextInput = document.getElementById(`mpin${current + 1}`);
            if (nextInput && document.getElementById(`mpin${current}`).value.length === 1) {
                nextInput.focus();
            }
        }
    </script>
</x-layout>
