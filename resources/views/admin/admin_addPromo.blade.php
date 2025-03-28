<x-admin_layout>
    <main class="py-6 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ isset($promo) ? 'Edit Promo' : 'Add New Promo' }}
                </h1>
                <a href="{{ route('promos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Promos
                </a>
            </div>
            
            <form method="POST" action="{{ isset($promo) ? route('promos.update', $promo->promo_id) : route('promos.store') }}" class="bg-white rounded-xl shadow-md overflow-hidden" onsubmit="return validateForm()">
                @csrf
                @if(isset($promo))
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $promo->promo_id }}">
                @endif
                
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Promo Details</h2>
                    <p class="mt-1 text-sm text-gray-500">Fill in the information below to {{ isset($promo) ? 'update' : 'create' }} your promo.</p>
                </div>
                
                <div class="p-6">
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Promo Name</label>
                                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('name', $promo->name ?? '') }}">
                                <span id="name_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                @error('name')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Pricing</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" id="price" name="price" class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('price', $promo->price ?? '') }}">
                                </div>
                                <span id="price_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                @error('price')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" id="duration" name="duration" class="w-full pr-12 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('duration', $promo->duration ?? '') }}">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Days</span>
                                    </div>
                                </div>
                                <span id="duration_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                @error('duration')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Limits -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Usage Limits</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="image_limit" class="block text-sm font-medium text-gray-700 mb-1">Image Limit</label>
                                <input type="number" id="image_limit" name="image_limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('image_limit', $promo->image_limit ?? '') }}">
                                @error('image_limit')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="reviewer_limit" class="block text-sm font-medium text-gray-700 mb-1">Reviewer Limit</label>
                                <input type="number" id="reviewer_limit" name="reviewer_limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('reviewer_limit', $promo->reviewer_limit ?? '') }}">
                                @error('reviewer_limit')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="quiz_limit" class="block text-sm font-medium text-gray-700 mb-1">Quiz Limit</label>
                                <input type="number" id="quiz_limit" name="quiz_limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('quiz_limit', $promo->quiz_limit ?? '') }}">
                                @error('quiz_limit')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="quiz_questions_limit" class="block text-sm font-medium text-gray-700 mb-1">Questions per Quiz Limit</label>
                                <input type="number" id="quiz_questions_limit" name="quiz_questions_limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('quiz_questions_limit', $promo->quiz_questions_limit ?? '') }}">
                                @error('quiz_questions_limit')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mix Quiz Options -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Mix Quiz Options</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mix Quiz Type</label>
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="can_mix_quiz" value="1" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ old('can_mix_quiz', $promo->can_mix_quiz ?? '') == '1' ? 'checked' : '' }} onclick="toggleMixQuizLimit(true)">
                                        <span class="ml-2 text-sm text-gray-700">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="can_mix_quiz" value="0" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ old('can_mix_quiz', $promo->can_mix_quiz ?? '') == '0' ? 'checked' : '' }} onclick="toggleMixQuizLimit(false)">
                                        <span class="ml-2 text-sm text-gray-700">No</span>
                                    </label>
                                </div>
                                @error('can_mix_quiz')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="mix_quiz_limit" class="block text-sm font-medium text-gray-700 mb-1">Questions per Mix Quiz Limit</label>
                                <input type="number" id="mix_quiz_limit" name="mix_quiz_limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('mix_quiz_limit', $promo->mix_quiz_limit ?? '') }}">
                                <span id="mix_quiz_limit_message" class="text-amber-600 text-xs mt-1 hidden">This field does not apply when Mix Quiz is disabled.</span>
                                @error('mix_quiz_limit')
                                    <span class="text-red-500 text-xs mt-1 mix-quiz-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Perks -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Perks & Benefits</h3>
                        <div>
                            <label for="perks" class="block text-sm font-medium text-gray-700 mb-1">Perks Description</label>
                            <textarea id="perks" name="perks" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Enter subscription perks (one per line)">{{ old('perks', $promo->perks ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Describe the benefits users will receive with this promo. Each line will be displayed as a separate perk.</p>
                            <span id="perks_error" class="text-red-500 text-xs mt-1 hidden"></span>
                            @error('perks')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Availability -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Availability</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Offer Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('start_date', isset($promo->start_date) ? \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') : '') }}">
                                @error('start_date')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Offer End Date</label>
                                <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" value="{{ old('end_date', isset($promo->end_date) ? \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Status</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Offer Status</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="active" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out" {{ old('status', $promo->status ?? '') == 'active' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="inactive" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out" {{ old('status', $promo->status ?? '') == 'inactive' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                            @error('status')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="button" onclick="window.history.back()" class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            {{ isset($promo) ? 'Update Promo' : 'Create Promo' }}
                        </button>
                    </div>
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
            
            if (!enable) {
                mixQuizLimitMessage.classList.remove('hidden');
                mixQuizLimitInput.classList.add('bg-gray-100');
            } else {
                mixQuizLimitMessage.classList.add('hidden');
                mixQuizLimitInput.classList.remove('bg-gray-100');
            }
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