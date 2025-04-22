<x-layout>
    <div class=" p-3 w-full h-fullx ">

        <h1 class="py-3 px-2 text-xl font-bold text-blue-800">Convert Image</h1>

        <div class="flex justify-between items-center gap-4 pb-4">

            <button id="openModal" type="button" class=" border shadow-md border-blue-200 hover:border-blue-600 border-dashed bg-white p-2 rounded-md btn btn-primary w-1/2 h-40 flex justify-center items-center">
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
        
        <!-- Analyze Graph Container -->
        <h2 class="py-2 mt-4 text-gray-700 text-base font-medium">Analyze Graph</h2>
        <div id="analyzeGraph" class="mt-2 px-3 py-4 text-center bg-white min-h-40 rounded-md border-gray-200 border flex flex-wrap items-center">
            <p class="text-gray-500 text-sm">No graph analysis available yet.</p>
        </div>
        
        <button id="extractTextButton" type="button" class="hidden  bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600" data-bs-toggle="modal" data-bs-target="#extractTextModal">Extract Text</button>
        
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
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 90%; max-width: 500px; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4 text-center">Take a Photo</h2>
            
            <!-- Desktop camera interface -->
            <div id="desktopCameraInterface" class="hidden">
                <video id="cameraFeed" class="w-full h-64 bg-black object-cover" autoplay playsinline></video>
                <div class="flex justify-center mt-4">
                    <button id="captureDesktopImage" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Capture Photo
                    </button>
                </div>
            </div>
            
            <!-- Mobile camera interface -->
            <div id="mobileCameraInterface" class="hidden">
                <div class="text-center py-4">
                    <input type="file" id="cameraInput" accept="image/*" capture="environment" class="hidden">
                    <label for="cameraInput" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 cursor-pointer inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Open Camera
                    </label>
                </div>
            </div>
            
            <!-- Image preview (for both interfaces) -->
            <div id="imagePreviewContainer" class="hidden mt-4">
                <p class="mb-2 font-medium">Preview:</p>
                <img id="imagePreview" src="#" alt="Preview" class="w-full h-48 object-contain border border-gray-300 rounded mb-4">
                <div class="flex justify-between">
                    <button id="retakePhoto" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Retake</button>
                    <button id="uploadCapturedImage" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Upload</button>
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
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
    
    {{-- Loader --}}
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="relative">
            <div class="loader"></div>
            <p class="absolute text-white text-center w-full -bottom-12 font-medium">Converting your Pdf.. This may take for a while.</p>
        </div>
    </div>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageContainer = document.getElementById('imageContainer');
        const analyzeGraph = document.getElementById('analyzeGraph');
        const modal = document.getElementById('dynamicModal');
        const modalTitle = document.getElementById('dynamicModal-title');
        const modalMessage = document.getElementById('dynamicModal-message');
        const modalButton = document.getElementById('dynamicModalButton');
        const topicsContainer = document.getElementById('topicsDropdownContainer');
        let subjectDropdownListenerAdded = false;
        
        // Add localStorage functions to persist image positions
        function getAnalyzedImages() {
            const analyzedImages = localStorage.getItem('analyzedImages');
            return analyzedImages ? JSON.parse(analyzedImages) : [];
        }
        
        function saveAnalyzedImage(filePath) {
            const analyzedImages = getAnalyzedImages();
            if (!analyzedImages.includes(filePath)) {
                analyzedImages.push(filePath);
                localStorage.setItem('analyzedImages', JSON.stringify(analyzedImages));
            }
        }
        
        function removeAnalyzedImage(filePath) {
            const analyzedImages = getAnalyzedImages();
            const updatedImages = analyzedImages.filter(path => path !== filePath);
            localStorage.setItem('analyzedImages', JSON.stringify(updatedImages));
        }
        
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

        const extractTextButton = document.getElementById('extractTextButton');
        // Toggle the visibility of the extract button
        function toggleExtractButton() {
            if (imageContainer.children.length > 0 ) {
                extractTextButton.classList.remove('hidden');
            } else {
                extractTextButton.classList.add('hidden');
            }
        }

        // Function to move image to analyze graph container
        function moveToAnalyzeGraph(imgWrapper) {
            // First remove any placeholder text in the analyze graph container
            if (analyzeGraph.querySelector('p.text-gray-500')) {
                analyzeGraph.innerHTML = '';
            }
            
            // Get file path
            const img = imgWrapper.querySelector('img');
            const filePath = img.getAttribute('data-file-path');
            
            // Make server request to move the file to graph folder
            fetch('/capture/move-to-graph', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ filePath: filePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the image path if it changed on server
                    if (data.newPath) {
                        img.setAttribute('data-file-path', data.newPath);
                        img.src = `${window.location.origin}/storage/${data.newPath}`;
                    }
                    
                    // Instead of trying to manually move the DOM elements,
                    // refresh both containers using the API
                    fetchImages();
                } else {
                    showModal('Error', data.message || 'Failed to move image to graph container.', 'text-red-500', 'OK');
                    console.error('Error details:', data);
                }
            })
            .catch(error => {
                console.error('Error moving image:', error);
                showModal('Error', 'An error occurred while moving the image.', 'text-red-500', 'OK');
            });
        }

        // Function to return image from analyze graph to image container
        function returnToImageContainer(imgWrapper) {
            // Get file path
            const img = imgWrapper.querySelector('img');
            const filePath = img.getAttribute('data-file-path');
            
            // Make server request to move the file back to image container folder
            fetch('/capture/move-to-container', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ filePath: filePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the image path if it changed on server
                    if (data.newPath) {
                        img.setAttribute('data-file-path', data.newPath);
                        img.src = `${window.location.origin}/storage/${data.newPath}`;
                    }
                    
                    // Instead of trying to manually move the DOM elements,
                    // refresh both containers using the API
                    fetchImages();
                } else {
                    showModal('Error', data.message || 'Failed to move image back to container.', 'text-red-500', 'OK');
                    console.error('Error details:', data);
                }
            })
            .catch(error => {
                console.error('Error moving image:', error);
                showModal('Error', 'An error occurred while moving the image.', 'text-red-500', 'OK');
            });
        }

        // Updated fetch and display images function
        function fetchImages() {
            // Fetch images from both container and graph folders
            Promise.all([
                fetch('/capture/container-images'),
                fetch('/capture/graph-images')
            ])
            .then(responses => Promise.all(responses.map(response => response.json())))
            .then(([containerData, graphData]) => {
                // Handle container images
                imageContainer.innerHTML = '';
                if (containerData.images && containerData.images.length > 0) {
                    containerData.images.forEach((url, index) => {
                        // Extract clean file path - only the part after /storage/
                        const filePath = url.split('/storage/')[1];
                        
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'm-2 img-wrapper relative';
                        
                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = 'Image ' + (index + 1);
                        img.className = 'w-28 h-32 object-cover border border-gray-300 rounded cursor-pointer';
                        img.setAttribute('data-file-path', filePath);
                        
                        const analyzeIcon = document.createElement('span');
                        analyzeIcon.className = 'analyze-icon absolute py-1 px-2 top-0 left-0 bg-blue-500 hover:bg-blue-700 text-white cursor-pointer';
                        analyzeIcon.textContent = '→';
                        analyzeIcon.title = 'Move to Analyze Graph';
                        
                        const deleteIcon = document.createElement('span');
                        deleteIcon.className = 'delete-icon absolute py-1 px-2 top-0 right-0 bg-red-500 hover:bg-red-700 text-white cursor-pointer';
                        deleteIcon.textContent = '×';
                        
                        const name = document.createElement('p');
                        name.textContent = `Image ${index + 1}`;
                        name.className = 'text-center';
                        
                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(analyzeIcon);
                        imgWrapper.appendChild(deleteIcon);
                        imgWrapper.appendChild(name);
                        imageContainer.appendChild(imgWrapper);
                    });
                } else {
                    const message = document.createElement('p');
                    message.classList.add('text-gray-500', 'text-sm', 'mt-2', 'text-center','flex','items-center','justify-center', 'w-full', 'p-4');
                    message.textContent = 'No images uploaded yet.';
                    imageContainer.appendChild(message);
                }
                
                // Handle graph images
                analyzeGraph.innerHTML = '';
                if (graphData.images && graphData.images.length > 0) {
                    graphData.images.forEach((url, index) => {
                        // Extract clean file path - only the part after /storage/
                        const filePath = url.split('/storage/')[1];
                        
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'm-2 img-wrapper relative';
                        
                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = 'Graph Image ' + (index + 1);
                        img.className = 'w-28 h-32 object-cover border border-gray-300 rounded cursor-pointer';
                        img.setAttribute('data-file-path', filePath);
                        
                        const returnIcon = document.createElement('span');
                        returnIcon.className = 'return-icon absolute py-1 px-2 top-0 left-0 bg-green-500 hover:bg-green-700 text-white cursor-pointer';
                        returnIcon.textContent = '←';
                        returnIcon.title = 'Return to Images';
                        
                        const deleteIcon = document.createElement('span');
                        deleteIcon.className = 'delete-icon absolute py-1 px-2 top-0 right-0 bg-red-500 hover:bg-red-700 text-white cursor-pointer';
                        deleteIcon.textContent = '×';
                        
                        const name = document.createElement('p');
                        name.textContent = `Graph ${index + 1}`;
                        name.className = 'text-center';
                        
                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(returnIcon);
                        imgWrapper.appendChild(deleteIcon);
                        imgWrapper.appendChild(name);
                        analyzeGraph.appendChild(imgWrapper);
                    });
                } else {
                    const message = document.createElement('p');
                    message.className = 'text-gray-500 text-sm text-center w-full';
                    message.textContent = 'No graph analysis available yet.';
                    analyzeGraph.appendChild(message);
                }
                
                // Toggle the extract button based on whether there are images in the container
                toggleExtractButton();
            })
            .catch(error => console.error('Error fetching images:', error));
        }

        if (extractTextButton) {
            extractTextButton.addEventListener('click', () => {
                console.log('hi');

                fetch('/capture/check', {
                        headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {


                        if (data.noImages) {
                            showModal('Error', 'No images uploaded yet.', 'text-red-500', 'OK');
                            modalButton.classList.add('hidden');
                        }
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
                                    subjectDropdown.addEventListener('change', () =>{
                                        const selectedSubjectId = subjectDropdown.value;
                                        if (!selectedSubjectId) {
                                            
                                            subjectReminder.classList.remove('hidden');
                                            topicsContainer.classList.add('hidden');
                                            noTopicsMessage.classList.add('hidden');
                                            addTopicButton.classList.add('hidden');
                                        } else {
                                            subjectReminder.classList.add('hidden');
                                            addTopicButton.classList.remove('hidden');
                                            fetch(`/topics/subject/${selectedSubjectId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.topics && data.topics.length > 0) {
                                                        topicDropdown.innerHTML = '';
                                                        data.topics.forEach(topic => {
                                                            const option = document.createElement('option');
                                                            option.value = topic.topic_id;
                                                            option.textContent = topic.name;
                                                            topicDropdown.appendChild(option);
                                                        });
                                                        topicsDropdownContainer.classList.remove('hidden');

                                                        noTopicsMessage.classList.add('hidden');
                                                    } else {
                                                        topicsDropdownContainer.classList.add('hidden');
                                                        noTopicsMessage.classList.remove('hidden');
                                                    }
                                                })
                                                .catch(error => console.error('Error fetching topics:', error));
                                        }
                                    });
                                    subjectDropdownListenerAdded = true;
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching subjects:', error));


                    } else {
                        showModal('Error', data.message, 'text-red-500', 'OK');
                        modalButton.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error checking images:', error));
        });


            
    }

        // Handle image upload
        const uploaded = document.getElementById('openModal');
        const uploadConfirmModal = document.getElementById('uploadConfirmModal');
        const closeUploadConfirm = document.getElementById('closeUploadConfirm');

        if (uploaded) {
    uploaded.addEventListener('click', function (event) {
        event.preventDefault();
        
        // Create a hidden file input element
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.multiple = true;
        fileInput.accept = 'image/*,application/pdf'; // Accept both images and PDFs
        fileInput.style.display = 'none';
        document.body.appendChild(fileInput);
        
        // Trigger click on the hidden file input
        fileInput.click();
        
        // Handle file selection
        fileInput.addEventListener('change', async function() {
            if (fileInput.files.length > 0) {
                uploaded.disabled = true; // Disable the upload button to prevent spamming
                
                // Show loader for PDF processing
                const loader = document.getElementById('loader');
                if (loader) loader.classList.remove('hidden');
                
                const formData = new FormData();
                
                // Check if we need to process PDFs
                let hasPdfs = false;
                for (let i = 0; i < fileInput.files.length; i++) {
                    if (fileInput.files[i].type === 'application/pdf') {
                        hasPdfs = true;
                        break;
                    }
                }
                
                // If we have PDFs, we need to load PDF.js
                if (hasPdfs && !window.pdfjsLib) {
                    // Load PDF.js dynamically
                    try {
                        await new Promise((resolve, reject) => {
                            const script = document.createElement('script');
                            script.src = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js';
                            script.onload = resolve;
                            script.onerror = reject;
                            document.head.appendChild(script);
                        });
                        
                        // Set worker source
                        window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
                    } catch (error) {
                        console.error('Error loading PDF.js:', error);
                        showModal('Error', 'Failed to load PDF processing library.', 'text-red-500', 'OK');
                        uploaded.disabled = false;
                        if (loader) loader.classList.add('hidden');
                        return;
                    }
                }
                
                // Process each file
                try {
                    for (let i = 0; i < fileInput.files.length; i++) {
                        const file = fileInput.files[i];
                        
                        if (file.type === 'application/pdf') {
                            // Convert PDF to images
                            const pdfBlob = file;
                            const pdfData = await pdfBlob.arrayBuffer();
                            
                            // Load the PDF document
                            const pdf = await window.pdfjsLib.getDocument({ data: pdfData }).promise;
                            const numPages = pdf.numPages;
                            
                            // Convert each page to an image
                            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                                const page = await pdf.getPage(pageNum);
                                const viewport = page.getViewport({ scale: 1.5 }); // Adjust scale as needed
                                
                                // Create canvas for rendering
                                const canvas = document.createElement('canvas');
                                canvas.width = viewport.width;
                                canvas.height = viewport.height;
                                
                                // Render the page on the canvas
                                await page.render({
                                    canvasContext: canvas.getContext('2d'),
                                    viewport: viewport
                                }).promise;
                                
                                // Convert canvas to blob
                                const imageBlob = await new Promise(resolve => {
                                    canvas.toBlob(blob => resolve(blob), 'image/jpeg', 0.95);
                                });
                                
                                // Add to form data
                                formData.append('images[]', imageBlob, `pdf-page-${pageNum}-${file.name.replace('.pdf', '.jpg')}`);
                            }
                        } else {
                            // Regular image file - add directly
                            formData.append('images[]', file);
                        }
                    }
                    
                    // Upload all images (both original and PDF-derived)
                    fetch('/capture/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (loader) loader.classList.add('hidden');
                        
                        if (data.success) {
                            uploadConfirmModal.classList.remove('hidden'); // Show the upload confirmation modal
                            
                            fetchImages(); // Updated function to fetch images from separate folders
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
                    .catch(error => {
                        console.error('Error:', error);
                        if (loader) loader.classList.add('hidden');
                    })
                    .finally(() => {
                        uploaded.disabled = false; // Re-enable the upload button after the request is complete
                        // Remove the temporary file input
                        document.body.removeChild(fileInput);
                    });
                } catch (error) {
                    console.error('Error processing files:', error);
                    showModal('Error', 'An error occurred while processing the PDF file.', 'text-red-500', 'OK');
                    modalButton.classList.add('hidden');
                    uploaded.disabled = false;
                    if (loader) loader.classList.add('hidden');
                    document.body.removeChild(fileInput);
                }
            }
        });
    });
}

        if (closeUploadConfirm) {
            closeUploadConfirm.addEventListener('click', () => {
                uploadConfirmModal.classList.add('hidden');
            });
        }

        fetchImages();

        // Handle image deletion and analyze button clicks
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');
        let imgWrapperToDelete = null;

        // Handle image deletion and analyze button clicks
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
                            body: JSON.stringify({ filePath: filePath }) // Remove the 'uploads/' prefix, the controller will handle the path
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
                } else if (event.target.classList.contains('analyze-icon')) {
                    // New analyze functionality
                    const imgWrapper = event.target.closest('.img-wrapper');
                    if (imgWrapper) {
                        moveToAnalyzeGraph(imgWrapper);
                    }
                } else if (event.target.tagName === 'IMG') {
                    window.open(event.target.src, '_blank');
                }
            });
        }

        // Handle return button clicks in analyze graph
        if (analyzeGraph) {
            analyzeGraph.addEventListener('click', function (event) {
                if (event.target.classList.contains('return-icon')) {
                    const imgWrapper = event.target.closest('.img-wrapper');
                    if (imgWrapper) {
                        returnToImageContainer(imgWrapper);
                    }
                } else if (event.target.classList.contains('delete-icon')) {
                    // Allow deletion from analyze graph too
                    const imgWrapper = event.target.closest('.img-wrapper');
                    if (imgWrapper) {
                        const filePath = imgWrapper.querySelector('img').getAttribute('data-file-path');
                        fetch('/capture/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ filePath: filePath })  // Remove the 'uploads/' prefix
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                analyzeGraph.removeChild(imgWrapper);
                                
                                // Check if the container is now empty
                                if (analyzeGraph.querySelectorAll('.img-wrapper').length === 0) {
                                    analyzeGraph.innerHTML = '<p class="text-gray-500 text-sm text-center w-full">No graph analysis available yet.</p>';
                                }
                            } else {
                                alert('Failed to delete image.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                } else if (event.target.tagName === 'IMG') {
                    window.open(event.target.src, '_blank');
                }
            });
        }

        // When an image is deleted, we need to update localStorage too
        const originalDeleteHandler = imageContainer.onclick;
        imageContainer.onclick = function(event) {
            if (event.target.classList.contains('delete-icon')) {
                const imgWrapper = event.target.closest('.img-wrapper');
                if (imgWrapper) {
                    const filePath = imgWrapper.querySelector('img').getAttribute('data-file-path');
                    // Remove from analyzed images if it exists
                    removeAnalyzedImage(filePath);
                }
            }
            // Let the original handler run
            if (typeof originalDeleteHandler === 'function') {
                originalDeleteHandler.apply(this, arguments);
            }
        };

        // Also handle deletion from analyze graph container
        analyzeGraph.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-icon')) {
                const imgWrapper = event.target.closest('.img-wrapper');
                if (imgWrapper) {
                    const filePath = imgWrapper.querySelector('img').getAttribute('data-file-path');
                    // Remove from analyzed images if it exists
                    removeAnalyzedImage(filePath);
                }
            }
        });

        // Handle image capture from camera
        const openCamera = document.getElementById('openCamera');
        const cameraModal = document.getElementById('cameraModal');
        const desktopCameraInterface = document.getElementById('desktopCameraInterface');
        const mobileCameraInterface = document.getElementById('mobileCameraInterface');
        const cameraFeed = document.getElementById('cameraFeed');
        const captureDesktopImage = document.getElementById('captureDesktopImage');
        const cameraInput = document.getElementById('cameraInput');
        const closeCamera = document.getElementById('closeCamera');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const retakePhoto = document.getElementById('retakePhoto');
        const uploadCapturedImage = document.getElementById('uploadCapturedImage');
        const captureConfirmModal = document.getElementById('captureConfirmModal');
        const captureConfirmMessage = document.getElementById('captureConfirmMessage');
        const closeCaptureConfirm = document.getElementById('closeCaptureConfirm');

        // Check if device is mobile
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        let capturedImage = null;
        let stream = null;

        // Function to stop any active camera stream
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                if (cameraFeed) cameraFeed.srcObject = null;
            }
        }

        // Open camera modal
        if (openCamera) {
            openCamera.addEventListener('click', async function() {
                cameraModal.classList.remove('hidden');
                imagePreviewContainer.classList.add('hidden');
                
                console.log("Device detection: " + (isMobile ? "Mobile" : "Desktop"));
                
                if (isMobile) {
                    // Mobile: Show the file input interface
                    mobileCameraInterface.classList.remove('hidden');
                    desktopCameraInterface.classList.add('hidden');
                } else {
                    // Desktop: Show the webcam interface
                    desktopCameraInterface.classList.remove('hidden');
                    mobileCameraInterface.classList.add('hidden');
                    
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ 
                            video: { facingMode: 'user' },
                            audio: false
                        });
                        cameraFeed.srcObject = stream;
                    } catch (err) {
                        console.error('Error accessing camera:', err);
                        captureConfirmMessage.textContent = 'Error accessing camera: ' + err.message;
                        captureConfirmModal.classList.remove('hidden');
                        
                        // Fall back to mobile interface if webcam access fails
                        desktopCameraInterface.classList.add('hidden');
                        mobileCameraInterface.classList.remove('hidden');
                    }
                }
            });
        }
        
        // Handle desktop capture button
        if (captureDesktopImage) {
            captureDesktopImage.addEventListener('click', function() {
                if (!cameraFeed.srcObject) {
                    captureConfirmMessage.textContent = 'Camera is not active. Please try again.';
                    captureConfirmModal.classList.remove('hidden');
                    return;
                }
                
                const canvas = document.createElement('canvas');
                canvas.width = cameraFeed.videoWidth;
                canvas.height = cameraFeed.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);
                
                // Display preview
                canvas.toBlob(function(blob) {
                    capturedImage = new File([blob], "captured-image.png", { type: "image/png" });
                    imagePreview.src = URL.createObjectURL(blob);
                    imagePreviewContainer.classList.remove('hidden');
                    desktopCameraInterface.classList.add('hidden');
                    mobileCameraInterface.classList.add('hidden');
                }, 'image/png');
            });
        }
        
        // Handle file selection from camera
        if (cameraInput) {
            cameraInput.addEventListener('change', function(event) {
                if (this.files && this.files[0]) {
                    capturedImage = this.files[0];
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.classList.remove('hidden');
                        desktopCameraInterface.classList.add('hidden');
                        mobileCameraInterface.classList.add('hidden');
                    };
                    reader.readAsDataURL(capturedImage);
                }
            });
        }
        
        // Retake photo
        if (retakePhoto) {
            retakePhoto.addEventListener('click', function() {
                imagePreviewContainer.classList.add('hidden');
                capturedImage = null;
                
                if (isMobile) {
                    // Mobile: reset file input and show mobile interface
                    cameraInput.value = '';
                    mobileCameraInterface.classList.remove('hidden');
                    // Trigger camera again
                    setTimeout(() => cameraInput.click(), 100);
                } else {
                    // Desktop: show webcam interface again
                    desktopCameraInterface.classList.remove('hidden');
                }
            });
        }
        
        // Upload captured image
        if (uploadCapturedImage) {
            uploadCapturedImage.addEventListener('click', function() {
                if (!capturedImage) {
                    captureConfirmMessage.textContent = 'No image captured.';
                    captureConfirmModal.classList.remove('hidden');
                    return;
                }
                
                const formData = new FormData();
                formData.append('images[]', capturedImage);
                
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
                    capturedImage = null;
                    stopCamera();
                    
                    if (data.success) {
                        fetchImages();
                    } else {
                        showModal('Error', data.message || 'Failed to upload image.', 'text-red-500', 'OK');
                    }
                })
                .catch(error => {
                    console.error('Error uploading image:', error);
                    captureConfirmMessage.textContent = 'Failed to upload captured image.';
                    captureConfirmModal.classList.remove('hidden');
                });
            });
        }
        
        // Close camera modal
        if (closeCamera) {
            closeCamera.addEventListener('click', function() {
                cameraModal.classList.add('hidden');
                capturedImage = null;
                if (cameraInput) cameraInput.value = '';
                stopCamera();
            });
        }
        
        // Handle capture confirmation modal close
        if (closeCaptureConfirm) {
            closeCaptureConfirm.addEventListener('click', function() {
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
                    noTopicsMessage.classList.add('hidden');
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
                        if(data.notSubscribed){
                            showModal('Error', 'You are not Subscibe to any promo yet.', 'text-red-500', 'OK');
                            modalButton.classList.add('hidden');
                        }
                        else if(data.reviewerLimitReached){
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

    
    //==================================================
    // Add this to your existing script in capture.blade.php where appropriate
    // Fix for the tutorial highlight positioning
const showStep = (step) => {
    const tutorialContent = document.getElementById('tutorialContent');
    const prevButton = document.getElementById('prevTutorial');
    const nextButton = document.getElementById('nextTutorial');
    updateProgressDots(step);
    
    // Show/hide previous button based on step
    if (step > 0) {
        prevButton.classList.remove('hidden');
    } else {
        prevButton.classList.add('hidden');
    }
    
    // Update next button text on last step
    if (step === tutorialSteps.length - 1) {
        nextButton.innerHTML = `Finish 
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>`;
    } else {
        nextButton.innerHTML = `Next
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>`;
    }
    
    const targetElement = document.querySelector(tutorialSteps[step].element);
    if (!targetElement) {
        console.error(`Tutorial target element not found: ${tutorialSteps[step].element}`);
        return;
    }
    
    // Fade out old content first
    tutorialContent.style.opacity = '0';
    tutorialContent.style.transform = 'translateY(10px)';
    
    // Remove any existing highlight elements to avoid duplicates
    const existingHighlight = document.getElementById('highlightBox');
    const existingPulse = document.getElementById('pulse');
    if (existingHighlight) existingHighlight.remove();
    if (existingPulse) existingPulse.remove();
    
    setTimeout(() => {
        // Create content for this step
        tutorialContent.innerHTML = `
            <div class="mb-4 transform transition-all duration-500">
                <div class="flex items-center mb-3">
                    <span class="text-3xl mr-3">${tutorialSteps[step].icon}</span>
                    <h3 class="text-lg font-semibold text-blue-700">${tutorialSteps[step].title}</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">${tutorialSteps[step].message}</p>
            </div>
        `;
        
        // Create highlight elements directly in the body for better positioning
        // We need to position them relative to the viewport to ensure correct placement
        const highlightBox = document.createElement('div');
        highlightBox.id = 'highlightBox';
        highlightBox.className = 'border-2 border-blue-500 border-dashed rounded-md fixed pointer-events-none z-[65] transition-all duration-500 shadow-lg';
        document.body.appendChild(highlightBox);
        
        const pulse = document.createElement('div');
        pulse.id = 'pulse';
        pulse.className = 'fixed rounded-md pointer-events-none z-[64] animate-pulse bg-blue-300 opacity-30';
        document.body.appendChild(pulse);
        
        // Update highlight position with a function
        const updateHighlightPosition = () => {
            const rect = targetElement.getBoundingClientRect();
            
            highlightBox.style.top = `${rect.top - 4}px`;
            highlightBox.style.left = `${rect.left - 4}px`;
            highlightBox.style.width = `${rect.width + 8}px`;
            highlightBox.style.height = `${rect.height + 8}px`;
            
            pulse.style.top = `${rect.top}px`;
            pulse.style.left = `${rect.left}px`;
            pulse.style.width = `${rect.width}px`;
            pulse.style.height = `${rect.height}px`;
        };
        
        // Initial position
        updateHighlightPosition();
        
        // Make sure element is in view
        const scrollIntoViewIfNeeded = () => {
            const rect = targetElement.getBoundingClientRect();
            const isInView = (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
            
            if (!isInView) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                
                // Update highlight position after scroll animation completes
                setTimeout(updateHighlightPosition, 500);
            }
        };
        
        // Make sure element is in view
        scrollIntoViewIfNeeded();
        
        // Fade in new content
        tutorialContent.style.opacity = '1';
        tutorialContent.style.transform = 'translateY(0)';
        
        // Listen for scroll and resize events to update highlight position
        window.addEventListener('scroll', updateHighlightPosition, { passive: true });
        window.addEventListener('resize', updateHighlightPosition, { passive: true });
        
        // Set a periodic check for position updates to handle dynamic content changes
        const positionInterval = setInterval(updateHighlightPosition, 200);
        
        // Cleanup event listeners when moving to next step
        return () => {
            window.removeEventListener('scroll', updateHighlightPosition);
            window.removeEventListener('resize', updateHighlightPosition);
            clearInterval(positionInterval);
            if (document.getElementById('highlightBox')) document.getElementById('highlightBox').remove();
            if (document.getElementById('pulse')) document.getElementById('pulse').remove();
        };
    }, 300);
};
function showNewUserTutorial() {
    // Check if the user has seen the tutorial before
    if (localStorage.getItem('captureTutorialSeen') === 'true') {
        return;
    }

    // Array of tutorial steps
    const tutorialSteps = [
        {
            element: '#openModal',
            title: 'Welcome to Image Capture!',
            message: 'Click here to upload images from your device.',
            position: 'bottom'
        },
        {
            element: '#openCamera',
            title: 'Use Your Camera',
            message: 'Capture images directly using your device camera.',
            position: 'bottom'
        },
        {
            element: '#imageContainer',
            title: 'Image Storage',
            message: 'Your uploaded images will appear here.',
            position: 'top'
        },
        {
            element: '#analyzeGraph',
            title: 'Analyze Your Images',
            message: 'Move images here for analysis and extraction.',
            position: 'top'
        },
        {
            element: '#extractTextButton',
            title: 'Extract Text',
            message: 'Once you have images, click here to extract text for your reviewers.',
            position: 'left'
        }
    ];

    // Create tutorial overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center';
    document.body.appendChild(overlay);

    // Tutorial container
    const tutorialContainer = document.createElement('div');
    tutorialContainer.className = 'bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4';
    tutorialContainer.innerHTML = `
        <h2 class="text-xl font-bold text-blue-800 mb-4">How to Use Capture</h2>
        <div id="tutorialContent" class="mb-4"></div>
        <div class="flex items-center justify-between">
            <button id="skipTutorial" class="text-gray-600 hover:text-gray-900">Skip Tutorial</button>
            <div>
                <button id="prevTutorial" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 hidden">Previous</button>
                <button id="nextTutorial" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Next</button>
            </div>
        </div>
    `;
    
    overlay.appendChild(tutorialContainer);
    
    let currentStep = 0;
    
    const showStep = (step) => {
        const tutorialContent = document.getElementById('tutorialContent');
        const prevButton = document.getElementById('prevTutorial');
        const nextButton = document.getElementById('nextTutorial');
        
        // Show/hide previous button based on step
        if (step > 0) {
            prevButton.classList.remove('hidden');
        } else {
            prevButton.classList.add('hidden');
        }
        
        // Update next button text on last step
        if (step === tutorialSteps.length - 1) {
            nextButton.textContent = 'Finish';
        } else {
            nextButton.textContent = 'Next';
        }
        
        const targetElement = document.querySelector(tutorialSteps[step].element);
        if (!targetElement) {
            console.error(`Tutorial target element not found: ${tutorialSteps[step].element}`);
            return;
        }
        
        // Position the highlight and content
        const rect = targetElement.getBoundingClientRect();
        
        // Create content for this step
        tutorialContent.innerHTML = `
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-blue-700">${tutorialSteps[step].title}</h3>
                <p class="text-gray-700 mt-2">${tutorialSteps[step].message}</p>
            </div>
            <div class="border-2 border-blue-500 border-dashed absolute pointer-events-none" style="
                top: ${rect.top - 4}px;
                left: ${rect.left - 4}px;
                width: ${rect.width + 8}px;
                height: ${rect.height + 8}px;
                z-index: 70;
            "></div>
        `;
    };
    
    // Initialize first step
    showStep(currentStep);
    
    // Event handlers for tutorial navigation
    document.getElementById('nextTutorial').addEventListener('click', () => {
        currentStep++;
        
        if (currentStep >= tutorialSteps.length) {
            // End tutorial
            localStorage.setItem('captureTutorialSeen', 'true');
            overlay.remove();
            return;
        }
        
        showStep(currentStep);
    });
    
    document.getElementById('prevTutorial').addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
    
    document.getElementById('skipTutorial').addEventListener('click', () => {
        localStorage.setItem('captureTutorialSeen', 'true');
        overlay.remove();
    });
}

// Add this to your document.addEventListener('DOMContentLoaded', function() {...}) block
// Call the function to show the tutorial
showNewUserTutorial();

// Add a button to reset tutorial (for testing or user preference)
function addTutorialResetOption() {
    const settingsContainer = document.createElement('div');
    settingsContainer.className = 'fixed bottom-4 right-4 z-50';
    settingsContainer.innerHTML = `
        <button id="resetTutorial" class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium p-2 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </button>
    `;
    document.body.appendChild(settingsContainer);
    
    document.getElementById('resetTutorial').addEventListener('click', () => {
        localStorage.removeItem('captureTutorialSeen');
        showNewUserTutorial();
    });
}

// Add the reset button option
addTutorialResetOption();
    });
    </script>
</x-layout>