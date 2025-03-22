<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Actions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gray-50">
    <x-admin_layout>
        <main class="py-6">
            {{-- //================================================================= --}}
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Admin Actions</h1>
                
                <!-- Table Container with Shadow and Rounded Corners -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($adminActions as $action)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($action->action_type == 'Create') bg-green-100 text-green-800
                                                @elseif($action->action_type == 'Update') bg-blue-100 text-blue-800
                                                @elseif($action->action_type == 'Delete') bg-red-100 text-red-800
                                                @elseif($action->action_type == 'Reply') bg-purple-100 text-purple-800
                                                @elseif($action->action_type == 'Logout') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $action->action_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $action->details }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($action->timestamp)->format('F d, Y, h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex space-x-2">
                        @if ($adminActions->onFirstPage())
                            <span class="btn btn-secondary disabled inline-flex items-center px-4 py-2 bg-gray-200 text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $adminActions->previousPageUrl() }}" class="btn btn-primary inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">Previous</a>
                        @endif

                        @if ($adminActions->hasMorePages())
                            <a href="{{ $adminActions->nextPageUrl() }}" class="btn btn-primary inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">Next</a>
                        @else
                            <span class="btn btn-secondary disabled inline-flex items-center px-4 py-2 bg-gray-200 text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Next</span>
                        @endif
                    </div>
                    
                    <!-- Laravel Pagination Links -->
                    <div class="pagination-links">
                        {{ $adminActions->links() }}
                    </div>
                </div>
            </div>
            {{-- //========================================================================== --}}
        </main>
    </x-admin_layout>

    <script>
        // Add custom styling to Laravel's pagination links
        document.addEventListener('DOMContentLoaded', function() {
            // Style the pagination links that Laravel generates
            const paginationContainer = document.querySelector('.pagination-links nav');
            if (paginationContainer) {
                // Add Tailwind classes to the pagination container
                paginationContainer.classList.add('inline-flex', 'rounded-md', 'shadow-sm', '-space-x-px');
                
                // Style the pagination links
                const paginationLinks = paginationContainer.querySelectorAll('a, span');
                paginationLinks.forEach(link => {
                    // Base styles for all pagination elements
                    link.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'border', 'text-sm', 'font-medium');
                    
                    if (link.classList.contains('text-gray-500')) {
                        // Non-active links
                        link.classList.add('bg-white', 'border-gray-300', 'text-gray-500', 'hover:bg-gray-50');
                    }
                    
                    if (link.getAttribute('aria-current') === 'page') {
                        // Active page
                        link.classList.add('z-10', 'bg-primary-50', 'border-primary-500', 'text-primary-600');
                    }
                    
                    // Disabled links
                    if (link.classList.contains('disabled')) {
                        link.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                    }
                });
            }
        });
    </script>
</body>
</html>