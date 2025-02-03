<x-layout>
<<<<<<< HEAD
    <div class="subject_id_in_topics p-6 w-full h-full flex flex-col items-center" data-subject-id="{{ $subject->subject_id }}">
        <div class="w-full max-w-2xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('subject') }}"><h1 class="py-3 px-2 text-l font-semibold text-blue-500">Subjects</h1></a>
                    <h2 class=" text-l font-bold text-blue-500"> > Topics</h2>
                </div>
                <button id="addTopicButton" class="mt-2 px-2 py-1 bg-blue-500 text-white rounded">Add Topic</button>
            </div> <div id="topics-container" class="w-full max-w-2xl">
                
=======
    <div class="subject_id_in_topics p-3 w-full h-full" data-subject-id="{{ $subject->subject_id }}">
        <div class="flex items-center">
            <a href="{{ route('subject')}}"><h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects </h1></a>
            <h2 class=" font-semibold text-xl text-blue-500"> > Topics</h2>
        </div>
     
        <!-- Add Topic Button -->
        <button id="addTopicButton" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Add Topic</button>
        <div id="topics-container" class="w-full max-w-2xl">
           {{-- topics to be added herre --}}
        </div>
        <p id="noTopicsMessage" class="text-gray-500 mt-2 hidden"></p>
    </div>

    <!-- Modal for Adding Topic -->
    <div id="addTopicModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Add Topic</h2>
            <div class="mb-4">
                <label for="newTopicName" class="block text-sm font-medium text-gray-700 mb-1">Topic Name</label>
                <input id="newTopicName" type="text" class="p-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cancelTopicButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="saveTopicButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </div>
    </div>

    <!-- Modal for Topic Creation Confirmation -->
    <div id="topicConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4 text-green-500">Topic Created</h2>
            <hr class="mb-2">
            <p>Your topic has been created successfully.</p>
            <div class="flex justify-end mt-4">
                <button id="closeTopicConfirm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>

    <!-- Modal for Topic Exists -->
    <div id="topicExistsModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4 text-red-500">Topic Exists</h2>
            <hr class="mb-2">
            <p>The topic name already exists. Please choose a different name.</p>
            <div class="flex justify-end mt-4">
                <button id="closeTopicExists" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>

    <!-- Delete Topic Confirmation Modal -->
    <div id="deleteTopicConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
            <p>Are you sure you want to delete this topic?</p>
            <div class="flex justify-end mt-4">
                <button id="cancelTopicDelete" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="confirmTopicDelete" class="bg-red-500 text-white py-2 px-4 rounded">Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const topicsContainer = document.getElementById('topics-container'); // Ensure this exists
        if (topicsContainer) {
            topicsContainer.addEventListener('click', function (event) {
                console.log("the Container is clicked");
                const topicButton = event.target.closest('.subject_topics'); // Check if the clicked element is a topic button
                console.log(topicButton);
                if (topicButton && topicButton.id) {
                    window.location.href = `/reviewer/${topicButton.id}`;
                }
            });
        }
        });
    </script>
</x-layout>