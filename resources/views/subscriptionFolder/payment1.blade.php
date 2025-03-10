<x-layout>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 pt-16">
        <div class="bg-white shadow-md rounded-lg p-6 w-96 text-center">
            <h1 class="text-2xl font-extrabold mb-6 text-blue-600">Payment Confirmation</h1>
            <hr class="border-gray-300 mb-4">
            <p class="mb-2 text-left pl-8"><strong>Promo:</strong> {{ $promo->name }}</p>
            <p class="mb-2 text-left pl-8"><strong>Amount Due:</strong> PHP {{ $promo->price }}</p>
            <p class="mb-2 text-left pl-8"><strong>Duration:</strong> {{ $promo->duration }} days</p>
            <p class="mb-4 text-left pl-8"><strong>Perks:</strong> {{ $promo->perks }}</p>

            <form action="{{ route('upgrade.receipt', ['promo_id' => $promo->promo_id]) }}" method="GET">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
                    Confirm Payment
                </button>
            </form>
        </div>
    </div>
</x-layout>
