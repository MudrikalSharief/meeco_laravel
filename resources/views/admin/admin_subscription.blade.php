<x-admin_layout>
    <main class="py-1 px-0">
        <div class="container mx-auto px-0 sm:px-4 flex-grow">
            <h1 class="text-2xl font-bold text-left mb-5">Promos</h1>

            <!-- Subscription Stats -->
            <div class="flex flex-wrap justify-between gap-2 mb-4 px-0">
                <!-- Promo Counters -->
                <div class="flex w-full sm:w-1/2 lg:w-1/4 gap-2">
                    <div class="flex flex-col items-center bg-white text-blue-500 border border-blue-700 font-bold py-1 px-0 rounded-lg shadow-md text-center flex-1">
                        <h2 class="py-1 text-l font-bold">Active Promos</h2>
                        <p class="text-l font-bold mt-0">{{ $activePromosCount }}</p>
                    </div>
                    <div class="flex flex-col items-center bg-white text-blue-500 border border-blue-700 font-bold py-1 px-0 rounded-lg shadow-md text-center flex-1">
                        <h2 class="py-1 text-l font-bold">Inactive Promos</h2>
                        <p class="text-l font-bold mt-0">{{ $inactivePromosCount }}</p>
                    </div>
                </div>
                
                <!-- Add New Offer Button -->
                <div class="w-full sm:w-1/3 lg:w-1/5">
                    <a href="{{ route('admin.addPromo') }}" class="flex flex-col items-center bg-white text-blue-500 border border-blue-700 font-bold py-2 px-2 rounded-lg shadow-md text-center">
                        <svg class="w-8 h-8 fill-current mb-1" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0a10 10 0 110 20A10 10 0 0110 0zm1 11h4a1 1 0 100-2h-4V5a1 1 0 10-2 0v4H5a1 1 0 100 2h4v4a1 1 0 102 0v-4z"/>
                        </svg>
                        <span class="text-l">Add New Offer</span>
                    </a>
                </div>
            </div>
            
            <!-- Existing Promos Table -->
            <div class="mt-8">
                <div class="flex flex-wrap justify-between items-center mb-4">
                    <h2 class="text-1xl font-bold">Promos</h2>
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.subscription') }}" class="flex items-center w-full sm:w-auto mt-2 sm:mt-0">
                        <input type="text" name="search" placeholder="Search Promos" class="w-full sm:w-48 p-2 border border-gray-300 rounded-lg" value="{{ request('search') }}">
                        <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg">Search</button>
                    </form>
                </div>
                <div class="overflow-x-auto px-2">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b">Promo Name</th>
                                <th class="py-2 px-4 border-b">Pricing</th>
                                <th class="py-2 px-4 border-b">Duration</th>
                                <th class="py-2 px-4 border-b">Start Date</th>
                                <th class="py-2 px-4 border-b">End Date</th>
                                <th class="py-2 px-4 border-b">Status</th>
                                <th class="py-2 px-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promos as $promo)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b">{{ $promo->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $promo->price }}</td>
                                <td class="py-2 px-4 border-b">{{ $promo->duration }}</td>
                                <td class="py-2 px-4 border-b">{{ $promo->start_date }}</td>
                                <td class="py-2 px-4 border-b">{{ $promo->end_date }}</td>
                                <td class="py-2 px-4 border-b">{{ $promo->status }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('admin.editPromo', $promo->promo_id) }}" class="inline-block">
                                        <img src="{{ asset('logo_icons/edit.png') }}" alt="Edit" class="w-4 h-4 mx-1">
                                    </a>
                                    <form action="{{ route('admin.deletePromo', $promo->promo_id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block">
                                            <img src="{{ asset('logo_icons/trash.png') }}" alt="Delete" class="w-4 h-4 mx-1">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-admin_layout>
