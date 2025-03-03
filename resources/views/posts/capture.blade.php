<x-layout>
    <div class=" p-3 w-full h-fullx ">

        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Convert Image</h1>

        <div class="flex">

            <button id="openModal" type="button" class=" bg-blue-50 p-2 rounded-md btn btn-primary w-24 ml-8 mt-3 flex flex-col items-center" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <img class=" z-10 w-8 filter-blue"  src="{{ asset('logo_icons/add-image.svg')}}" alt="">
                <p  class=" text-sm mt-2">Add Image</p>
            </button>
    
            <button id="openCamera" type="button" class="bg-blue-50 p-2 rounded-md btn btn-primary w-24 ml-8 mt-3 flex flex-col items-center">
                <img class="z-10 w-8 filter-blue" src="{{ asset('logo_icons/camera-viewfinder.svg') }}" alt="">
                <p class=" text-sm mt-2">Camera</p>
            </button>
        </div>

        <hr class=" my-3">

        <!-- Container for Uploaded Images -->
        <h2 class=" py2 px-2 text-base font-medium ">Image uploaded</h2>
   
        <div id="imageContainer" class="mt-2 px-3 flex flex-wrap"></div>
        <div id="imageNamesContainer" class="mt-2 px-3 flex flex-wrap"></div>
        
        <button id="extractTextButton" type="button" class=" ml-5 bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600" data-bs-toggle="modal" data-bs-target="#extractTextModal">Extract Text</button>
        
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
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" id="uploadButton">Upload</button>
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
            <h2 id="captureConfirmMessage" class="text-lg font-semibold mb-4"></h2>
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

</x-layout>