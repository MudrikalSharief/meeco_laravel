<x-layout>

    <h1 class="py-3 px-2 text-xl font-bold">Convert Image</h1>

    <button id="openModal" type="button" class=" bg-blue-50 p-2 rounded-md btn btn-primary w-24 ml-8 mt-3 flex flex-col items-center" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <img class=" z-10 w-8 filter-blue"  src="{{ asset('logo_icons/add-image.svg')}}" alt="">
        <p>Add Image</p>
    </button>

    <!-- Container for Uploaded Images -->
    <h2 class="py-3 px-2 text-base font-medium ">Image uploaded</h2>
    <div id="imageContainer" class="mt-4 flex flex-wrap"></div>

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
                        <input id="imageInput" type="file" name="images[]" id="images" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" multiple>
                    </div>
                </form>
            </div>
            <div class="flex justify-end border-t px-4 py-2">
                <button class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600" id="cancelUpload">Cancel</button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" id="uploadButton">Upload</button>
            </div>
    </div>



</x-layout>