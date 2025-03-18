<x-layout>
    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center text-xl font-semibold">
                    Subjects
                </span>
            </div>
        </div>

    <div class=" w-full h-full flex flex-col items-center">
        <div class="flex text-xs px-3 justify-between items-center w-full max-w-2xl">
            <p>Subject Name</p>
            <p>Acion</p>
        </div>
        <div class="w-full max-w-2xl">
            <div class="flex justify-between items-center hidden">
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

    <!-- Edit Subject Modal -->
    <div id="editSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Edit Subject</h2>
            <input type="text" id="editSubjectName" class="border p-2 w-full mb-1" placeholder="Subject Name">
            <p class="error EditSubjectError mb-3 hidden text-center">Subject Already Exist</p>
            <div class="flex justify-center">
                <button id="cancelEditButton" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="saveEditButton" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
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

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const deleteConfirmModal = document.getElementById('deleteConfirmModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const editSubjectModal = document.getElementById('editSubjectModal');
            const cancelEditButton = document.getElementById('cancelEditButton');
            const saveEditButton = document.getElementById('saveEditButton');
            const editSubjectName = document.getElementById('editSubjectName');
            let subjectIdToDelete = null;
            let subjectElementToDelete = null;
            let subjectIdToEdit = null;
            let subjectElementToEdit = null;

            if (cancelDelete) {
                cancelDelete.addEventListener('click', function () {
                    deleteConfirmModal.classList.add('hidden');
                    subjectIdToDelete = null;
                    subjectElementToDelete = null;
                });
            }

            if (confirmDelete) {
                confirmDelete.addEventListener('click', function () {
                    if (subjectIdToDelete && subjectElementToDelete) {
                        fetch('/subjects/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ id: subjectIdToDelete })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                subjectElementToDelete.remove();
                                if (subjectsContainer.children.length === 0) {
                                    noSubjectsMessage.classList.remove('hidden');
                                }
                            } else {
                                alert('Failed to delete subject.');
                            }
                        })
                        .catch(error => console.error('Error deleting subject:', error))
                        .finally(() => {
                            deleteConfirmModal.classList.add('hidden');
                            subjectIdToDelete = null;
                            subjectElementToDelete = null;
                        });
                    }
                });
            }

            if (cancelEditButton) {
                cancelEditButton.addEventListener('click', function () {
                    editSubjectModal.classList.add('hidden');
                    subjectIdToEdit = null;
                    subjectElementToEdit = null;
                });
            }

            if (saveEditButton) {
                saveEditButton.addEventListener('click', function () {
                    
                    if (subjectIdToEdit && editSubjectName.value.trim() !== '') {
                        fetch('/subjects/edit', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ subject_id: subjectIdToEdit, name: editSubjectName.value.trim() })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                subjectElementToEdit.querySelector('span').textContent = data.subject.name;
                            } else {
                                alert('Failed to edit subject.');
                            }
                        })
                        .catch(error => console.error('Error editing subject:', error))
                        .finally(() => {
                            editSubjectModal.classList.add('hidden');
                            subjectIdToEdit = null;
                            subjectElementToEdit = null;
                        });
                    }
                });
            }

            if(subjectsContainer){
                fetch('/subjects')
                    .then(response => response.json())
                    .then(data => {
                        if (data.subjects && data.subjects.length > 0 ) {
                            data.subjects.forEach((subject, index) => {
                                const subjectButton = document.createElement('a');
                                subjectButton.href = `/subjects/${subject.subject_id}`;
                                subjectButton.innerHTML = `<button class="w-full text-start py-2 px-3 my-2 shadow-md rounded-md flex justify-between bg-white items-center hover:bg-blue-50 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300">
                                                                <span>${subject.name}</span>
                                                                <div class="flex gap-2">
                                                                    <span class="delete-subject text-red-500 h-full" data-subject-id="${subject.subject_id}"> <img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete"></span>
                                                                    <span class="edit-subject text-red-500 h-full" data-subject-id="${subject.subject_id}"> <img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/edit.png" alt="Edit"></span>
                                                                </div>
                                                            </button>`;
                                if(subjectsContainer){
                                    subjectsContainer.appendChild(subjectButton);
                                }
                            });

                            document.querySelectorAll('.delete-subject').forEach(button => {
                                button.addEventListener('click', function (event) {
                                    event.preventDefault(); // Prevent navigation
                                    subjectIdToDelete = this.getAttribute('data-subject-id');
                                    subjectElementToDelete = this.closest('a');
                                    deleteConfirmModal.classList.remove('hidden');
                                });
                            });

                            document.querySelectorAll('.edit-subject').forEach(button => {
                                button.addEventListener('click', function (event) {
                                    event.preventDefault(); // Prevent navigation
                                    subjectIdToEdit = this.getAttribute('data-subject-id');
                                    subjectElementToEdit = this.closest('a');
                                    editSubjectName.value = subjectElementToEdit.querySelector('span').textContent;
                                    editSubjectModal.classList.remove('hidden');
                                });
                            });


                        } else {
                            if(noSubjectsMessage){
                                noSubjectsMessage.classList.remove('hidden');
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            }    
    
        });
    </script>
</x-layout>