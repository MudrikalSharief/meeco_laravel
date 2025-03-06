<x-admin_layout>
    <main class="py-3">
        <div class="container mx-auto px-4 sm:max-w-full md:max-w-2xl">
            <h1 class="text-xl sm:text-lg md:text-2xl font-bold text-left mb-5">
                {{ isset($promo) ? 'Edit Promo' : 'Add New Promo' }}
            </h1>
            
            <form method="POST" action="{{ isset($promo) ? route('promos.update', $promo->promo_id) : route('promos.store') }}" class="bg-white sm:p-3 md:p-6 rounded-lg shadow-lg">
                @csrf
                @if(isset($promo))
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $promo->promo_id }}">
                @endif
                
                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700 font-bold mb-1 sm:text-sm">Promo Name:</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('name', $promo->name ?? '') }}" required>
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 font-bold mb-1 sm:text-sm">Pricing:</label>
                        <input type="text" id="price" name="price" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('price', $promo->price ?? '') }}" required>
                    </div>
                    <div>
                        <label for="duration" class="block text-gray-700 font-bold mb-1 sm:text-sm">Duration (Days):</label>
                        <input type="text" id="duration" name="duration" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('duration', $promo->duration ?? '') }}" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="perks" class="block text-gray-700 font-bold mb-1 sm:text-sm">Perks:</label>
                    <textarea id="perks" name="perks" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" rows="3" placeholder="Enter subscription perks">{{ old('perks', $promo->perks ?? '') }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 font-bold mb-1 sm:text-sm">Offer Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('start_date', isset($promo->start_date) ? \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') : '') }}" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-bold mb-1 sm:text-sm">Offer End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('end_date', isset($promo->end_date) ? \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') : '') }}" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-gray-700 font-bold mb-1 sm:text-sm">Offer Status:</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center text-sm">
                            <input type="radio" name="status" value="active" class="mr-2" {{ old('status', $promo->status ?? '') == 'active' ? 'checked' : '' }}>
                            Active
                        </label>
                        <label class="inline-flex items-center text-sm">
                            <input type="radio" name="status" value="inactive" class="mr-2" {{ old('status', $promo->status ?? '') == 'inactive' ? 'checked' : '' }}>
                            Inactive
                        </label>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-600 transform hover:scale-105 transition text-sm md:text-base">Save Offer</button>
                </div>
            </form>
        </div>
    </main>
    </x-admin_layout>
