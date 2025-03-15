<div id="{{ $id }}" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-4 w-1/2 min-w-60 max-w-96">
        <h2 id="{{ $id }}-title" class="text-lg font-semibold mb-4 {{ $titleColor }}">{{ $title }}</h2>
        <hr class="mb-2">
        <p id="{{ $id }}-message">{{ $message }}</p>
        <div class="flex justify-end mt-4 gap-4">
            <button id="{{ $id }}-close" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
            <button id="{{ $buttonId }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ $buttonText }}</button>
        
        </div>
    </div>
</div>