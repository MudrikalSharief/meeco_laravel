<x-admin_layout>
    <main class="p-5">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold text-gray-800">Inactive Subscriptions</h1>
            <a href="{{ route('admin.newtransactions') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200 text-sm font-medium">
                View Active Subscriptions
            </a>
        </div>

        <div class="flex space-x-2 mb-5">
            <button id="show-all" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-200 text-xs font-medium">
                All Inactive
            </button>
            <button id="show-expired" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-200 text-xs font-medium">
                Expired
            </button>
            <button id="show-cancelled" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-200 text-xs font-medium">
                Cancelled
            </button>
            <button id="show-limit-reached" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-200 text-xs font-medium">
                Limit Reached
            </button>
        </div>
        
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
              </tr>
            </thead>
            <tbody>
            @forelse ($subscriptions as $subscription)
              <tr class="subscription-row {{ strtolower($subscription->status) }}-status">
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->reference_number ?? 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->user->email ?? 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $subscription->reviewer_created }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->quiz_created }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->start_date ? date('M d, Y', strtotime($subscription->start_date)) : 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm">{{ $subscription->end_date ? date('M d, Y', strtotime($subscription->end_date)) : 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm font-medium">
                  <span class="px-2 py-1 rounded-full text-xs 
                  {{ $subscription->status === 'Expired' ? 'bg-red-100 text-red-700' : 
                     ($subscription->status === 'Cancelled' ? 'bg-yellow-100 text-yellow-700' : 
                     'bg-gray-100 text-gray-700') }}">
                    {{ $subscription->status }}
                  </span>
                </td>
                <td class="p-3 border-b border-gray-200 text-sm font-medium">{{ $subscription->subscription_type }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="p-3 border-b border-gray-200 text-sm text-gray-600 text-center">No inactive subscriptions found</td>
              </tr>
            @endforelse
            </tbody>
          </table>
    </main>

    <script>
        // Filter buttons functionality
        document.addEventListener('DOMContentLoaded', function() {
            const showAll = document.getElementById('show-all');
            const showExpired = document.getElementById('show-expired');
            const showCancelled = document.getElementById('show-cancelled');
            const showLimitReached = document.getElementById('show-limit-reached');
            const rows = document.querySelectorAll('.subscription-row');

            showAll.addEventListener('click', function() {
                rows.forEach(row => {
                    row.style.display = 'table-row';
                });
            });

            showExpired.addEventListener('click', function() {
                rows.forEach(row => {
                    if (row.classList.contains('expired-status')) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            showCancelled.addEventListener('click', function() {
                rows.forEach(row => {
                    if (row.classList.contains('cancelled-status')) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            showLimitReached.addEventListener('click', function() {
                rows.forEach(row => {
                    if (row.classList.contains('limit-reached-status')) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-admin_layout>
