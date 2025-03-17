<x-admin_layout>
    <main class="py-3">
        <div class="container mx-auto px-4 sm:max-w-full md:max-w-2xl">
            <h1 class="text-xl sm:text-lg md:text-2xl font-bold text-left mb-5">
                {{ isset($promo) ? 'Edit Promo' : 'Add New Promo' }}
            </h1>
            
            <form method="POST" action="{{ isset($promo) ? route('promos.update', $promo->promo_id) : route('promos.store') }}" class="bg-white sm:p-3 md:p-6 rounded-lg shadow-lg" onsubmit="return validateForm()">
                @csrf
                @if(isset($promo))
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $promo->promo_id }}">
                @endif
                
                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700 font-bold mb-1 sm:text-sm">Promo Name:</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('name', $promo->name ?? '') }}">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 font-bold mb-1 sm:text-sm">Pricing:</label>
                        <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('price', $promo->price ?? '') }}">
                        @error('price')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="duration" class="block text-gray-700 font-bold mb-1 sm:text-sm">Duration (Days):</label>
                        <input type="number" id="duration" name="duration" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('duration', $promo->duration ?? '') }}">
                        @error('duration')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="image_limit" class="block text-gray-700 font-bold mb-1 sm:text-sm">Image limit : </label>
                        <input type="number" id="image_limit" name="image_limit" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('image_limit', $promo->image_limit ?? '') }}">
                        @error('image_limit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="reviewer_limit" class="block text-gray-700 font-bold mb-1 sm:text-sm">Reviewer limit : </label>
                        <input type="number" id="reviewer_limit" name="reviewer_limit" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('reviewer_limit', $promo->reviewer_limit ?? '') }}">
                        @error('reviewer_limit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="quiz_limit" class="block text-gray-700 font-bold mb-1 sm:text-sm">Quiz limit : </label>
                        <input type="number" id="quiz_limit" name="quiz_limit" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('quiz_limit', $promo->quiz_limit ?? '') }}">
                        @error('quiz_limit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="quiz_questions_limit" class="block text-gray-700 font-bold mb-1 sm:text-sm">Question per Quiz limit : </label>
                        <input type="number" id="quiz_questions_limit" name="quiz_questions_limit" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('quiz_questions_limit', $promo->quiz_questions_limit ?? '') }}">
                        @error('quiz_questions_limit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="mt-4">
                            <label for="can_mix_quiz" class="block text-gray-700 font-bold mb-1 sm:text-sm">Mix Quiz Type : </label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center text-sm">
                                    <input type="radio" name="can_mix_quiz" value="1" class="mr-2" {{ old('can_mix_quiz', $promo->can_mix_quiz ?? '') == '1' ? 'checked' : '' }} onclick="toggleMixQuizLimit(true)">
                                    Yes
                                </label>
                                <label class="inline-flex items-center text-sm">
                                    <input type="radio" name="can_mix_quiz" value="0" class="mr-2" {{ old('can_mix_quiz', $promo->can_mix_quiz ?? '') == '0' ? 'checked' : '' }} onclick="toggleMixQuizLimit(false)">
                                    No
                                </label>
                            </div>
                            @error('can_mix_quiz')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="mix_quiz_limit" class="block text-gray-700 font-bold mb-1 sm:text-sm">Question per Mix Quiz limit : </label>
                        <input type="number" id="mix_quiz_limit" name="mix_quiz_limit" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('mix_quiz_limit', $promo->mix_quiz_limit ?? '') }}">
                        <span id="mix_quiz_limit_message" class="text-red-500 text-sm hidden">This field does not apply.</span>
                        @error('mix_quiz_limit')
                            <span class="text-red-500 text-sm mix-quiz-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="perks" class="block text-gray-700 font-bold mb-1 sm:text-sm">Perks:</label>
                    <textarea id="perks" name="perks" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" rows="3" placeholder="Enter subscription perks">{{ old('perks', $promo->perks ?? '') }}</textarea>
                    <span id="perks_error" class="text-red-500 text-sm hidden"></span>
                    @error('perks')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                
                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 font-bold mb-1 sm:text-sm">Offer Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('start_date', isset($promo->start_date) ? \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') : '') }}">
                        @error('start_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-bold mb-1 sm:text-sm">Offer End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" value="{{ old('end_date', isset($promo->end_date) ? \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
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
                    @error('status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-600 transform hover:scale-105 transition text-sm md:text-base">Save Offer</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function validateForm() {
            let isValid = true;

            let name = document.getElementById('name');
            let price = document.getElementById('price');
            let duration = document.getElementById('duration');
            let perks = document.getElementById('perks');
            let mixQuizLimit = document.getElementById('mix_quiz_limit');
            let canMixQuiz = document.querySelector('input[name="can_mix_quiz"]:checked').value;

            let nameError = document.getElementById('name_error');
            let priceError = document.getElementById('price_error');
            let durationError = document.getElementById('duration_error');
            let perksError = document.getElementById('perks_error');
            let mixQuizLimitMessage = document.getElementById('mix_quiz_limit_message');
            let mixQuizLimitError = document.querySelector('.mix-quiz-error');

            nameError.classList.add('hidden');
            priceError.classList.add('hidden');
            durationError.classList.add('hidden');
            perksError.classList.add('hidden');
            mixQuizLimitMessage.classList.add('hidden');
            if (mixQuizLimitError) {
                mixQuizLimitError.classList.add('hidden');
            }

            if (!name.value.trim()) {
                nameError.textContent = "Promo Name is required.";
                nameError.classList.remove('hidden');
                isValid = false;
            } else if (name.value.length < 3 || name.value.length > 50) {
                nameError.textContent = "Promo Name must be between 3 and 50 characters.";
                nameError.classList.remove('hidden');
                isValid = false;
            }

            if (!price.value.trim()) {
                priceError.textContent = "Pricing is required.";
                priceError.classList.remove('hidden');
                isValid = false;
            } else if (isNaN(price.value) || parseFloat(price.value) <= 0) {
                priceError.textContent = "Pricing must be a valid positive number.";
                priceError.classList.remove('hidden');
                isValid = false;
            }

            if (!duration.value.trim()) {
                durationError.textContent = "Duration is required.";
                durationError.classList.remove('hidden');
                isValid = false;
            } else if (isNaN(duration.value) || parseInt(duration.value) < 1 || parseInt(duration.value) > 365) {
                durationError.textContent = "Duration must be between 1 and 365 days.";
                durationError.classList.remove('hidden');
                isValid = false;
            }

            if (!perks.value.trim()) {
                perksError.textContent = "Perks field cannot be empty.";
                perksError.classList.remove('hidden');
                isValid = false;
            } else if (perks.value.length < 5) {
                perksError.textContent = "Perks must be at least 5 characters.";
                perksError.classList.remove('hidden');
                isValid = false;
            }

            if (canMixQuiz == '0') {
                mixQuizLimitMessage.classList.remove('hidden');
                if (mixQuizLimitError) {
                    mixQuizLimitError.classList.add('hidden');
                }
            }

            return isValid;
        }

        function toggleMixQuizLimit(enable) {
            const mixQuizLimitInput = document.getElementById('mix_quiz_limit');
            const mixQuizLimitMessage = document.getElementById('mix_quiz_limit_message');
            mixQuizLimitInput.disabled = !enable;
            mixQuizLimitInput.required = enable;
            mixQuizLimitMessage.classList.toggle('hidden', enable);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const canMixQuiz = document.querySelector('input[name="can_mix_quiz"]:checked').value;
            toggleMixQuizLimit(canMixQuiz == '1');
        });

        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.getElementById('end_date');
            const minEndDate = new Date(startDate);
            minEndDate.setDate(minEndDate.getDate() + 1);
            endDateInput.min = minEndDate.toISOString().split('T')[0];
        });
    </script>
</x-admin_layout>
