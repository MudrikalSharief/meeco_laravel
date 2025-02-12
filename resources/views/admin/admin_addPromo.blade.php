<x-admin_layout>
    <!-- Form for Adding New Offer -->
    <div class="container mx-auto px-2 mt-6">
        <a href="{{ route('admin.subscription') }}">
            <img class="px-2" src="{{ asset('logo_icons/arrow.png') }}" alt="Back to Subscription">
        </a>
        <div class="container mx-auto px-2 mt-6">
            <h2 class="text-2xl font-bold mb-4">Add New Offer</h2>
            <form id="unifiedForm" class="bg-white p-8 rounded-lg shadow-lg" method="POST" action="{{ route('promos.store') }}">
                @csrf
            
                <!-- Subscription Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Subscription Name:</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg" required placeholder="Enter subscription name">
                </div>
            
                <!-- Pricing -->
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-bold mb-2">Pricing:</label>
                    <input type="text" id="price" name="price" class="w-full p-2 border border-gray-300 rounded-lg" required placeholder="Enter pricing">
                </div>
            
                <!-- Duration -->
                <div class="mb-4">
                    <label for="duration" class="block text-gray-700 font-bold mb-2">Duration:</label>
                    <input type="text" id="duration" name="duration" class="w-full p-2 border border-gray-300 rounded-lg" required placeholder="Enter subscription duration (days)">
                </div>
            
                <!-- Features Section -->
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Features Available:</h2>
            
                <!-- Photo-to-Text Conversion -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Photo-to-Text Conversion:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="photo_unlimited" name="photo_to_text" value="unlimited" class="mr-2">
                        <label for="photo_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="photo_limited" name="photo_to_text" value="limited" class="mr-2">
                        <label for="photo_limited" class="mr-2">Limited</label>
                        <input type="text" id="photo_limit" name="photo_limit" class="w-24 p-2 border border-gray-300 rounded-lg hidden" placeholder="Enter limit">
                    </div>
                </div>
            
                <!-- Reviewer Generator -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Reviewer Generator:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="reviewer_unlimited" name="reviewer_generator" value="unlimited" class="mr-2">
                        <label for="reviewer_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="reviewer_limited" name="reviewer_generator" value="limited" class="mr-2">
                        <label for="reviewer_limited" class="mr-2">Limited</label>
                        <input type="text" id="reviewer_limit" name="reviewer_limit" class="w-24 p-2 border border-gray-300 rounded-lg hidden" placeholder="Enter limit">
                    </div>
                </div>
            
                <!-- Mock Quiz Generator -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Mock Quiz Generator:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="mock_quiz_unlimited" name="mock_quiz_generator" value="unlimited" class="mr-2">
                        <label for="mock_quiz_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="mock_quiz_limited" name="mock_quiz_generator" value="limited" class="mr-2">
                        <label for="mock_quiz_limited" class="mr-2">Limited</label>
                        <input type="text" id="mock_quiz_limit" name="mock_quiz_limit" class="w-24 p-2 border border-gray-300 rounded-lg hidden" placeholder="Enter limit">
                    </div>
                </div>
            
                <!-- Save & Download Reviewer -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Save & Download Reviewer:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="save_reviewer_unlimited" name="save_reviewer" value="unlimited" class="mr-2">
                        <label for="save_reviewer_unlimited" class="mr-4">Unlimited</label>
                        <input type="radio" id="save_reviewer_limited" name="save_reviewer" value="limited" class="mr-2">
                        <label for="save_reviewer_limited" class="mr-2">Limited</label>
                        <input type="text" id="save_reviewer_limit" name="save_reviewer_limit" class="w-24 p-2 border border-gray-300 rounded-lg hidden" placeholder="Enter limit">
                    </div>
                </div>
            
                <!-- Perks Input Field -->
                <div class="mb-4">
                    <label for="perks" class="block text-gray-700 font-bold mb-2">Perks:</label>
                    <textarea id="perks" name="perks" class="w-full p-2 border border-gray-300 rounded-lg" rows="3" placeholder="Enter subscription perks"></textarea>
                </div>
            
                <!-- Offer Dates -->
                <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 font-bold mb-2">Offer Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 font-bold mb-2">Offer End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg" required>
                </div>
            
                <!-- Discount Type -->
                <div class="mb-4">
                    <label for="discount_type" class="block text-gray-700 font-bold mb-2">Discount Type:</label>
                    <select id="discount_type" name="discount_type" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="percent">Percent Based</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>
            
                <!-- Percent Discount -->
                <div class="mb-4 hidden" id="percentDiscountContainer">
                    <label for="percent_discount" class="block text-gray-700 font-bold mb-2">Percent Discount:</label>
                    <input type="number" id="percent_discount" name="percent_discount" class="w-full p-2 border border-gray-300 rounded-lg" min="0" max="100">
                </div>
            
                <!-- Offer Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Offer Status:</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="status_active" name="status" value="active" class="mr-2">
                        <label for="status_active" class="mr-4">Active</label>
                        <input type="radio" id="status_inactive" name="status" value="inactive" class="mr-2">
                        <label for="status_inactive">Inactive</label>
                    </div>
                </div>
            
                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg">Save Offer</button>
                </div>
            </form>
        </div>
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

    document.getElementById('discount_type').addEventListener('change', function() {
        const discountType = this.value;
        const percentDiscountContainer = document.getElementById('percentDiscountContainer');
        
        // Show or hide percent discount input based on the selected discount type
        if (discountType === 'percent') {
            percentDiscountContainer.classList.remove('hidden');
        } else {
            percentDiscountContainer.classList.add('hidden');
        }
    });
</script>
</x-admin_layout>