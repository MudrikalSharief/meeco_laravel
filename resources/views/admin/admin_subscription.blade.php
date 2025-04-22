<x-admin_layout>
    <main class="py-6 px-6 bg-gray-50">
        <div class="container mx-auto flex-grow">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Promo Management</h1>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="#" class="text-gray-500 hover:text-blue-600">
                                Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="ml-1 font-medium text-blue-600">Promos</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Subscription Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 mb-8">
                <!-- Promo Counters -->
                <div class="col-span-1 sm:col-span-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-blue-100">
                        <div class="flex items-center p-6">
                            <div class="bg-blue-500 rounded-xl p-3 mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">Active Promos</div>
                                <div class="text-3xl font-bold text-blue-700">{{ number_format($activePromosCount) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-green-100">
                        <div class="flex items-center p-6">
                            <div class="bg-green-500 rounded-xl p-3 mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">Inactive Promos</div>
                                <div class="text-3xl font-bold text-green-700">{{ number_format($inactivePromosCount) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-purple-100">
                        <div class="flex items-center p-6">
                            <div class="bg-purple-500 rounded-xl p-3 mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">Total Promos</div>
                                <div class="text-3xl font-bold text-purple-700">{{ number_format($activePromosCount + $inactivePromosCount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Add New Offer Button -->
                <div class="col-span-1 sm:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm h-full border border-gray-100">
                        <a href="{{ route('admin.addPromo') }}" class="flex flex-col h-full items-center justify-center p-6 text-center transition-all duration-300 hover:bg-blue-50">
                            <div class="bg-blue-600 rounded-full p-4 mb-4 shadow-md">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 mb-1">Create New Promo</h3>
                            <p class="text-sm text-gray-500">Add a new promotional offer for your users</p>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Existing Promos Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 sm:mb-0">Promo List</h2>
                        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                            <!-- Status Filter Dropdown -->
                            <form method="GET" action="{{ route('admin.subscription') }}" class="w-full sm:w-auto">
                                <div class="relative">
                                    <select name="status" class="appearance-none w-full sm:w-48 p-2.5 pl-4 pr-10 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Search Form -->
                            <form method="GET" action="{{ route('admin.subscription') }}" class="w-full sm:w-auto">
                                <div class="relative">
                                    <input type="text" name="search" placeholder="Search Promos" class="w-full sm:w-64 p-2.5 pl-10 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ request('search') }}">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors">
                                        Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promo Name</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pricing</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image Limit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer Limit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz Limit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions/Quiz</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mix Quiz</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mix Quiz Limit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perks</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($promos as $promo)
                                @if(request('status') == '' || request('status') == $promo->status)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $promo->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">₱{{ number_format($promo->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->duration }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->image_limit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->reviewer_limit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->quiz_limit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->quiz_questions_limit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($promo->can_mix_quiz)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->can_mix_quiz ? $promo->mix_quiz_limit : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $promo->perks }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($promo->start_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($promo->end_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($promo->status == 'active')
                                            <span class="px-2.5 py-1 inline-flex items-center text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-600"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex items-center text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-600"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center space-x-3">
                                            <a href="{{ route('admin.editPromo', $promo->promo_id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            <button type="button" onclick="openDeleteModal({{ $promo->promo_id }})" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg hover:bg-red-100 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            
                            @if(count($promos) == 0 || (request('status') != '' && !$promos->contains('status', request('status'))))
                            <tr>
                                <td colspan="14" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-4 text-gray-500">No promos found</p>
                                        <a href="{{ route('admin.addPromo') }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Create New Promo</a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 rounded-b-2xl">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            Showing <span class="font-medium">{{ count($promos) }}</span> promos
                        </p>
                        <!-- Pagination could go here if needed -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 sm:w-2/3 md:w-1/2 lg:w-1/3 max-w-md transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Delete Promo?</h2>
                <p class="mt-4 text-gray-600">Are you sure you want to delete this promo? This action cannot be undone and all associated data will be permanently removed.</p>
            </div>
            <div class="mt-8 flex justify-center space-x-4">
                <button id="closeDeleteModal" class="px-6 py-3 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(promoId) {
            document.getElementById('deleteForm').action = '/admin/deletePromo/' + promoId;
            document.getElementById('deleteModal').classList.remove('hidden');
            // Add fade-in animation
            setTimeout(() => {
                document.querySelector('#deleteModal > div').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        document.getElementById('closeDeleteModal').addEventListener('click', function () {
            // Add fade-out animation
            document.querySelector('#deleteModal > div').classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                document.getElementById('deleteModal').classList.add('hidden');
            }, 300);
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('closeDeleteModal').click();
            }
        });
    </script>
</x-admin_layout>
