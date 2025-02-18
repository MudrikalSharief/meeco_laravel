<x-admin_layout>
    <main class="py-3">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-left mb-5">{{ isset($promo) ? 'Edit Promo' : 'Add New Promo' }}</h1>
            <form method="POST" action="{{ route('promos.store') }}" class="bg-white p-8 rounded-lg shadow-lg">
                @csrf
                @if(isset($promo))
                    <input type="hidden" name="id" value="{{ $promo->promo_id }}">
                @endif
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Promo Name:</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('name', $promo->name ?? '') }}" required>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-bold mb-2">Pricing:</label>
                    <input type="text" id="price" name="price" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('price', $promo->price ?? '') }}" required>
                </div>
                <div class="mb-4">
                    <label for="duration" class="block text-gray-700 font-bold mb-2">Duration:</label>
                    <input type="text" id="duration" name="duration" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('duration', $promo->duration ?? '') }}" required placeholder="Enter subscription duration (days)">
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Features Available:</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Photo-to-Text Conversion:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="photo_unlimited" name="photo_to_text" value="unlimited" class="mr-2" {{ old('photo_to_text', $promo->photo_to_text ?? '') == 'unlimited' ? 'checked' : '' }}>
                        <label for="photo_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="photo_limited" name="photo_to_text" value="limited" class="mr-2" {{ old('photo_to_text', $promo->photo_to_text ?? '') == 'limited' ? 'checked' : '' }}>
                        <label for="photo_limited" class="mr-2">Limited</label>
                        <input type="text" id="photo_limit" name="photo_limit" class="w-24 p-2 border border-gray-300 rounded-lg {{ old('photo_to_text', $promo->photo_to_text ?? '') == 'limited' ? '' : 'hidden' }}" value="{{ old('photo_limit', $promo->photo_limit ?? '') }}" placeholder="Enter limit">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Reviewer Generator:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="reviewer_unlimited" name="reviewer_generator" value="unlimited" class="mr-2" {{ old('reviewer_generator', $promo->reviewer_generator ?? '') == 'unlimited' ? 'checked' : '' }}>
                        <label for="reviewer_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="reviewer_limited" name="reviewer_generator" value="limited" class="mr-2" {{ old('reviewer_generator', $promo->reviewer_generator ?? '') == 'limited' ? 'checked' : '' }}>
                        <label for="reviewer_limited" class="mr-2">Limited</label>
                        <input type="text" id="reviewer_limit" name="reviewer_limit" class="w-24 p-2 border border-gray-300 rounded-lg {{ old('reviewer_generator', $promo->reviewer_generator ?? '') == 'limited' ? '' : 'hidden' }}" value="{{ old('reviewer_limit', $promo->reviewer_limit ?? '') }}" placeholder="Enter limit">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Mock Quiz Generator:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="mock_quiz_unlimited" name="mock_quiz_generator" value="unlimited" class="mr-2" {{ old('mock_quiz_generator', $promo->mock_quiz_generator ?? '') == 'unlimited' ? 'checked' : '' }}>
                        <label for="mock_quiz_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="mock_quiz_limited" name="mock_quiz_generator" value="limited" class="mr-2" {{ old('mock_quiz_generator', $promo->mock_quiz_generator ?? '') == 'limited' ? 'checked' : '' }}>
                        <label for="mock_quiz_limited" class="mr-2">Limited</label>
                        <input type="text" id="mock_quiz_limit" name="mock_quiz_limit" class="w-24 p-2 border border-gray-300 rounded-lg {{ old('mock_quiz_generator', $promo->mock_quiz_generator ?? '') == 'limited' ? '' : 'hidden' }}" value="{{ old('mock_quiz_limit', $promo->mock_quiz_limit ?? '') }}" placeholder="Enter limit">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Save & Download Reviewer:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="save_reviewer_unlimited" name="save_reviewer" value="unlimited" class="mr-2" {{ old('save_reviewer', $promo->save_reviewer ?? '') == 'unlimited' ? 'checked' : '' }}>
                        <label for="save_reviewer_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="save_reviewer_limited" name="save_reviewer" value="limited" class="mr-2" {{ old('save_reviewer', $promo->save_reviewer ?? '') == 'limited' ? 'checked' : '' }}>
                        <label for="save_reviewer_limited" class="mr-2">Limited</label>
                        <input type="text" id="save_reviewer_limit" name="save_reviewer_limit" class="w-24 p-2 border border-gray-300 rounded-lg {{ old('save_reviewer', $promo->save_reviewer ?? '') == 'limited' ? '' : 'hidden' }}" value="{{ old('save_reviewer_limit', $promo->save_reviewer_limit ?? '') }}" placeholder="Enter limit">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="perks" class="block text-gray-700 font-bold mb-2">Perks:</label>
                    <textarea id="perks" name="perks" class="w-full p-2 border border-gray-300 rounded-lg" rows="3" placeholder="Enter subscription perks">{{ old('perks', $promo->perks ?? '') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 font-bold mb-2">Offer Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('start_date', $promo->start_date ?? '') }}" required>
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 font-bold mb-2">Offer End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('end_date', $promo->end_date ?? '') }}" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Offer Status:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="status_active" name="status" value="active" class="mr-2" {{ old('status', $promo->status ?? '') == 'active' ? 'checked' : '' }}>
                        <label for="status_active" class="mr-4">Active</label>
                        <input type="radio" id="status_inactive" name="status" value="inactive" class="mr-2" {{ old('status', $promo->status ?? '') == 'inactive' ? 'checked' : '' }}>
                        <label for="status_inactive">Inactive</label>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg">Save Offer</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function toggleInput(radioGroup, inputField) {
                const radios = document.querySelectorAll(`input[name="${radioGroup}"]`);
                radios.forEach(radio => {
                    radio.addEventListener("change", function() {
                        if (this.value === "limited") {
                            document.getElementById(inputField).classList.remove("hidden");
                        } else {
                            document.getElementById(inputField).classList.add("hidden");
                        }
                    });
                });
            }

            toggleInput("photo_to_text", "photo_limit");
            toggleInput("reviewer_generator", "reviewer_limit");
            toggleInput("mock_quiz_generator", "mock_quiz_limit");
            toggleInput("save_reviewer", "save_reviewer_limit");
        });
    </script>
</x-admin_layout>