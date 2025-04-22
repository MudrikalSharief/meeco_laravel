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
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <div class="relative">
                <video id="cameraFeed" class="w-full h-full" autoplay></video>
                <div id="cameraTypeLabel" class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs py-1 px-2 rounded">
                    Front Camera
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button id="switchCamera" class="bg-blue-500 text-white px-4 py-2 rounded mr-2 hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7C8 5.93913 8.42143 4.92172 9.17157 4.17157C9.92172 3.42143 10.9391 3 12 3C13.0609 3 14.0783 3.42143 14.8284 4.17157C15.5786 4.92172 16 5.93913 16 7H18L19 9H5L6 7H8Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V9" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14L12 17M12 17L15 14M12 17V11" />
                    </svg>
                    Flip Camera
                </button>
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
        const captureImage = document.getElementById('captureImage');
        const cameraFeed = document.getElementById('cameraFeed');
        const cameraModal = document.getElementById('cameraModal');
        const closeCamera = document.getElementById('closeCamera');
        const switchCamera = document.getElementById('switchCamera');
        const cameraTypeLabel = document.getElementById('cameraTypeLabel');
        const captureConfirmModal = document.getElementById('captureConfirmModal');
        const captureConfirmMessage = document.getElementById('captureConfirmMessage');
        const closeCaptureConfirm = document.getElementById('closeCaptureConfirm');

        // Variables to track camera devices
        let currentStream = null;
        let availableCameras = [];
        let currentCameraIndex = 0;
        let isFrontCamera = true; // Track if we're using front or back camera

        // Check if device has multiple cameras
        async function getAvailableCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                return devices.filter(device => device.kind === 'videoinput');
            } catch (error) {
                console.error('Error enumerating devices:', error);
                return [];
            }
        }

        // Helper function to determine if a camera is front-facing
        // This isn't 100% reliable but works for most mobile devices
        function isFrontFacingCamera(label) {
            const frontLabels = ['front', 'user', 'selfie', 'face'];
            label = label.toLowerCase();
            return frontLabels.some(term => label.includes(term));
        }

        // Function to start camera with specific constraints
        async function startCamera(deviceId = null, preferFront = true) {
            try {
                // Stop any existing stream first
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                // Get all video devices first if we don't have them yet
                if (availableCameras.length === 0) {
                    availableCameras = await getAvailableCameras();
                }

                // Set constraints based on available deviceId
                let constraints = {
                    video: true,
                    audio: false
                };

                // If we have deviceId, use it
                if (deviceId) {
                    constraints.video = { deviceId: { exact: deviceId } };
                } 
                // If on mobile and we have multiple cameras, try to choose based on preference
                else if (availableCameras.length > 1 && !deviceId) {
                    // Look for likely front/back cameras
                    let frontCamera = null;
                    let backCamera = null;
                    
                    for (const camera of availableCameras) {
                        if (camera.label) {
                            if (isFrontFacingCamera(camera.label)) {
                                frontCamera = camera;
                            } else {
                                backCamera = camera;
                            }
                        }
                    }
                    
                    // Choose the appropriate camera based on preference
                    if (preferFront && frontCamera) {
                        constraints.video = { deviceId: { exact: frontCamera.deviceId } };
                        isFrontCamera = true;
                    } else if (!preferFront && backCamera) {
                        constraints.video = { deviceId: { exact: backCamera.deviceId } };
                        isFrontCamera = false;
                    } else if (frontCamera) {
                        constraints.video = { deviceId: { exact: frontCamera.deviceId } };
                        isFrontCamera = true;
                    } else if (backCamera) {
                        constraints.video = { deviceId: { exact: backCamera.deviceId } };
                        isFrontCamera = false;
                    }
                    // If no identified cameras, fall back to default
                }

                // For iOS/Safari specific handling
                if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                    if (preferFront) {
                        constraints.video = { facingMode: 'user' };
                        isFrontCamera = true;
                    } else {
                        constraints.video = { facingMode: 'environment' };
                        isFrontCamera = false;
                    }
                }

                // Get the stream
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                cameraFeed.srcObject = stream;
                currentStream = stream;

                // Update camera label
                updateCameraLabel();

                // Check available cameras after permissions granted
                availableCameras = await getAvailableCameras();
                
                // Show/hide switch camera button based on available cameras
                if (availableCameras.length > 1) {
                    switchCamera.classList.remove('hidden');
                    
                    // Find index of current camera
                    const currentTrack = stream.getVideoTracks()[0];
                    const currentDeviceId = currentTrack.getSettings().deviceId;
                    currentCameraIndex = availableCameras.findIndex(device => device.deviceId === currentDeviceId);
                    if (currentCameraIndex === -1) currentCameraIndex = 0;
                } else {
                    switchCamera.classList.add('hidden');
                }
                
                return true;
            } catch (error) {
                console.error('Error accessing camera:', error);
                captureConfirmMessage.textContent = 'Unable to access camera. Please check permissions.';
                captureConfirmModal.classList.remove('hidden');
                return false;
            }
        }

        // Update camera type label based on current camera
        function updateCameraLabel() {
            if (currentStream) {
                const videoTrack = currentStream.getVideoTracks()[0];
                if (videoTrack) {
                    const settings = videoTrack.getSettings();
                    const label = videoTrack.label || '';

                    // Set label text based on camera detection
                    if (isFrontCamera || isFrontFacingCamera(label) || label.toLowerCase().includes('front')) {
                        cameraTypeLabel.textContent = 'Front Camera';
                    } else {
                        cameraTypeLabel.textContent = 'Back Camera';
                    }
                }
            }
        }

        // Open camera modal and initialize camera
        if (openCamera) {
            openCamera.addEventListener('click', async () => {
                cameraModal.classList.remove('hidden');
                await startCamera(null, true); // Start with front camera by default
            });
        }

        // Switch between front and back cameras
        if (switchCamera) {
            switchCamera.addEventListener('click', async () => {
                isFrontCamera = !isFrontCamera; // Toggle front/back preference
                await startCamera(null, isFrontCamera);
            });
        }

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
                        
                        // Stop camera stream when we're done
                        if (cameraFeed.srcObject) {
                            const stream = cameraFeed.srcObject;
                            const tracks = stream.getTracks();
                            tracks.forEach(track => track.stop());
                            cameraFeed.srcObject = null;
                            currentStream = null;
                        }
                        fetchImages(); // Using our updated fetchImages function will handle this correctly
                    })
                    .catch(error => {
                        cameraModal.classList.add('hidden');
                        captureConfirmMessage.textContent = 'Failed to upload captured image.';
                        captureConfirmModal.classList.remove('hidden');
                        console.error('Error:', error);
                        
                        // Also stop camera stream on error
                        if (cameraFeed.srcObject) {
                            const stream = cameraFeed.srcObject;
                            const tracks = stream.getTracks();
                            tracks.forEach(track => track.stop());
                            cameraFeed.srcObject = null;
                            currentStream = null;
                        }
                    });
                }, 'image/png');
            });
        }

        if (closeCamera) {
            closeCamera.addEventListener('click', () => {
                cameraModal.classList.add('hidden');
                if (cameraFeed.srcObject) {
                    const stream = cameraFeed.srcObject;
                    const tracks = stream.getTracks();
                    tracks.forEach(track => track.stop());
                    cameraFeed.srcObject = null;
                    currentStream = null;
                }
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

    

    });
    </script>
</x-layout>