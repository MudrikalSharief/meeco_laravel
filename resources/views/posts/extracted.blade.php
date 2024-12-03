<?php
     require_once base_path('vendor/autoload.php');
     use Google\Cloud\Vision\V1\ImageAnnotatorClient;
?>
<x-layout>
    <div class="p-6 h-full  md:overflow-hidden">
        <h1 class="pb-3 text-xl font-bold text-blue-500">Extracting Text</h1>
        <div class="flex flex-col md:flex-row h-full">
            <div class="image-container  md:max-h-[80vh] md:w-1/3 w-full">
                <h3 class=" mb-2 text-blue-400">Images Uploaded <hr></h3>
                <div class="image_holder flex gap-3 md:flex-col overflow-x-auto md:overflow-y-auto scrollable">
                        <?php
                    try {
                        $imageAnnotatorClient = new ImageAnnotatorClient();
                        $image_paths = glob('storage/uploads/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        $extractedText = '';
                        foreach ($image_paths as $index => $image_path) {
                            $imageContent = file_get_contents($image_path);
                            $response = $imageAnnotatorClient->textDetection($imageContent);
                            $text = $response->getTextAnnotations();
                            $extractedText .=  '========== Image ' . ($index + 1) . "==========" .PHP_EOL;
                            $extractedText .= $text[0]->getDescription() . PHP_EOL . PHP_EOL;
                            if ($error = $response->getError()) {
                                $extractedText .= 'API Error: ' . $error->getMessage() . PHP_EOL;
                            }
                            ?>
                            <div class="mb-4 flex flex-col items-center min-w-32">
                                <img src="<?php echo $image_path; ?>" alt="Image" class="w-32 h-32 object-cover cursor-pointer" onclick="openModal('<?php echo $image_path; ?>')">
                                <p class="text-center">Image <?php echo $index + 1; ?></p>
                            </div>
                            <?php
                        }
                        $imageAnnotatorClient->close();
                    } catch(Exception $e) {
                        $extractedText .= $e->getMessage();
                    }
                ?>
                </div>
            </div>
            <div class="text-container  w-full md:w-2/3 md:pl-4 pt-4 md:pt-0">
                <h3 class=" pb-2 text-blue-400">Extracted Text <hr></h3>
                <textarea class="w-full h-[calc(100vh-16rem)] md:h-3/4 p-2 border rounded"><?php echo htmlspecialchars($extractedText); ?></textarea>
                <button id="generateReviewer" class="mb-5 md:mb-0 bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600">Generate Reviewer</button>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="imageModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" onclick="closeModal(event)">
        <div class="bg-white p-4 rounded" onclick="event.stopPropagation()" style="width: 80%; min-width: 270px;">
            <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full">
        </div>
    </div>

    
</x-layout>
