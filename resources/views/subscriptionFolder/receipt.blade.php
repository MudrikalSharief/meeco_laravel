<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
            <div class="flex justify-center items-center mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-6 text-green-600">Payment Success!</h1>
            <p class="mb-2">Amount Paid: Php {{ $promo->price }}</p>
            <p class="mb-2">Reference Number: {{ $referenceNumberValue }}</p>
            <p class="mb-2">Payment Time: {{ now()->format('d-m-Y, H:i') }}</p>
            <p class="mb-2">Payment Method: Gcash</p>
            <p class="mb-2">Sender Name: {{ $userName }}</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-6" onclick="redirectToCapture()">Finish Transaction</button>
            
            <!-- Modal -->
            <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg p-4 text-center max-w-[18rem] w-full mx-4 sm:max-w-[20rem] shadow-xl modal-shift-right">
                    <div class="flex justify-center items-center mb-4">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-extrabold mb-4 text-blue-800">Premium</h2>
                    <div class="text-lg font-semibold mb-2">{{ $promo->name }}</div>
                    <div class="text-base mb-4">You've got upgraded to {{ $promo->name }}!</div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="hideModal()">Close</button>
                </div>
            </div>

            <script>
                function showModal() {
                    document.getElementById('modal').classList.remove('hidden');
                }

                function hideModal() {
                    document.getElementById('modal').classList.add('hidden');
                }

                function redirectToCapture() {
                    window.location.href = "{{ route('capture') }}";
                }
            </script>
        </div>
    </div>

    <style>
        .modal-shift-right {
            transform: translateX(30px);
        }
    </style>
</x-layout>
