<x-admin_layout>
    <main class="py-3">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-left mb-5">Subscription</h1>

            <!-- Subscription Stats -->
            <div class="flex justify-between mb-6 px-2">
                <div class="flex space-x-4 w-2/3">
                    <div class="bg-blue-500 text-white p-2 rounded-lg shadow-lg flex-1 text-center">
                        <h2 class="py-2 text-2xl font-bold">Total Subscribers</h2>
                        <p class="text-4xl font-bold mt-1">{{ $totalSubscribersCount }}</p>
                    </div>
                    <div class="bg-blue-500 text-white p-2 rounded-lg shadow-lg flex-1 text-center px-2">
                        <h2 class="py-2 text-2xl font-bold">Active Promo</h2>
                        <p class="text-4xl font-bold mt-1">{{ $activePromosCount }}</p>
                    </div>
                </div>
                <div class="w-1/4">
                    <a href="{{ route('admin.addPromo') }}" class="flex flex-col items-center bg-white text-blue-500 border border-blue-500 font-bold py-8 px-6 rounded-lg shadow-lg text-center">
                        <svg class="w-10 h-10 fill-current mb-2" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0a10 10 0 110 20A10 10 0 0110 0zm1 11h4a1 1 0 100-2h-4V5a1 1 0 10-2 0v4H5a1 1 0 100 2h4v4a1 1 0 102 0v-4z"/>
                        </svg>
                        Add New Offer
                    </a>
                </div>
            </div>
            <!-- Existing Promos Table -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Existing Promos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b">Promo Name</th>
                                <th class="py-2 px-4 border-b">Pricing</th>
                                <th class="py-2 px-4 border-b">Duration</th>
                                <th class="py-2 px-4 border-b">Start Date</th>
                                <th class="py-2 px-4 border-b">End Date</th>
                                <th class="py-2 px-4 border-b">Status</th>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subscribers Table -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Subscribers</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b">Subscriber Name</th>
                                <th class="py-2 px-4 border-b">Email</th>
                                <th class="py-2 px-4 border-b">Subscription Type</th>
                                <th class="py-2 px-4 border-b">Start Date</th>
                                <th class="py-2 px-4 border-b">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b">{{ $subscription->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $subscription->email }}</td>
                                <td class="py-2 px-4 border-b">{{ $subscription->subscription_type }}</td>
                                <td class="py-2 px-4 border-b">{{ $subscription->start_date }}</td>
                                <td class="py-2 px-4 border-b">{{ $subscription->end_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    
        </div>
    </main>
</x-admin_layout>
