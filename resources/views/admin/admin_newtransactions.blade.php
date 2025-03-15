<x-admin_layout>
    <main class="p-5">
        <table class="w-full border-collapse mb-5">
            <thead>
              <tr>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Reference No.</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Email</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Reviewer Created</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Quiz Created</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Start Date</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">End Date</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Status</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Subcription Type</th>
                <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Actions</th>
              </tr>
            </thead>
            <tbody>
            @forelse ($subscriptions as $subscription)
              <tr>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->reference_number ?? 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->user->email ?? 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->reviewer_created }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->quiz_created }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->start_date ? date('M d, Y', strtotime($subscription->start_date)) : 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->end_date ? date('M d, Y', strtotime($subscription->end_date)) : 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm font-medium">
                  <span class="px-2 py-1 rounded-full text-xs {{ $subscription->status === 'Active' ? 'bg-green-100 text-green-700' : ($subscription->status === 'Expired' ? 'bg-red-100 text-red-700' : ($subscription->status === 'Cancelled' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                {{ $subscription->status }}
                  </span>
                </td>
                <td class="p-3 border-b border-gray-200 text-sm font-medium">{{ $subscription->subscription_type }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">
                  <button onclick="openEditModal({{ $subscription->subscription_id }})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200 text-xs font-medium">
                    Edit
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="p-3 border-b border-gray-200 text-sm text-gray-600 text-center">No subscriptions found</td>
              </tr>
            @endforelse
            </tbody>
          </table>
    </main>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Edit Subscription</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-800 focus:outline-none">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="subscription_id" id="subscription_id">
                
                <div class="mb-4">
                    <label for="reference_display" class="block text-sm font-medium text-gray-700">Reference Number</label>
                    <div id="reference_display" class="mt-1 block w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-md shadow-sm sm:text-sm"></div>
                </div>
                
                <div class="mb-4">
                    <label for="email_display" class="block text-sm font-medium text-gray-700">Email</label>
                    <div id="email_display" class="mt-1 block w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-md shadow-sm sm:text-sm"></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="start_date_display" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <div id="start_date_display" class="mt-1 block w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-md shadow-sm sm:text-sm"></div>
                        <input type="hidden" id="start_date" name="start_date">
                    </div>
                    
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="end_date" name="end_date" min="{{ date('Y-m-d') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="reviewer_created" class="block text-sm font-medium text-gray-700">Reviewer Created</label>
                        <input type="number" id="reviewer_created" name="reviewer_created" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div class="mb-4">
                        <label for="quiz_created" class="block text-sm font-medium text-gray-700">Quiz Created</label>
                        <input type="number" id="quiz_created" name="quiz_created" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Active">Active</option>
                        <option value="Expired">Expired</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Limit Reached">Limit Reached</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="subscription_type" class="block text-sm font-medium text-gray-700">Subscription Type</label>
                    <select id="subscription_type" name="subscription_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Admin Granted">Admin Granted</option>
                        <option value="Subscribed">Subscribed</option>
                    </select>
                </div>
                
                <div class="mt-5 flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="mr-3 px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id) {
            fetch(`/admin/subscription/${id}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('subscription_id').value = data.subscription_id;
                    document.getElementById('reference_display').textContent = data.reference_number || 'N/A';
                    document.getElementById('email_display').textContent = data.user.email || 'N/A';
                    document.getElementById('reviewer_created').value = data.reviewer_created;
                    document.getElementById('quiz_created').value = data.quiz_created;
                    
                    // Format dates for display and hidden fields
                    const startDate = data.start_date ? new Date(data.start_date) : null;
                    const endDate = data.end_date ? new Date(data.end_date) : null;
                    
                    if (startDate) {
                        // Format the display date in a readable format
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        document.getElementById('start_date_display').textContent = startDate.toLocaleDateString('en-US', options);
                        
                        // Still keep the ISO format for the form submission
                        document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
                        
                        // Set the minimum date for end_date to be the day after the start date
                        const minEndDate = new Date(startDate);
                        minEndDate.setDate(minEndDate.getDate() + 1); // Add one day to start date
                        
                        // Ensure the minimum date is either today or the day after start date, whichever is later
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (minEndDate < today) {
                            minEndDate.setTime(today.getTime());
                        }
                        
                        document.getElementById('end_date').min = minEndDate.toISOString().split('T')[0];
                    }
                    
                    if (endDate) {
                        document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
                    }
                    
                    document.getElementById('status').value = data.status;
                    document.getElementById('subscription_type').value = data.subscription_type;
                    
                    // Set the form action URL
                    document.getElementById('editForm').action = `/admin/subscription/${data.subscription_id}/update`;
                    
                    // Show the modal
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching subscription data:', error);
                    alert('Failed to load subscription data. Please try again.');
                });
        }
        
        // Add an event listener to validate the end date when it changes
        document.addEventListener('DOMContentLoaded', function() {
            const endDateInput = document.getElementById('end_date');
            
            endDateInput.addEventListener('change', function() {
                const startDateValue = document.getElementById('start_date').value;
                const startDate = new Date(startDateValue);
                const endDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Calculate the day after start date
                const minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);
                
                // Use the later of today or the day after start date
                if (minEndDate < today) {
                    minEndDate.setTime(today.getTime());
                }
                
                // If end date is before min date, reset it
                if (endDate < minEndDate) {
                    alert('End date must be after the start date and not in the past.');
                    this.value = minEndDate.toISOString().split('T')[0];
                }
            });
            
            const reviewerCreatedInput = document.getElementById('reviewer_created');
            const quizCreatedInput = document.getElementById('quiz_created');
            
            function validatePositiveInteger(input) {
                input.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    if (isNaN(value) || value < 0) {
                        this.value = 0;
                    }
                });
            }
            
            validatePositiveInteger(reviewerCreatedInput);
            validatePositiveInteger(quizCreatedInput);
        });
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-admin_layout>