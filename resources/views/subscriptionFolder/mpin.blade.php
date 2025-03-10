<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-md rounded-lg p-6 w-96">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-600 text-center">GCash</h1>
            <label for="mpin" class="block mb-2 text-center">Enter your 4-digit MPIN</label>
            
            <div class="flex justify-center mb-4 space-x-2">
                <input type="password" id="mpin1" name="mpin1" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="validateAndMoveFocus(1)" required />
                <input type="password" id="mpin2" name="mpin2" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="validateAndMoveFocus(2)" required />
                <input type="password" id="mpin3" name="mpin3" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="validateAndMoveFocus(3)" required />
                <input type="password" id="mpin4" name="mpin4" maxlength="1" 
                       class="w-12 h-12 border border-gray-300 text-center text-lg p-2 rounded"
                       oninput="validateAndMoveFocus(4)" required />
            </div>

            <a href="{{ route('upgrade.payment1', ['promo_id' => $promo->promo_id]) }}" class="block" id="next-link">
                <button class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
                    NEXT
                </button>
            </a>
        </div>
    </div>

    <script>
        function validateAndMoveFocus(current) {
            const currentInput = document.getElementById(`mpin${current}`);
            const nextInput = document.getElementById(`mpin${current + 1}`);
            if (isNaN(currentInput.value) || currentInput.value < 0 || currentInput.value.length !== 1) {
                currentInput.value = '';
                alert('Please enter a valid number.');
            } else if (nextInput) {
                nextInput.focus();
            }
        }

        document.getElementById('next-link').addEventListener('click', function(event) {
            for (let i = 1; i <= 4; i++) {
                if (!document.getElementById(`mpin${i}`).value) {
                    event.preventDefault();
                    alert('Please enter your 4-digit MPIN.');
                    return;
                }
            }
        });
    </script>
</x-layout>
