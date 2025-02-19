<x-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded p-4">
            <h1 class="text-3xl font-extrabold mb-6 text-blue-800">Payment Confirmation</h1>
            <p class="mb-2">Promo: {{ $promo->name }}</p>
            <p class="mb-2">Amount Due: PHP {{ $promo->price }}</p>
            <p class="mb-2">Duration: {{ $promo->duration }} days</p>
            <p class="mb-2">Perks: {{ $promo->perks }}</p>
            <form action="{{ route('upgrade.receipt', ['promo_id' => $promo->promo_id]) }}" method="GET">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Confirm Payment
                </button>
            </form>
        </div>
    </div>
</x-layout>