<x-layout>
    <div class="p-6 w-full h-full flex flex-col items-center">
        <div class="w-full max-w-2xl">
            <div class="flex justify-between items-center">
                <h1 class="py-3 text-xl font-bold text-blue-800">Subjects</h1>
                <button id="addSubjectButton" class="mb-3 bg-blue-500 text-white py-2 px-4 rounded hidden">Add Subject</button>
            </div>
        <div id="subjectsContainer" class="w-full max-w-2xl"></div>
        <p id="noSubjectsMessage" class="text-gray-500 mt-2 hidden text-center">No Subjects to Show</p>
    </div>

    <!-- Add Subject Modal -->
    <div id="addSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Add New Subject</h2>
            <input type="text" id="newSubjectName" class="border p-2 w-full mb-1" placeholder="Subject Name">
            <p class="error NewSubjectError mb-3 hidden text-center">Subject Already Exist</p>
            <div class="flex justify-center">
                <button id="cancelButton" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="saveButton" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Subject Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Confirm Deletion</h2>
            <p class="text-center">Are you sure you want to delete this subject?</p>
            <div class="flex justify-center mt-4">
                <button id="cancelDelete" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded">Delete</button>
            </div>
        </div>
    </div>
</x-layout>