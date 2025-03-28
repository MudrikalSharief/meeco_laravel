<x-contact_layout>
    <main class="mt-5">
        <div class="breadcrumb mb-2 flex  font-bold text-md px-4">
            <a href="{{ route('contact') }}" class="text-gray-500 no-underline">← My Inquiry</a>
        </div>
        <div class="form-container w-[90%] md:w-[70%] bg-white p-8 rounded-lg shadow-md mx-auto md:ml-48">
            <h1 class="text-blue-700 text-2xl mb-6 font-medium">Inquiry (Message Form)</h1>
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <form action="{{ route('submitInquiry') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-5">
                    <label for="email" class="block text-gray-600 mb-2 text-sm">Email</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" readonly class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">This email is associated with your account and cannot be changed.</p>
                </div>
    
                <div class="form-group mb-5">
                    <label for="category" class="block text-gray-600 mb-2 text-sm">Category</label>
                    <select id="category" name="category" required class="w-full p-3 border border-gray-300 rounded-lg text-sm appearance-none bg-white focus:rounded-none">
                        <option value="" disabled selected>Select</option>
                        <option value="Login Issue">Login Issue</option>
                        <option value="Conversion Problem">Conversion Problem</option>
                        <option value="Reveiwer Problem">Reveiwer Problem</option>
                        <option value="Quiz Problem">Quiz Problem</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
    
                <div class="form-group mb-5">
                    <label for="subject" class="block text-gray-600 mb-2 text-sm">Subject <span class="info-icon inline-block w-4 h-4 bg-gray-300 rounded-full text-center leading-4 text-xs text-white ml-1">i</span></label>
                    <input type="text" id="subject" name="subject" required class="w-full p-3 border border-gray-300 rounded-lg text-sm">
                </div>
    
                <div class="form-group mb-5">
                    <label for="question" class="block text-gray-600 mb-2 text-sm">Question</label>
                    <textarea id="question" name="question" required class="w-full p-3 border border-gray-300 rounded-lg text-sm min-h-[150px] resize-y"></textarea>
                </div>
    
                <div class="form-group mb-5">
                    <label class="block text-gray-600 mb-2 text-sm">Upload</label>
                    <div class="upload-section mb-2">
                        <div class="file-input mb-2 border border-gray-300 rounded-lg p-3">
                            <input type="file" name="upload[]" accept=".jpg,.jpeg,.png,.svg" class="w-full">
                        </div>
                        <div class="file-input mb-2 border border-gray-300 rounded-lg p-3">
                            <input type="file" name="upload[]" accept=".jpg,.jpeg,.png,.svg" class="w-full">
                        </div>
                    </div>
                    <p class="file-note text-gray-600 text-xs mt-1">Note: Only jpg, jpeg, svg, and png format is supported; File size must not exceed 5MB.</p>
                </div>
    
                <div class="button-group flex gap-2 justify-end mt-5">
                    <a href="{{ route('contact') }}" class="btn btn-secondary border border-transparent bg-gray-200 text-gray-800 p-3 rounded-lg text-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary bg-[#66B3FF] text-white p-3 rounded-lg text-sm hover:bg-blue-700">Send</button>
                </div>
            </form>
        </div>
    </main>
</x-contact_layout>