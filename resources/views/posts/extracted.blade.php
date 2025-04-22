<?php
     require_once base_path('vendor/autoload.php');
     use Google\Cloud\Vision\V1\ImageAnnotatorClient;
     use App\Models\Raw;
     use App\Http\Controllers\RawController;

     $rawText = Raw::where('topic_id', request('topic_id'))->first()->raw_text ?? '';
?>
<x-layout>
    <div id="loading_modal" class="hidden z-50  flex flex-col absolute top-0 left-0 h-screen w-screen bg-gray-300 bg-opacity-70 flex items-center justify-center ">
        <video class="z-10 w-36 filter-blue" autoplay loop muted>
            <source src="{{ asset('logo_icons/ExtractingText.webm') }}" type="video/webm">
            Your browser does not support the video tag.
        </video>
        <p class="text-blue-600">Extracting Text</p>
        
    </div>
    <div class="p-6 h-full  md:overflow-hidden">
        <h1 class="pb-3 text-xl font-bold text-blue-500">Extracted Text for Topic: {{ request('topic_name') }}</h1>
        <div class="flex flex-col md:flex-row h-screen">
            <div class="image-container md:max-h-[80vh] md:w-1/3 w-full">
                <h3 class="mb-2 text-blue-400">Images Uploaded <hr></h3>
                <div class="image_holder flex gap-3 md:flex-col overflow-x-auto md:overflow-y-auto scrollable">
                    <?php
                    $userid = auth()->user()->user_id;
                    $directory = storage_path('app/public/uploads/user_' . $userid . '/imagecontainer');
                    $image_paths = glob($directory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    foreach ($image_paths as $index => $image_path) {
                        $publicImagePath = str_replace(storage_path('app/public'), 'storage', $image_path);
                        ?>
                        <div class="mb-4 mt-2 flex flex-col items-center min-w-32">
                            <img src="<?php echo asset($publicImagePath); ?>" alt="Image" class="w-32 h-32 object-cover cursor-pointer" onclick="openModal('<?php echo asset($publicImagePath); ?>')">
                            <p class="text-center">Image <?php echo $index + 1; ?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <h3 class="mt-6 mb-2 text-blue-400">Graphs <hr></h3>
                <div class="graph_holder flex gap-3 md:flex-col overflow-x-auto md:overflow-y-auto scrollable">
                    <?php
                    $graphDirectory = storage_path('app/public/uploads/user_' . $userid . '/graph');
                    if (file_exists($graphDirectory)) {
                        $graph_paths = glob($graphDirectory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        foreach ($graph_paths as $index => $graph_path) {
                            $publicGraphPath = str_replace(storage_path('app/public'), 'storage', $graph_path);
                            $filename = basename($graph_path);
                            ?>
                            <div class="mb-4 mt-2 flex flex-col items-center min-w-32">
                                <img src="<?php echo asset($publicGraphPath); ?>" alt="<?php echo $filename; ?>" class="w-32 h-32 object-cover cursor-pointer" 
                                     onclick="openModal('<?php echo asset($publicGraphPath); ?>')">
                                <p class="text-center">Graph <?php echo $index + 1; ?></p>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-gray-500 italic'>No graphs available</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="text-container  w-full md:w-2/3 md:pl-4 pt-4 md:pt-0">
                <h3 class=" pb-2 text-blue-400">Extracted Text <hr></h3>
                <form id="extractedTextForm" class="w-full h-4/5" method="POST" action="/store-extracted-text">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ request('topic_id') }}">
                    <textarea name="raw_text" class="extractedTA w-full h-full min-h-96 md:h-3/4 p-2 border rounded"><?php echo htmlspecialchars($rawText); ?></textarea>
                    <button type="submit" id="genereate_reviewer_button" class="generate_reviewer mb-5 md:mb-0 bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600">Generate Reviewer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" onclick="closeModal(event)">
        <div class="bg-white p-4 rounded" onclick="event.stopPropagation()" style="width: 80%; min-width: 270px;">
            <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full">
        </div>
    </div>

    <!-- Graph Analysis Modal -->
    <div id="graphAnalysisModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" onclick="closeGraphModal(event)">
        <div class="bg-white p-4 rounded max-w-2xl w-full" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-blue-500">Graph Analysis</h2>
                <button onclick="closeGraphModal(event)" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 p-2">
                    <img id="graphModalImage" src="" alt="Graph" class="w-full object-contain max-h-64">
                    <p id="graphFilename" class="text-center text-sm text-gray-500 mt-2"></p>
                </div>
                <div class="md:w-1/2 p-2">
                    <div id="graphAnalysisLoader" class="flex justify-center items-center h-32">
                        <div class="loader" style="width: 40px; height: 40px; border-width: 5px;"></div>
                    </div>
                    <div id="graphAnalysisContent" class="hidden">
                        <h3 id="graphTitle" class="font-bold text-lg text-blue-600 mb-2"></h3>
                        <div class="mb-3">
                            <p class="text-gray-700" id="graphSummary"></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-500 mb-1">Notable Trends:</h4>
                            <ul id="graphTrends" class="list-disc pl-5 text-gray-700"></ul>
                        </div>
                    </div>
                    <div id="graphAnalysisError" class="hidden text-red-500">
                        Error analyzing graph. Please try again.
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button onclick="closeGraphModal(event)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded" style="width: 80%; min-width: 270px;">
            <p>Reviewer Generated!</p>
                
                <button id="ReviewerGeneratedButton" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600">Okay</button>
            
        </div>
    </div>


    {{-- Loader --}}
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="relative">
            <div class="loader"></div>
            <p class="absolute text-white text-center w-full -bottom-12 font-medium">This may take a few minutes</p>
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
            const extractedTextForm = document.getElementById('extractedTextForm');
            const extractedTextArea = extractedTextForm.querySelector('textarea[name="raw_text"]');
            const topicId = extractedTextForm.querySelector('input[name="topic_id"]').value;
            const bottomNav = document.getElementById('bottom_nav');
            bottomNav.classList.add('hidden');

            const loader = document.getElementById('loader');
            
           

            fetch('/get-raw-text', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ topic_id: topicId})
            })
            .then(response => response.json())
            .then(data => {
                if (data.raw_text.trim() !== '') {
                    extractedTextArea.value = data.raw_text;
                    // Check if raw text already has graph analysis
                    if (!data.raw_text.includes("--- GRAPH ANALYSES ---")) {
                        // Add a button to analyze graphs instead of doing it automatically
                        const analyzeGraphsButton = document.createElement('button');
                        analyzeGraphsButton.textContent = 'Analyze Graphs';
                        analyzeGraphsButton.className = 'bg-blue-500 text-white px-4 py-2 rounded mt-2 mr-2 hover:bg-blue-600';
                        analyzeGraphsButton.addEventListener('click', analyzeAllGraphsAndAddToText);
                        
                        const submitButton = document.getElementById('genereate_reviewer_button');
                        submitButton.parentNode.insertBefore(analyzeGraphsButton, submitButton);
                    }
                } else {
                     // Show the loader
                    loader.classList.remove('hidden');
                    
                    fetch('/extract-text', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ topic_id: topicId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            throw new Error('Response is not JSON');
                        }
                    })
                    .then(data => {
                         // Hide the loader
                        loader.classList.add('hidden');
                        
                        console.log('Success:', data);
                        // Display the returned raw_text in the textarea
                        extractedTextArea.value = data.raw_text;
                        
                        // After text extraction, analyze graphs and add results to text
                        analyzeAllGraphsAndAddToText();
                    })
                    .catch((error) => {
                         // Hide the loader
                         loader.classList.add('hidden');
                         alert('error');
                        console.error('Error:', error);
                    });
                }
            })
            .catch((error) => {
                 // Hide the loader
                 loader.classList.add('hidden');
                 alert('error');
                console.error('Error:', error);
            });


            //======= the reviewer genereate button is clicked here ==================================================================
            extractedTextForm.addEventListener('submit', function(event) {
                event.preventDefault();
                fetch('/UpdateAndGet_RawText', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ topic_id: topicId, raw_text:extractedTextArea.value})
                })
                .then(response => response.json())
                .then(() => {
                    loader.classList.remove('hidden');
                    fetch('/openai/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ content: extractedTextArea.value, topic_id: topicId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        loader.classList.add('hidden');

                        if (data.success) {
                            document.getElementById('successModal').classList.remove('hidden');
                            console.log('Success: OpenAI has created the reviewer');
                        } else {
                            // Show error to user
                            console.error('Error:', data.message);
                            alert('Error generating reviewer: ' + data.message);
                        }
                    })
                    .catch((error) => {
                        loader.classList.add('hidden');
                        console.error('Error:', error);
                        alert('An error occurred: ' + error.message);
                    });
                })
                .catch((error) => {
                    loader.classList.add('hidden');
                    console.error('Error updating raw text:', error);
                    alert('Error updating text: ' + error.message);
                });
            });

            //======================================================================
            //this is for the ReviewerGeneratedButton button clicked
            const ReviewerGeneratedButton = document.getElementById('ReviewerGeneratedButton');
            ReviewerGeneratedButton.addEventListener('click', function() {
                document.getElementById('successModal').classList.add('hidden');
                const topicId = document.querySelector('input[name="topic_id"]').value;
                window.location.href = `/reviewer/${topicId}`;
            });
            
            //=======================================================================================================
            function closeModal(event) {
                if (event.target.id === 'imageModal') {
                    document.getElementById('imageModal').classList.add('hidden');
                }
            }

            function closeSuccessModal() {
                document.getElementById('successModal').classList.add('hidden');
            }

            window.openModal = function(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').classList.remove('hidden');
            }
            
            window.closeModal = function(event) {
                if (event.target.id === 'imageModal') {
                    document.getElementById('imageModal').classList.add('hidden');
                }
            }
            
            async function analyzeAllGraphsAndAddToText() {
                const extractedTextArea = document.querySelector('textarea[name="raw_text"]');
                const loader = document.getElementById('loader');
                
                // Check if there are any graphs
                const graphElements = document.querySelectorAll('.graph_holder img');
                if (graphElements.length === 0) {
                    console.log('No graphs to analyze');
                    alert('No graphs found to analyze.');
                    return;
                }
                
                // Show loader
                loader.classList.remove('hidden');
                
                try {
                    let allAnalysisText = "\n\n--- GRAPH ANALYSES ---\n\n";
                    let graphsAnalyzed = 0;
                    
                    // Analyze each graph one by one
                    for (let i = 0; i < graphElements.length; i++) {
                        const graphImg = graphElements[i];
                        const imageSrc = graphImg.src;
                        const filename = graphImg.getAttribute('alt') || `Graph ${i+1}`;
                        
                        console.log(`Analyzing graph ${i+1}/${graphElements.length}: ${filename}`);
                        
                        try {
                            // Add CSRF token to headers explicitly
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            
                            // Make API request to analyze the graph
                            const response = await fetch('/openai/analyze-graph', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    image_url: imageSrc,
                                    image_id: filename,
                                    detailed: true // Request detailed analysis
                                })
                            });
                            
                            if (!response.ok) {
                                console.error(`Error analyzing graph ${i+1}: HTTP ${response.status}`);
                                const errorText = await response.text();
                                console.error(`Error details: ${errorText}`);
                                allAnalysisText += `GRAPH ${i+1}: ${filename}\nAnalysis failed (HTTP ${response.status})\n\n`;
                                continue;
                            }
                            
                            const data = await response.json();
                            
                            if (data.error) {
                                console.error(`Error analyzing graph ${i+1}:`, data.error);
                                allAnalysisText += `GRAPH ${i+1}: ${filename}\n${data.error}\n\n`;
                                continue;
                            }
                            
                            if (data.result && data.result.trim() !== '' && data.result !== 'no graph detected') {
                                // Format the graph analysis with a header including the image name
                                allAnalysisText += `GRAPH ${i+1}: ${filename}\n${data.result}\n\n`;
                                graphsAnalyzed++;
                            } else if (data.result === 'no graph detected') {
                                console.log(`Image ${filename} is not a graph/chart - skipping`);
                            }
                        } catch (error) {
                            console.error(`Error processing graph ${i+1}:`, error);
                            allAnalysisText += `GRAPH ${i+1}: ${filename}\nProcessing error: ${error.message}\n\n`;
                        }
                    }
                    
                    // Add the analysis text to the textarea
                    if (graphsAnalyzed > 0) {
                        extractedTextArea.value += allAnalysisText;
                        alert(`Successfully analyzed ${graphsAnalyzed} graphs and added the results to your text.`);
                        console.log(`Added analysis for ${graphsAnalyzed} graphs to text`);
                    } else {
                        alert('No valid graphs were detected for analysis.');
                        console.log('No valid graph analyses to add');
                    }
                    
                } catch (error) {
                    console.error('Error during graph analysis:', error);
                    alert('Error analyzing graphs: ' + error.message);
                } finally {
                    // Hide loader
                    loader.classList.add('hidden');
                }
            }
        });

        
    </script>
</x-layout>