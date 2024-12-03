<x-layout>
    <div class=" p-3 w-full h-full" data-subject-id="{{ $subject->subject_id }}">
        <div class="flex items-center">
            <a href="{{ route('subject')}}"><h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects </h1></a>
            <h2 class=" font-semibold text-xl text-blue-500"> > Topics</h2>
        </div>
        

        <!-- Add Topic Button -->
        <button id="addTopicButton" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Add Topic</button>

        @if ($topics->count() > 0)
            <div class="topics-container">
                @foreach ($topics as $topic)
                    <a href="#"><button class=" w-full max-w-2xl border text-start py-2 px-3 my-2 shadow-md rounded-md">{{ $topic->name }}</button></a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-2">No Topics to Show</p>
        @endif

        
        <!-- Add Topic Modal -->
        <div id="addTopicModal" class="fixed inset-0 mb-3 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded shadow-md">
                <h2 class="text-xl mb-4">Add New Topic</h2>
                <input type="text" id="newTopicName" class="border p-2 w-full mb-4" placeholder="Topic Name">
                <div class="flex justify-end">
                    <button id="cancelTopicButton" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
                    <button id="saveTopicButton" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>