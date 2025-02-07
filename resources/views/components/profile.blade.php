<x-layout>
    <script src="//unpkg.com/alpinejs" defer></script>
    <div x-data="{ openModal: false }" class="container mx-auto p-6 h-screen flex flex-col">
        <div class="flex-grow overflow-auto pb-16">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <div class="flex items-center mb-6">
                    <img class="w-16 h-16 rounded-full border-2 border-gray-300" src="{{ asset('logo_icons/3.jpg') }}" alt="Profile Picture">
                    <div class="ml-4">
                        <h2 class="text-2xl font-semibold text-gray-600">Anthony Marc</h2>
                        <p class="text-gray-600">anthony@gmail.com</p>
                        <p class="text-gray-600">Joined August 2024</p>
                    </div>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Logout</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-blue-600 mb-4">Subscription Status</h3>
                    <p class="text-gray-700"><span class="font-medium">Subscription:</span> Casual</p>
                    <p class="text-gray-700"><span class="font-medium">Start Date:</span> October 1, 2024, at 1:32 PM</p>
                    <p class="text-gray-700"><span class="font-medium">End Date:</span> October 8, 2024, at 1:32 PM</p>
                    <button @click="openModal = true" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300">Cancel Subscription</button>
                </div>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-blue-600 mb-4">Activity Tracker</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Photos Uploaded: 18</li>
                        <li>Reviewers: 13</li>
                        <li>Mock Quizzes: 13</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-8 rounded-lg shadow-lg w-3/4 sm:w-2/3 md:w-1/2 lg:w-1/3 relative left-7">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Confirm Cancellation</h2>
                <p class="text-gray-600 mb-4">Are you sure you want to cancel your subscription?</p>
                <div class="flex justify-end">
                    <button @click="openModal = false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2">Cancel</button>
                    <form action="{{ url('/profile/cancelled') }}" method="GET">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
