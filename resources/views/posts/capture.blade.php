<x-layout>
    <div class=" p-3 w-full h-fullx ">

        <h1 class="py-3 px-2 text-xl font-bold text-blue-800">Convert Image</h1>

        <div class="flex justify-between items-center gap-4 pb-4">

            <button id="openModal" type="button" class=" border shadow-md border-blue-200 hover:border-blue-600 border-dashed bg-white p-2 rounded-md btn btn-primary w-1/2 h-40 flex justify-center items-center" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-blue-100 text-white p-3 rounded-full flex justify-center items-center w-12">
                        <img class=" z-10 max-w-8  filter-blue"  src="{{ asset('logo_icons/add-image.svg')}}" alt="">
                    </div>
                        <p  class=" text-xs mt-2 text-gray-600">Add Image</p>
                </div>
            </button>
    
            <button id="openCamera" type="button" class="border  shadow-md border-blue-200 hover:border-blue-600 bg-white rounded-md btn btn-primary w-1/2  h-40  flex flex-col items-center justify-center">

                <div class="flex flex-col items-center justify-center">
                    <div  class="bg-blue-100 text-white p-3 w-12 rounded-full">
                        <img class="z-10 max-w-8 filter-blue" src="{{ asset('logo_icons/camera-viewfinder.svg') }}" alt="">
                    </div>
                        <p class=" text-xs mt-2 text-gray-600">Camera</p>
                </div>

            </button>
        </div>

        <!-- Container for Uploaded Images -->
        <h2 class=" py2 text-gray-700 text-base font-medium">Image uploaded</h2>
   
        <div id="imageContainer" class="mt-2 px-3 flex flex-wrap bg-white min-h-32 rounded-md border-gray-200 border"></div>
        <div id="imageNamesContainer" class="mt-2 px-3 flex flex-wrap"></div>
        
        <button id="extractTextButton" type="button" class="hidden  bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600" data-bs-toggle="modal" data-bs-target="#extractTextModal">Extract Text</button>
        
        <!-- Modal -->
        <div id="uploadModal" class="min-w-72 fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex  items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg   ">
                <div class="flex justify-between items-center border-b px-4 py-2">
                    <h5 class="text-lg font-semibold">Upload Images</h5>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="p-4">
                    <form id="uploadForm" action="{{ route('capture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Choose Images</label>
                            <input id="imageInput" type="file" name="images[]" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" multiple>
                        </div>
                    </form>
                </div>
                <div class="flex justify-end border-t px-4 py-2">
                    <button class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600" id="cancelUpload">Cancel</button>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:hover:bg-blue-500" id="uploadButton">Upload</button>
                </div>
        </div>
    </div>

    <!-- Modal for Zoomed Image -->
    <div id="zoomModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <img id="zoomedImage" src="" alt="Zoomed Image" class="max-w-full max-h-full">
        </div>
    </div>

    <!-- Modal for Delete Confirmation -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
            <p>Are you sure you want to delete this image?</p>
            <div class="flex justify-end mt-4">
                <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>

    <!-- Modal for Upload Confirmation -->
    <div id="uploadConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg  font-semibold mb-4 text-green-500">Upload Successful</h2>
            <hr class="mb-2">
            <p>Your images have been uploaded successfully.</p>
            <div class="flex justify-end mt-4">
                <button id="closeUploadConfirm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>

    <!-- Modal for Camera -->
    <div id="cameraModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <video id="cameraFeed" class="w-full h-full" autoplay></video>
            <div class="flex justify-end mt-4">
                <button id="captureImage" class="bg-blue-500 text-white px-4 py-2 rounded mr-2 hover:bg-blue-600">Capture</button>
                <button id="closeCamera" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal for Capture Confirmation -->
    <div id="captureConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 id="captureConfirmMessage" class="text-red-500 font-semibold mb-4"></h2>
            <div class="flex justify-end mt-4">
                <button id="closeCaptureConfirm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>

    <!-- Modal for Extract Text -->
    <div id="extractTextModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Extract Text</h2>
            <div class="mb-4">
                <label for="subjectDropdown" class="block text-sm font-medium text-gray-700 mb-1">Select Subject</label>
                <select id="subjectDropdown" class="p-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Subject</option>
                    <!-- Options will be populated dynamically -->
                </select>
                <button id="addSubjectButton" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Subject</button>
            </div>
            <div id="topicsDropdownContainer" class="mb-4 hidden">
                <label for="topicDropdown" class="block text-sm font-medium text-gray-700 mb-1">Select Topic</label>
                <select id="topicDropdown" class="p-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <button id="addTopicButton" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hidden">Add Topic</button>
            <p id="noTopicsMessage" class="text-gray-500 mt-2 hidden">This subject has no topics yet.</p>
            <p id="subjectReminder" class="text-red-500 mt-2 hidden">Please select a subject.</p>
            <p id="topicReminder" class="text-red-500 mt-2 hidden">Please select a topic.</p>
            <div class="flex justify-end mt-4">
                <button id="cancelExtractTextModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="confirmExtractText" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Extract</button>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Subject -->
    <div id="addSubjectModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Add Subject</h2>
            <div class="mb-4">
                <label for="newSubjectName" class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                <input id="newSubjectName" type="text" class="p-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cancelSubjectButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="saveSubjectButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </div>
    </div>

    <!-- Modal for Subject Creation Confirmation -->
    <div id="subjectConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4 text-green-500">Subject Created</h2>
            <hr class="mb-2">
            <p>Your subject has been created successfully.</p>
            <div class="flex justify-end mt-4">
                <button id="closeSubjectConfirm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
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
                <button id="saveTopicButtonCapture" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
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

    <x-confirmation_modal id="dynamicModal" title="" titleColor="" message="" buttonId="dynamicModalButton" buttonText="OK" />
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageContainer = document.getElementById('imageContainer');
        const modal = document.getElementById('dynamicModal');
        const modalTitle = document.getElementById('dynamicModal-title');
        const modalMessage = document.getElementById('dynamicModal-message');
        const modalButton = document.getElementById('dynamicModalButton');
        let subjectDropdownListenerAdded = false;
        // Function to show the dynamic modal
        function showModal(title = '', message = '', titleColor = '', buttonText = '') {
            modalTitle.textContent = title;
            modalTitle.className = `text-lg font-semibold mb-4 ${titleColor}`;
            modalMessage.textContent = message;
            modalButton.textContent = buttonText;
            modal.classList.remove('hidden');
        }

        // Close the modal when the close button is clicked
        document.getElementById('dynamicModal-close').addEventListener('click', function() {
            document.getElementById('dynamicModal').classList.add('hidden');
        });

        // Handle image upload
        const uploaded = document.getElementById('uploadButton');
        const uploadConfirmModal = document.getElementById('uploadConfirmModal');
        const closeUploadConfirm = document.getElementById('closeUploadConfirm');

        if (uploaded) {
            uploaded.addEventListener('click', function () {
                uploaded.disabled = true; // Disable the upload button to prevent spamming

                let form = document.getElementById('uploadForm');
                const fileInput = document.getElementById('imageInput'); 
                let formData = new FormData(form);

                fetch('/capture/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadConfirmModal.classList.remove('hidden'); // Show the upload confirmation modal
                        document.querySelector('#uploadModal').classList.add('hidden');
                        fileInput.value = ''; // Reset the file input value

                        fetch('/capture/images')
                        .then(response => response.json())
                        .then(data => {
                            if (data.images && data.images.length > 0) {
                                imageContainer.innerHTML = '';
                                data.images.forEach((url, index) => {
                                    const imgWrapper = document.createElement('div');
                                    imgWrapper.className = 'm-2 img-wrapper relative';

                                    const img = document.createElement('img');
                                    img.src = url;
                                    img.alt = 'Uploaded Image';
                                    img.className = 'w-28 h-32 object-cover border border-gray-300 rounded cursor-pointer';
                                    img.setAttribute('data-file-path', url.replace(`${window.location.origin}/storage/uploads/`, ''));

                                    const deleteIcon = document.createElement('span');
                                    deleteIcon.className = 'delete-icon absolute py-1 px-2 top-0 right-0 bg-red-500 hover:bg-red-700 text-white cursor-pointer ';
                                    deleteIcon.textContent = '×';

                                    const name = document.createElement('p');
                                    name.textContent = `Image ${index + 1}`;
                                    name.className = 'text-center';

                                    imgWrapper.appendChild(img);
                                    imgWrapper.appendChild(deleteIcon);
                                    imgWrapper.appendChild(name);
                                    imageContainer.appendChild(imgWrapper);
                                });
                                toggleExtractButton();
                            }
                        })
                        .catch(error => console.error('Error:', error))
                        .finally(() => {
                            uploaded.disabled = false;
                        });
                    } else {
                        if (data.route) {
                            showModal('No Subscription', data.message, 'text-red-500', 'View Promos');
                            modalButton.classList.remove('hidden');
                            modalButton.addEventListener('click', function() {
                                window.location.href = '/upgrade';
                            });
                        } else {
                            showModal('Error', data.message, 'text-red-500', 'OK');
                            modalButton.classList.add('hidden');
                        }
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    uploaded.disabled = false; // Re-enable the upload button after the request is complete
                });
            });
        }

        if (closeUploadConfirm) {
            closeUploadConfirm.addEventListener('click', () => {
                uploadConfirmModal.classList.add('hidden');
            });
        }

        // Fetch and display previously uploaded images
        function fetchImages() {
            fetch('/capture/images')
            .then(response => response.json())
            .then(data => {
                if (data.images && data.images.length > 0) {
                    imageContainer.innerHTML = '';
                    data.images.forEach((url, index) => {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'm-2 img-wrapper relative';

                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = 'Uploaded Image';
                        img.className = 'w-28 h-32 object-cover border border-gray-300 rounded cursor-pointer';
                        img.setAttribute('data-file-path', url.replace(`${window.location.origin}/storage/uploads/`, ''));

                        const deleteIcon = document.createElement('span');
                        deleteIcon.className = 'delete-icon absolute py-1 px-2 top-0 right-0 bg-red-500 hover:bg-red-700 text-white cursor-pointer';
                        deleteIcon.textContent = '×';

                        const name = document.createElement('p');
                        name.textContent = `Image ${index + 1}`;
                        name.className = 'text-center';

                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(deleteIcon);
                        imgWrapper.appendChild(name);
                        imageContainer.appendChild(imgWrapper);
                    });
                    toggleExtractButton();
                } else {
                    const message = document.createElement('p');
                    message.classList.add('text-gray-500', 'text-sm','mt-2');
                    message.textContent = 'No images uploaded yet.';
                    imageContainer.appendChild(message);
                }
            })
            .catch(error => console.error('Error fetching uploaded images:', error));
        }

        fetchImages();

        // Toggle the visibility of the extract button
        function toggleExtractButton() {
            const extractTextButton = document.getElementById('extractTextButton');
            if (imageContainer.children.length > 0) {
                extractTextButton.classList.remove('hidden');
            } else {
                extractTextButton.classList.add('hidden');
            }
        }

        // Handle image deletion
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');
        let imgWrapperToDelete = null;

 // Handle image deletion
if (imageContainer) {
    imageContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-icon')) {
            imgWrapperToDelete = event.target.closest('.img-wrapper');
            if (imgWrapperToDelete) {
                const filePath = imgWrapperToDelete.querySelector('img').getAttribute('data-file-path');
                fetch('/capture/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ filePath: 'uploads/' + filePath })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        
                            
                        if (imgWrapperToDelete && imgWrapperToDelete.parentNode === imageContainer) {
                            imageContainer.removeChild(imgWrapperToDelete);
                        }
                        
                        toggleExtractButton();

                        // Check if the container is now empty
                        if (imageContainer.querySelectorAll('.img-wrapper').length === 0) {
                            // Clear the container first to avoid any issues
                            imageContainer.innerHTML = '';
                            
                            const message = document.createElement('p');
                            message.textContent = 'No images uploaded yet.';
                            message.className = 'text-gray-500 mt-2 text-sm text-center w-full p-4';
                            imageContainer.appendChild(message);
                        }
                    } else {
                        alert('Failed to delete image.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    deleteConfirmModal.classList.add('hidden');
                    imgWrapperToDelete = null;
                });
            }
        }
    });
}

        // Handle zooming of images
        const zoomModal = document.getElementById('zoomModal');
        const zoomedImage = document.getElementById('zoomedImage');

        if (imageContainer) {
            imageContainer.addEventListener('click', (event) => {
                if (event.target.tagName === 'IMG') {
                    zoomedImage.src = event.target.src;
                    zoomModal.classList.remove('hidden');
                }
            });
        }

        if (zoomModal) {
            zoomModal.addEventListener('click', (event) => {
                if (event.target === zoomModal) {
                    zoomModal.classList.add('hidden');
                }
            });
        }

        // Handle image capture from camera
        const captureImage = document.getElementById('captureImage');
        const cameraFeed = document.getElementById('cameraFeed');
        const cameraModal = document.getElementById('cameraModal');
        const captureConfirmModal = document.getElementById('captureConfirmModal');
        const captureConfirmMessage = document.getElementById('captureConfirmMessage');
        const closeCaptureConfirm = document.getElementById('closeCaptureConfirm');

        if (captureImage) {
            captureImage.addEventListener('click', () => {
                const canvas = document.createElement('canvas');
                canvas.width = cameraFeed.videoWidth;
                canvas.height = cameraFeed.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('images[]', blob, 'captured-image.png');

                    fetch('/capture/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        cameraModal.classList.add('hidden');
                        if (!data.success) {
                            
                            showModal('Error', data.message, 'text-red-500', 'OK');
                            modalButton.classList.add('hidden');
                        } 
                        
                        if (cameraFeed.srcObject) {
                            const stream = cameraFeed.srcObject;
                            const tracks = stream.getTracks();
                            tracks.forEach(track => track.stop());
                            cameraFeed.srcObject = null;
                        }
                        fetchImages(); // Refresh the image list
                    })
                    .catch(error => {
                        cameraModal.classList.add('hidden');
                        captureConfirmMessage.textContent = 'Failed to upload captured image.';
                        captureConfirmModal.classList.remove('hidden');
                        console.error('Error:', error);
                    });
                }, 'image/png');
            });
        }

        if (closeCaptureConfirm) {
            closeCaptureConfirm.addEventListener('click', () => {
                captureConfirmModal.classList.add('hidden');
            });
        }

        if (confirmExtractText) {
        confirmExtractText.addEventListener('click', () => {
            if (!subjectDropdown.value) {
                subjectReminder.classList.remove('hidden');
                noTopicsMessage.classList.add('hidden');
            } else if (!topicDropdown.value) {
                subjectReminder.classList.add('hidden');
                noTopicsMessage.classList.remove('hidden');
            } else {
                subjectReminder.classList.add('hidden');
                noTopicsMessage.classList.add('hidden');
                extractTextModal.classList.add('hidden');

                
                //check if the user exceeded the reviewer generation yet
                fetch('/subscription/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);
                        if(data.reviewerLimitReached){
                            showModal('Error', 'Please upgrade your subscription to add more reviewers.', 'text-red-500', 'OK');
                            modalButton.classList.add('hidden');
                        }
                        else{
                            const topicId = topicDropdown.value;
                            const topicName = topicDropdown.options[topicDropdown.selectedIndex].text;
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/capture/extract';
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="topic_id" value="${topicId}">
                                <input type="hidden" name="topic_name" value="${topicName}">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    } else {
                        console.log(data);
                    }
                })
                .catch(error => console.error('Error checking subscription:', error));
                

            }
        });
    }

    if (extractTextButton) {
        extractTextButton.addEventListener('click', () => {
            extractTextModal.classList.remove('hidden');
            fetch('/subjects')
                .then(response => response.json())
                .then(data => {
                    if (data.subjects && data.subjects.length > 0) {
                        subjectDropdown.innerHTML = '<option value="">Select Subject</option>';
                        data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.subject_id;
                            option.textContent = subject.name;
                            subjectDropdown.appendChild(option);
                        });

                        if (!subjectDropdownListenerAdded) {
                            subjectDropdown.addEventListener('change', handleSubjectDropdownChange);
                            subjectDropdownListenerAdded = true;
                        }
                    }
                })
                .catch(error => console.error('Error fetching subjects:', error));
        });
    }
    const closeExtractTextModal = document.getElementById('closeExtractTextModal');
    if (closeExtractTextModal) {
        closeExtractTextModal.addEventListener('click', () => {
            extractTextModal.classList.add('hidden');
        });
    }

    });
    </script>
</x-layout>