<x-layout>
    <div class="container mx-auto px-6 py-8 min-h-screen flex flex-col">
        @if(session('success'))
            <div id="success-notification" class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="error-notification" class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="w-20 h-20 rounded-full border-4 border-gray-300" src="{{ asset('logo_icons/3.jpg') }}" alt="Profile Picture">
                    <div class="ml-5">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-gray-600 text-sm">Joined {{ $user->created_at->format('F Y') }}</p>
                    </div>
                </div>
                <div class="flex justify-start">
                    <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Help</a>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Total Reviewers and Quizzes -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-700 py-1">Statistics</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-blue-100 p-4 rounded-lg shadow-md">
                    <h4 class="text-l font-semibold text-blue-700">Total Reviewers</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalReviewers }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg shadow-md">
                    <h4 class="text-l font-semibold text-green-700">Total Quiz</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalQuizzes }}</p>
                </div>
            </div>
        </div>

        <!-- Subscription Status -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-700">Subscription Status</h3>
            </div>
            @if($subscription)
                <div class="mt-4 text-xs rounded-lg ">
                    <hr class="my-3">
                    <p class="text-blue-500">Promo <span class="font-semibold text-gray-700"> {{ $subscription->promo->name }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Status <span class="font-semibold text-gray-700">{{ $subscription->status }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Duration <span class="font-semibold text-gray-700"> {{ $subscription->promo->duration }} days </span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Reviewer created <span class="font-semibold text-gray-700"> {{ $subscription->reviewer_created }} / {{ $subscription->promo->reviewer_limit }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Quiz created<span class="font-semibold text-gray-700"> {{ $subscription->quiz_created }} /  {{ $subscription->promo->quiz_limit }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Quiz Question Limi<span class="font-semibold text-gray-700"> {{ $subscription->promo->quiz_questions_limit }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Mixed Quiz <span class="font-semibold text-gray-700"> {{ $subscription->promo->can_mix_quiz ? 'Yes' : 'No' }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Mixed Question Limit<span class="font-semibold text-gray-700"> {{ $subscription->promo->mix_quiz_limit }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Max Image per Upload <span class="font-semibold text-gray-700"> {{ $subscription->promo->image_limit }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">Start Date and Time <span class="font-semibold text-gray-700"> {{ \Carbon\Carbon::parse($subscription->start_date)->format('F j, Y g:i A') }}</span></p>
                    <hr class="my-3">
                    <p class="text-blue-500">End Date and Time <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y g:i A') }}</span></p>
                    <hr class="my-3">
                </div>
                <div class="flex justify-end mt-4">
                
                        
                    {{-- @else
                        <a href="#" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 shadow-md">
                            Subscribe to a New Promo
                        </a> --}}
                    @if($subscription->status == 'Expired')
                        <a href="{{ route('upgrade') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 shadow-md">
                            Subscribe to a New Promo
                        </a>
                    @elseif($subscription->status == 'Cancelled')
                        <a href="{{ route('upgrade') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 shadow-md">
                            Subscribe to a New Promo
                        </a>
                    @else
                        <button id="openModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
                            Cancel Subscription
                        </button>
                    @endif
                </div>

            @else
                <p class="text-gray-500 mt-4">No active subscription.</p>
            @endif
        </div>
          <!-- Subscription History -->
<div class="bg-white shadow-lg rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-700">Subscription History</h3>
    </div>

    @if($subscriptionHistory->isNotEmpty())
        <div class="mt-4 text-xs rounded-lg">
            <hr class="my-3">
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-s">
                        <tr>
                            <th class="px-4 py-3 text-left border-b border-gray-200">Promo Name</th>
                            <th class="px-4 py-3 text-left border-b border-gray-200">Status</th>
                            <th class="px-4 py-3 text-left border-b border-gray-200">Start Date</th>
                            <th class="px-4 py-3 text-left border-b border-gray-200">End Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-s">
                        @foreach($subscriptionHistory as $history)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-4 py-3">{{ $history->promo->name }}</td>
                                <td class="px-4 py-3 font-medium 
                                    @if($history->status == 'active') text-green-600 
                                    @elseif($history->status == 'expired') text-red-600 
                                    @else text-gray-600 
                                    @endif">
                                    {{ ucfirst($history->status) }}
                                </td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($history->start_date)->format('F j, Y g:i A') }}</td>
                                <td class="px-4 py-3">
                                    {{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('F j, Y g:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr class="my-3">
        </div>
    @else
        <p class="text-gray-500 mt-4">No subscription history available.</p>
    @endif
</div>


    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/2 lg:w-1/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Confirm Cancellation</h2>
            <p class="text-gray-600 mb-6">Are you sure you want to cancel your subscription?</p>
            <div class="flex justify-end space-x-3">
                <button id="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow hover:bg-gray-400 transition duration-300">
                    Cancel
                </button>
                <form action="{{ route('profile.cancelSubscription') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300 shadow-md">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>

       {{-- <!-- Modal -->
       <div id="modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/2 lg:w-1/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Confirm Cancellation</h2>
            <p class="text-gray-600 mb-6">Before Subscribing to another promo, cancel your current promo first.</p>
            <div class="flex justify-end space-x-3">
                <button id="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow hover:bg-gray-400 transition duration-300">
                    Cancel
                </button>
                <form action="{{ route('profile.cancelSubscription') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300 shadow-md">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div> --}}

    <script>

            // Remove notifications after 3 seconds with slide-out animation
            setTimeout(function() {
                const successNotification = document.getElementById('success-notification');
                const errorNotification = document.getElementById('error-notification');
                if (successNotification) {
                    successNotification.classList.add('slide-out');
                    setTimeout(() => successNotification.style.display = 'none', 500); // Match the duration of the slide-out animation
                }
                if (errorNotification) {
                    errorNotification.classList.add('slide-out');
                    setTimeout(() => errorNotification.style.display = 'none', 500); // Match the duration of the slide-out animation
                }
            }, 3000);


        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        if(openModal){
            openModal.addEventListener('click', function () {
                document.getElementById('modal').classList.remove('hidden');
            });
        }
        if(closeModal){
            closeModal.addEventListener('click', function () {
                document.getElementById('modal').classList.add('hidden');
            });
        }
    </script>
</x-layout>
