<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raw;
use App\Models\Reviewer;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class RawController extends Controller
{
    public function storeExtractedText(Request $request)
    {
        try {
            $request->validate([
                'topic_id' => 'required|integer',
                'raw_text' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        $raw = Raw::updateOrCreate(
            ['topic_id' => $request->topic_id],
            ['raw_text' => $request->raw_text]
        );

        return response()->json(['success' => true, 'message' => 'Extracted text saved successfully.', 'raw_text' => $raw->raw_text]);
    }

    public function getRawText(Request $request)
    {   
    
        //return the raw text
        $rawText = Raw::where('topic_id', $request->topic_id)->first()->raw_text ?? '';
        return response()->json(['raw_text' => $rawText]);
    }
    
    public function UpdateAndGet_RawText(Request $request)
    {   
        $request->validate([
            'topic_id' => 'required|integer',
            'raw_text' => 'required|string',
        ]);

        Raw::updateOrCreate(
            ['topic_id' => $request->topic_id],
            ['raw_text' => $request->raw_text]
        );
    
        //return the raw text
        $rawText = Raw::where('topic_id', $request->topic_id)->first()->raw_text ?? '';
        return response()->json(['raw_text' => $rawText]);
    }

    public function extractText(Request $request)
    {   
        set_time_limit(300); // Set the maximum execution time to 300 seconds
        $startTime = time(); // Record the start time

        $request->validate([
            'topic_id' => 'required|integer',
        ]);

        $rawText = Raw::where('topic_id', $request->topic_id)->first()->raw_text ?? '';

        if (empty($rawText)) {
            try {
                
                $imageAnnotatorClient = new ImageAnnotatorClient();
                $image_paths = glob(storage_path('app/public/uploads/*.{jpg,jpeg,png,gif}'), GLOB_BRACE);
                $extractedText = '';
                foreach ($image_paths as $index => $image_path) {
                    
                    $imageContent = file_get_contents($image_path);
                    $response = $imageAnnotatorClient->textDetection($imageContent,['timeout' => 300]);
                    $text = $response->getTextAnnotations();
                    $extractedText .= '=Image ' . ($index + 1) . '=' . PHP_EOL;
                    $extractedText .= $text[0]->getDescription() . PHP_EOL . PHP_EOL;
                    if ($error = $response->getError()) {
                        $extractedText .= 'API Error: ' . $error->getMessage() . PHP_EOL;
                    }
                }
                
                $imageAnnotatorClient->close();

                Raw::updateOrCreate(
                    ['topic_id' => $request->topic_id],
                    ['raw_text' => $extractedText]
                );

                return response()->json(['success' => true, 'raw_text' => $extractedText]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            if(set_time_limit(300)){
                return response()->json(['success' => false, 'message' => 'time limit reach']);
            }
        } else {
            return response()->json(['success' => true, 'raw_text' => $rawText]);
        }
    }


 
    

    public function storeReviewer(Request $request)
    {
        try {
            $request->validate([
                'topic_id' => 'required|integer',
                'reviewer_text' => 'required|string',
            ]);

            Reviewer::updateOrCreate(
                ['topic_id' => $request->topic_id],
                ['reviewer_text' => $request->reviewer_text]
            );

            return redirect()->route('reviewer/', ['topicId' => $request->topic_id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
}
