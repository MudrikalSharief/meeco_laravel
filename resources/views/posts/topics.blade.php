<x-layout>
    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center font-semibold">
                    <div class="flex items-center">
                        <a href="{{ route('subject') }}"><h1 class=" text-xl  ">Subjects </h1></a>
                        <h2 class="pl-2 text-l"> → {{ $subject->name }}  </span></h2>
                    </div>
                </span>
            </div>
        </div>


    <div class="subject_id_in_topics w-full h-full flex flex-col items-center" data-subject-id="{{ $subject->subject_id }}">
        <div class="w-full max-w-2xl">
            <div class="flex justify-between items-center hidden">
                <div class="flex items-center">
                    <a href="{{ route('subject') }}"><h1 class="py-3 text-xl font-bold text-blue-800">Subjects </h1></a>
                    <h2 class="pl-2 text-l font-bold text-blue-500"> → {{ $subject->name }}  </span></h2>
                </div>
                <button id="addTopicButton" class="mt-2 px-2 py-1 bg-blue-500 text-white rounded hidden">Add Topic</button>
            </div>
     
        <!-- Add Topic Button -->
        <div id="topics-container" class="w-full max-w-2xl">
           {{-- topics to be added herre --}}
        </div>
        <p id="noTopicsMessage" class="text-gray-500 mt-2 hidden text-center">
            No Existiong Topics.
        </p>
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subject = @json($subject);
            const topic = @json($topic);
            const topicsContainer = document.getElementById('topics-container'); // Ensure this exists
            const deleteTopicConfirmModal = document.getElementById('deleteTopicConfirmModal');
            const topics_container = document.getElementById('topics-container');
            let topicIdToDelete = null;
            let topicElementToDelete = null;
        // if (topicsContainer) {
        //     topicsContainer.addEventListener('click', function (event) {
        //         console.log("the Container is clicked");
        //         const topicButton = event.target.closest('.subject_topics'); // Check if the clicked element is a topic button
        //         // const 
        //         console.log(topicButton);
        //         if (topicButton && topicButton.id) {
        //             // window.location.href = `/reviewer/${topicButton.id}`;
        //         }
        //     });
        // }

        fetch(`/subject/topics/${subject.subject_id}`)
        .then(response => response.json())
        .then(data => {
            if(data){
                    if (data.topics && data.topics.length > 0 ) {
                    topicsContainer.innerHTML = ''; // Clear existing topics
                    data.topics.forEach((topic, index) => {
                        const topicButton = document.createElement('a');
                        topicButton.href = `/reviewer/${topic.topic_id}`;
                        topicButton.innerHTML = `<button class="w-full text-start py-2 px-3 my-2 shadow-md bg-white rounded-md flex justify-between items-center hover:bg-blue-50 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                                                        <span>${topic.name}</span>
                                                        <span class="delete-topic text-red-500 h-full" data-topic-id="${topic.topic_id}"><img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete"></span>
                                                    </button>`;
                        if(topicsContainer){
                            topicsContainer.appendChild(topicButton);
                        }
                    });

                    document.querySelectorAll('.delete-topic').forEach(button => {
                        button.addEventListener('click', function (event) {
                            event.preventDefault(); // Prevent navigation
                            topicIdToDelete = this.getAttribute('data-topic-id');
                            topicElementToDelete = this.closest('a');
                            deleteTopicConfirmModal.classList.remove('hidden');
                        });
                    });
                } else {
                    if(noTopicsMessage){
                        noTopicsMessage.classList.remove('hidden');
                    }
                }
            }
            
        })
        .catch(console.log('Error fetching topics:'));

        
        const cancelTopicDelete = document.getElementById('cancelTopicDelete');
        const confirmTopicDelete = document.getElementById('confirmTopicDelete');
        if (cancelTopicDelete) {
            cancelTopicDelete.addEventListener('click', function () {
                deleteTopicConfirmModal.classList.add('hidden');
                topicIdToDelete = null;
                topicElementToDelete = null;
            });
        }

            if (confirmTopicDelete) {
                confirmTopicDelete.addEventListener('click', function () {
                    if (topicIdToDelete && topicElementToDelete) {
                        fetch('/topics/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ id: topicIdToDelete })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                topicElementToDelete.remove();
                                if (topics_container.children.length === 0) {
                                    noTopicsMessage.classList.remove('hidden');
                                }
                            } else {
                                alert('Failed to delete topic.');
                            }
                        })
                        .catch(error => console.error('Error deleting topic:', error))
                        .finally(() => {
                            deleteTopicConfirmModal.classList.add('hidden');
                            topicIdToDelete = null;
                            topicElementToDelete = null;
                        });
                    }
                });
            }
            
            if (cancelTopicDelete) {
                cancelTopicDelete.addEventListener('click', function () {
                    deleteTopicConfirmModal.classList.add('hidden');
                    topicIdToDelete = null;
                    topicElementToDelete = null;
                });
            }
    });
    </script>
</x-layout>