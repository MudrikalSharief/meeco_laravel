<x-layout>
    <div class=" p-3 w-full h-full">
        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Deleted</h1>
        <button id="selectItemsButton" class=" mb-3 bg-blue-500 text-white py-2 px-4 rounded">Select</button>
        <div id="deletedContainer" class="w-full max-w-2xl"></div>
        <p id="noDeletedMessage" class="text-gray-500 mt-2 hidden">No Deleted to Show</p>
    </div>

    <!-- Delete Subject Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
            <p>Are you sure you want to delete this item?</p>
            <div class="flex justify-end mt-4">
                <button id="cancelDelete" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded">Delete</button>
            </div>
        </div>
    </div>
</x-layout>