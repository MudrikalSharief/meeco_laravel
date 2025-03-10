<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-lg rounded-lg p-6 w-96 text-center border border-gray-300">
            <div class="flex justify-center items-center mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Receipt</h1>
            <hr class="border-gray-300 mb-4">

            <div class="text-left text-sm font-medium">
                <p class="mb-2 flex justify-between"><span>Amount Paid:</span> <span>Php {{ $promo->price ?? 'N/A' }}</span></p>
                <p class="mb-2 flex justify-between"><span>Reference No.:</span> <span>{{ $subscription->reference_number }}</span></p>
                <p class="mb-2 flex justify-between"><span>Payment Time:</span> <span>{{ now()->format('d-m-Y, H:i') }}</span></p>
                <p class="mb-2 flex justify-between"><span>Payment Method:</span> <span>GCash</span></p>
                <p class="mb-2 flex justify-between"><span>Sender Name:</span> <span>{{ $userName ?? 'Unknown' }}</span></p>
            </div>

            <hr class="border-gray-300 my-4">

            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" onclick="showModal()">Finish Transaction</button>

            <!-- Receipt Modal -->
            <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg p-4 text-center w-80 shadow-xl border border-gray-300">
                    <div class="flex justify-center items-center mb-4">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-extrabold mb-2 text-blue-800">Premium Upgrade</h2>
                    <p class="text-gray-700 mb-2 text-sm">You've been upgraded to <strong>{{ $promo->name ?? 'Unknown Plan' }}</strong>!</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" onclick="hideModal()">Close</button>
                </div>
            </div>

            <script>
                function showModal() {
                    document.getElementById('modal').classList.remove('hidden');
                }

                function hideModal() {
                    document.getElementById('modal').classList.add('hidden');
                    window.location.href = "{{ route('capture') }}";
                }
            </script>
        </div>
    </div>
</x-layout>
