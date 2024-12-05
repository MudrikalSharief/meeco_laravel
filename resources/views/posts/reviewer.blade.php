<x-layout>
    <div class="flex items-center">
        <a href="{{ route('subject') }}">
            <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects</h1>
        </a>
        <a href="#">
            <h2 class="font-semibold text-xl text-blue-500"> > Topics</h2>
        </a>
    </div>

    <div class="max-w-3xl mx-auto my-10 p-6 bg-white  rounded-lg ">
        <!-- Buttons -->
        <div class="flex gap-2 space-x-4 mb-6">
            <button class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">Reviewer</button>
            <button class="py-2 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">Quizzes</button>
        </div>

        <!-- Content Header -->
        <hr class="my-3">
        <div class="flex items-center justify-between mb-4">
            <h1 class="TITLE text-xl font-bold text-gray-800">Topic Name</h1>
            <button id="toggleButton" class="text-blue-500 text-sm font-medium rounded-lg hover:underline">Raw text</button>
        </div>

        <!-- Scrollable Content Box -->
        <div class="Reviewer border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            
            <h1> - Programming is a way to communicate with computers by giving them instructions.</h1>
            <h1> - It’s similar to how we use languages like Hindi or English to talk to each other.</h1>
            <h1> - C is a programming language.</h1>
            <h1> - It was developed by Dennis Ritchie at AT&T's Bell Labs in the USA in 1972.</h1>
            <h1> - It is one of the oldest and most reliable programming languages.</h1>
            <h1> - Uses of C Language</h1>
            <h1> - Operating Systems:
                Major parts of Windows, Linux, and other operating systems are written in C.</h1>
            <h1> - Device Drivers:
                C is used to write programs for devices like tablets and printers.</h1>
            <h1> - Embedded Systems:
                Used for devices with limited memory, like microwaves and cameras.</h1>
            <h1> - Game Development:
                Perfect for creating games due to its fast response to user inputs.</h1>
        </div>

        <div class="Rawtext hidden border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            <h1>URBAN
                EDG
                What is Programming?
                Computer Programming is a medium for us to communicate
                with computers. Just like we use "Hindi" or "English" I
                to Communicate with each other, programming is a
                way for us to deliver our instructions to the Computer..
                What is C
                C is a programming language.
                C is one of the oldest and finest programming
                languages
                C was developed by Dennis Ritchie at AT&T'Ś
                Bell Labs, USA in 1972.
                Uses of C
                C Language is used to program a wide variety of
                Systems. Some of the uses of C are as follows:
                17 Major parts of Windows, Linux and other operating
                Systems are written in C.
                27 C is used to write driver programs for devices like
                Tablets, printers etc.
                32 C language is used to program embedded systems where
                progrants need to run faster in limited memory (Microwave,
                "Cameras etc.)
                42 C is used to develop games, an area where latency is very
                I important. ic. Computer has to react quickly on user input.</h1>
            
         </div>

         <div class="Questions border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
    
         </div>
     
    </div>

   
    <div id="extractTextModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Extract Text</h2>
            <!-- Modal content here -->
            <div class="flex justify-end mt-4">
                <button id="cancelExtractTextModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="confirmExtractText" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Extract</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            const reviewerSection = document.querySelector('.Reviewer');
            const rawTextSection = document.querySelector('.Rawtext');
            const questionSection = document.querySelector('.Question');
            const TITLE = document.querySelector('.TITLE');
            const button = document.getElementById('toggleButton');

            reviewerSection.classList.toggle('hidden');
            rawTextSection.classList.toggle('hidden');

            if (reviewerSection.classList.contains('hidden')) {
                button.textContent = 'Reviewer';
                TITLE.textContent="Raw Text"
            } else {
                button.textContent = 'Raw text';
                TITLE.textContent="Topic Name";
            }
        });
    </script>

</x-layout>
