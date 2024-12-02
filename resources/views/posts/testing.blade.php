<?php
require_once base_path('vendor/autoload.php');
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

try {
    $imageAnnotatorClient = new ImageAnnotatorClient();

    $image_path = 'storage/uploads/Q9bp9W5T0OuxJyQ4yS0QnTwRrueLT2ZsZFSv2N5n.png';
    $imageContent = file_get_contents($image_path);
    $response = $imageAnnotatorClient->textDetection($imageContent);
    $text = $response->getTextAnnotations();
    echo $text[0]->getDescription();

    if ($error = $response->getError()) {
        print('API Error: ' . $error->getMessage() . PHP_EOL);
    }

    $imageAnnotatorClient->close();
} catch(Exception $e) {
    echo $e->getMessage();
}
