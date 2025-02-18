<x-admin_layout>
    <main class="py-3">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-left mb-5">
                {{ isset($promo) ? 'Edit Promo' : 'Add New Promo' }}
            </h1>
            
            <form method="POST" action="{{ route('promos.store') }}" class="bg-white p-6 rounded-lg shadow-lg">
                @csrf
                @if(isset($promo))
                    <input type="hidden" name="id" value="{{ $promo->promo_id }}">
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700 font-bold mb-1">Promo Name:</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('name', $promo->name ?? '') }}" required>
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 font-bold mb-1">Pricing:</label>
                        <input type="text" id="price" name="price" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('price', $promo->price ?? '') }}" required>
                    </div>
                    <div>
                        <label for="duration" class="block text-gray-700 font-bold mb-1">Duration (Days):</label>
                        <input type="text" id="duration" name="duration" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('duration', $promo->duration ?? '') }}" required>
                    </div>
                </div>
                
                <h2 class="text-2xl font-semibold text-gray-800 my-4">Features Available:</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([
                        'photo_to_text' => 'Photo-to-Text Conversion',
                        'reviewer_generator' => 'Reviewer Generator',
                        'mock_quiz_generator' => 'Mock Quiz Generator',
                        'save_reviewer' => 'Save & Download Reviewer'
                    ] as $key => $label)
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">{{ $label }}:</label>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="{{ $key }}" value="unlimited" class="mr-2" {{ old($key, $promo->$key ?? '') == 'unlimited' ? 'checked' : '' }}>
                                    Unlimited
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="{{ $key }}" value="limited" class="mr-2" {{ old($key, $promo->$key ?? '') == 'limited' ? 'checked' : '' }}>
                                    Limited
                                </label>
                                <input type="text" name="{{ $key }}_limit" class="w-20 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 {{ old($key, $promo->$key ?? '') == 'limited' ? '' : 'hidden' }}" value="{{ old($key.'_limit', $promo->{$key.'_limit'} ?? '') }}" placeholder="Limit">
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <label for="perks" class="block text-gray-700 font-bold mb-1">Perks:</label>
                    <textarea id="perks" name="perks" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter subscription perks">{{ old('perks', $promo->perks ?? '') }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 font-bold mb-1">Offer Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('start_date', $promo->start_date ?? '') }}" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-bold mb-1">Offer End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('end_date', $promo->end_date ?? '') }}" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-gray-700 font-bold mb-1">Offer Status:</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="active" class="mr-2" {{ old('status', $promo->status ?? '') == 'active' ? 'checked' : '' }}>
                            Active
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="inactive" class="mr-2" {{ old('status', $promo->status ?? '') == 'inactive' ? 'checked' : '' }}>
                            Inactive
                        </label>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-600 transform hover:scale-105 transition">Save Offer</button>
                </div>
            </form>
        </div>
    </main>
</x-admin_layout>
