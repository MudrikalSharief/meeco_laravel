<x-admin_layout>
    <main class="p-5">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold text-gray-800">Add New Subscription</h1>
            <a href="{{ route('admin.newtransactions') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200 text-sm font-medium">
                Back to Active Subscriptions
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.subscription.store') }}" method="POST" id="addSubscriptionForm">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">User Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="promo_id" class="block text-sm font-medium text-gray-700">Subscription Plan</label>
                    <select name="promo_id" id="promo_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Select a subscription plan</option>
                        @foreach($promos as $promo)
                            <option value="{{ $promo->promo_id }}" {{ old('promo_id') == $promo->promo_id ? 'selected' : '' }}>
                                {{ $promo->name }} - {{ $promo->duration }} days (₱{{ number_format($promo->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('promo_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="subscription_type" class="block text-sm font-medium text-gray-700">Subscription Type</label>
                        <select name="subscription_type" id="subscription_type" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Admin Granted" {{ old('subscription_type') == 'Admin Granted' ? 'selected' : '' }}>Admin Granted</option>
                            <option value="Subscribed" {{ old('subscription_type') == 'Subscribed' ? 'selected' : '' }}>Subscribed</option>
                        </select>
                        @error('subscription_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Limit Reached" {{ old('status') == 'Limit Reached' ? 'selected' : '' }}>Limit Reached</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-5">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add Subscription
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Error Modal (Improved Design) -->
    <div id="errorModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-0 rounded-lg shadow-2xl w-full max-w-md transform transition-transform duration-300 scale-95 opacity-0" id="errorModalContent">
            <!-- Modal Header -->
            <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                <h3 class="text-xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Subscription Error
                </h3>
                <button onclick="closeErrorModal()" class="text-white hover:text-gray-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="mb-5 text-gray-700">
                    <p id="errorMessage" class="text-base"></p>
                    <div id="additionalErrorContent" class="mt-4 text-sm"></div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end mt-6">
                    <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const promoSelect = document.getElementById('promo_id');
            const startDateInput = document.getElementById('start_date');
            const form = document.getElementById('addSubscriptionForm');
            
            // When start date changes, validate dates
            startDateInput.addEventListener('change', function() {
                validateDates();
            });
            
            function validateDates() {
                const startDate = new Date(startDateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Ensure start date is not in the past
                if (startDate < today) {
                    alert('Start date cannot be in the past. Setting to today.');
                    startDateInput.value = today.toISOString().split('T')[0];
                }
            }
            
            // Initial validation
            validateDates();
            
            // Check if there's an error message in session and show modal
            @if(session('error'))
                showErrorModal("{{ session('error') }}");
            @endif
        });
        
        function showErrorModal(message) {
            const errorModal = document.getElementById('errorModal');
            const errorModalContent = document.getElementById('errorModalContent');
            const errorMessage = document.getElementById('errorMessage');
            const additionalContent = document.getElementById('additionalErrorContent');
            
            // Create a temporary div to decode HTML entities
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = message;
            const decodedMessage = tempDiv.textContent;
            
            // Set the decoded message content
            errorMessage.textContent = decodedMessage;
            
            // Add a go to active subscriptions link if the error message contains the text
            if (decodedMessage.includes('You need to change the status')) {
                additionalContent.innerHTML = `
                    <div class="bg-gray-100 p-4 rounded-md border-l-4 border-blue-500 mt-3">
                        <h4 class="font-semibold mb-2 text-gray-800">How to fix this:</h4>
                        <ol class="list-decimal pl-5 space-y-1 text-gray-700">
                            <li>Go to <a href="{{ route('admin.newtransactions') }}" class="text-blue-600 hover:text-blue-800 font-medium">Active Subscriptions</a></li>
                            <li>Find the user's current subscription</li>
                            <li>Click the <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">Edit</span> button</li>
                            <li>Change the status to <span class="font-medium">"Expired"</span> or <span class="font-medium">"Cancelled"</span></li>
                            <li>Save your changes</li>
                            <li>Return to this page to add the new subscription</li>
                        </ol>
                    </div>
                `;
            } else {
                additionalContent.innerHTML = '';
            }
            
            // Show the modal with animation
            errorModal.classList.remove('hidden');
            errorModal.classList.add('flex');
            
            // Trigger animation after a small delay
            setTimeout(() => {
                errorModalContent.classList.remove('scale-95', 'opacity-0');
                errorModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeErrorModal() {
            const errorModal = document.getElementById('errorModal');
            const errorModalContent = document.getElementById('errorModalContent');
            
            // Reverse animation
            errorModalContent.classList.remove('scale-100', 'opacity-100');
            errorModalContent.classList.add('scale-95', 'opacity-0');
            
            // Hide modal after animation completes
            setTimeout(() => {
                errorModal.classList.add('hidden');
                errorModal.classList.remove('flex');
            }, 200);
        }
        
        // Close modal when clicking outside
        document.getElementById('errorModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeErrorModal();
            }
        });
    </script>
</x-admin_layout>
