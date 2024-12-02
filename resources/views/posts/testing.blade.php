<?php
require_once base_path('vendor/autoload.php');
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

try {
    $imageAnnotatorClient = new ImageAnnotatorClient();

    $image_paths = [
        'storage/uploads/3Q4h1P2UwUVXuBkkGi6EXhyppLIaajisqmZOavj2.png',
        'storage/uploads/Uk0tihgnd2ipBLGqge5eEcmkepIHnovQS37060xm.png'
    ];

    foreach ($image_paths as $image_path) {
        $imageContent = file_get_contents($image_path);
        $response = $imageAnnotatorClient->textDetection($imageContent);
        $text = $response->getTextAnnotations();
        echo $text[0]->getDescription() . PHP_EOL;

        if ($error = $response->getError()) {
            print('API Error: ' . $error->getMessage() . PHP_EOL);
        }
    }

    $imageAnnotatorClient->close();
} catch(Exception $e) {
    echo $e->getMessage();
}
