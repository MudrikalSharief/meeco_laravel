<x-layout>
    <div class="container mx-auto px-6 py-8 min-h-screen flex flex-col">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
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

         <!-- Subscription Status (Same Size as User Info) -->
         <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-blue-700">Subscription Status</h3>
            </div>
            @if($subscription)
                <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-gray-700"><span class="font-semibold">Promo :</span> {{ $subscription->promo->name }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Status :</span> {{ $subscription->status }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Duration:</span> {{ $subscription->promo->duration }} days</p>
                    <p class="text-gray-700"><span class="font-semibold">Reviewer created:</span> {{ $subscription->reviewer_created }} / {{ $subscription->promo->reviewer_limit }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Quiz created:</span> {{ $subscription->quiz_created }} /  {{ $subscription->promo->quiz_limit }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Quiz Question Limit:</span> {{ $subscription->promo->quiz_questions_limit }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Mixed Quiz :</span> {{ $subscription->promo->can_mix_quiz ? 'Yes' : 'No' }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Mixed Question Limit:</span> {{ $subscription->promo->mix_quiz_limit }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Start Date and Time:</span> {{ \Carbon\Carbon::parse($subscription->start_date)->format('F j, Y g:i A') }}</p>
<p class="text-gray-700"><span class="font-semibold">End Date and Time:</span> {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y g:i A') }}</p>
                </div>
                <div class="flex justify-end mt-4">
                    <button id="openModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
                        Cancel Subscription
                    </button>
                </div>
            @else
                <p class="text-gray-500 mt-4">No active subscription.</p>
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

    <script>
        document.getElementById('openModal').addEventListener('click', function () {
            document.getElementById('modal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('modal').classList.add('hidden');
        });
    </script>
</x-layout>
