<x-layout>
    <div class=" p-3 w-full h-full">
        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects</h1>
        <button id="addSubjectButton" class=" mb-3 bg-blue-500 text-white py-2 px-4 rounded">Add Subject</button>
        <div id="subjectsContainer" class="w-full max-w-2xl"></div>
        <p id="noSubjectsMessage" class="text-gray-500 mt-2 hidden">No Subjects to Show</p>
    </div>

    <!-- Modal -->
    <div id="addSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-bold mb-4">Add New Subject</h2>
            <input type="text" id="newSubjectName" class="border p-2 w-full mb-1" placeholder="Subject Name">
            <p class="error NewSubjectError mb-3 hidden">Subject Already Exist</p>
            <div class="flex justify-end">
                <button id="cancelButton" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="saveButton" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
            </div>
        </div>
    </div>

    
</x-layout>