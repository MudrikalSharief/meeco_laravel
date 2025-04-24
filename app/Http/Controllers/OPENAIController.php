<?php

namespace App\Http\Controllers;

use App\Helpers\OpenAIHelper;
use App\Models\Identification;
use App\Models\multiple_choice;
use App\Models\Question;
use App\Models\Reviewer;
use App\Models\Subscription;
use App\Models\Topic;

use App\Models\true_or_false;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class OPENAIController extends Controller
{   

    public function gemma3(Request $request){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->timeout(300)
        ->post("https://generativelanguage.googleapis.com/v1beta/models/gemma-3-27b-it:generateContent?key=AIzaSyC2nOeBHRx7RQQ9PZCpxYX16MqDZ9b4eMQ", [
            'contents' => [
                [   'role'  => 'user',
                    'parts' => [
                        ['text' => 'You are an AI that helps generate organized study reviewers. A user has uploaded notes, and your task is to analyze the content  and divide the information accordingly. 
        
                                Here are the rules for processing:
                                1. Identify the main topics in the notes .
                                2. Break the topics content into smaller, logically grouped pieces that fit on individual cards.
                                3. Each piece should focus on a single concept or subtopic, with no piece being longer than 100 words.
                                4. If the notes are unclear, use your best judgment to organize the content logically while keeping it concise and easy to understand.
                                5. If there are term that you dont really know just disregard it and focus only on what you understand. do not create a reviewer in a specific topic if you doesnt know it.
                                6. Use simple and clear language suitable for a reviewer.
        
                                Example format for the output:
                                [
                                    {
                                                Topic, 
                                                Description of the topic.
                                    }
                                ]
        
                                Input notes: Chemistry is the study of matter, its properties, changes, and the energy involved in those changes. Matter exists in different states: solids (fixed shape and volume), liquids (fixed volume but no fixed shape), gases (no fixed shape or volume), and plasma (high-energy ionized gas). Chemistry is divided into several branches, including organic chemistry (study of carbon-based compounds), inorganic chemistry (study of non-carbon substances), physical chemistry (focuses on energy and reactions), analytical chemistry (identifying substances), and biochemistry (chemical processes in living organisms). Changes in matter can be physical, where no new substance is formed (like melting ice), or chemical, where a new substance is created (like rusting iron). Chemistry plays a crucial role in our daily lives, from the food we eat to the medicines we take. Understanding chemical reactions helps in fields like medicine, engineering, and environmental science. One of the most important tools in chemistry is the periodic table, which organizes elements based on their properties. Scientists use it to predict how different elements will react. Chemistry is everywhere, and learning it helps us understand the world around us better!
                        ']
                    ]
                ]
            ]
        ]);

        $responseBody = $response->body();
        $responseData = json_decode($responseBody, true);
        
        if ($response->failed()) {
            return response()->json(['success' => false, 'message' => 'Failed to communicate with GEMINI API.', 'data' => $responseData]);
        }


        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            return response()->json(['success' => false, 'message' => 'Invalid response format from GEMINI API.', 'data' => $responseData]);
        }

        
        // Fix 2: Extract the JSON content from the markdown code block
        $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'];
        
        // Remove markdown code block formatting if present
        $jsonText = preg_replace('/```json\n|\n```$/', '', $rawText);
        
        // Decode the JSON content
        $content = json_decode($jsonText, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to parse JSON response: ' . json_last_error_msg(),
                'rawText' => $rawText
            ]);
        }

        foreach($content as $item){
            $reviewer = new Reviewer;
            $reviewer->topic_id = 66;
            $reviewer->reviewer_about = $item['Topic'];
            $reviewer->reviewer_text = $item['Description'];
            $reviewer->save();
        }

       return response()->json(['success' => true, 'data' => $content]);
    }

    public function analyze_graph(Request $request)
    {
        Log::info('analyze_graph called', [
            'request' => $request->all(),
            'user_id' => auth()->id(),
            'method' => $request->method()
        ]);
        
        $userId = auth()->id();
        
        if (!$userId) {
            Log::error('User not authenticated in analyze_graph');
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $directoryPath = public_path('storage/uploads/user_' . $userId . '/graph');
        
        if (!file_exists($directoryPath)) {
            Log::info('Graph directory does not exist: ' . $directoryPath);
            return response()->json(['error' => 'Graph directory not found'], 404);     
        }
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $files = [];
        
        try {
            foreach (scandir($directoryPath) as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $allowedExtensions)) {
                    $fullPath = $directoryPath . '/' . $file;
                    $files[] = [
                        'filename' => $file,
                        'path' => $fullPath,
                        'modified' => filemtime($fullPath)
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error scanning directory', [
                'path' => $directoryPath,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Error accessing graph directory: ' . $e->getMessage()], 500);
        }
        
        if (empty($files)) {
            Log::info('No image files found in directory', ['path' => $directoryPath]);
            return response()->json([
                'images' => [],
                'message' => 'No graph images found. Please upload some graph images first.'
            ]);
        }
        
        Log::info('Found graph files', ['count' => count($files)]);
        
        usort($files, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
        
        $requestedImages = $request->input('images', []);
        if ($request->has('image_id')) {
            $requestedImages[] = $request->input('image_id');
        }
        
        if (empty($requestedImages) && !$request->has('analyze_latest') && !$request->has('image_url')) {
            Log::info('No specific images requested, returning list');
            $imageList = [];
            foreach ($files as $file) {
                $publicPath = str_replace(public_path('storage'), '/storage', $file['path']);
                $imageList[] = [
                    'filename' => $file['filename'],
                    'url' => asset($publicPath)
                ];
            }
            return response()->json(['images' => $imageList]);
        }
        
        $imagesToAnalyze = [];
        
        if ($request->has('image_url')) {
            $imageUrl = $request->input('image_url');
            $imagePath = null;
            
            foreach ($files as $file) {
                $publicPath = str_replace(public_path('storage'), '/storage', $file['path']);
                if (asset($publicPath) === $imageUrl || $publicPath === $imageUrl) {
                    $imagePath = $file['path'];
                    Log::info('Found matching file for URL', ['url' => $imageUrl, 'path' => $imagePath]);
                    $imagesToAnalyze[] = [
                        'filename' => $file['filename'],
                        'path' => $imagePath
                    ];
                    break;
                }
            }
            
            if (empty($imagesToAnalyze)) {
                Log::error('Could not find matching file for URL', ['url' => $imageUrl]);
                return response()->json(['error' => 'Could not find image file matching the provided URL'], 404);
            }
        }
        else if ($request->has('analyze_latest')) {
            Log::info('Analyzing latest image');
            $imagesToAnalyze[] = $files[0];
        } else if (!empty($requestedImages)) {
            Log::info('Analyzing specified images', ['requested' => $requestedImages]);
            foreach ($files as $file) {
                if (in_array($file['filename'], $requestedImages)) {
                    $imagesToAnalyze[] = $file;
                }
            }
        }
        
        if (empty($imagesToAnalyze)) {
            Log::error('No valid images found to analyze', [
                'requested' => $requestedImages,
                'available' => array_column($files, 'filename')
            ]);
            return response()->json(['error' => 'No valid images found to analyze'], 404);
        }

        $detailed = $request->input('detailed', false);
        
        try {
            $apiKey = OpenAIHelper::getApiKey();
            Log::info('Got API key for OpenAI');
            
            if (empty($apiKey)) {
                Log::error('OpenAI API Key is empty');
                return response()->json(['error' => 'API key is missing or invalid'], 500);
            }
            
            $results = [];
            
            foreach ($imagesToAnalyze as $image) {
                Log::info('Analyzing image', ['image' => $image['filename'], 'path' => $image['path']]);
                
                try {
                    $imageData = file_get_contents($image['path']);
                    if ($imageData === false) {
                        Log::error('Failed to read image file', ['path' => $image['path']]);
                        $results[] = [
                            'image' => $image['filename'],
                            'image_url' => str_replace(public_path('storage'), '/storage', $image['path']),
                            'error' => 'Failed to read image file'
                        ];
                        continue;
                    }
                    
                    $base64Image = base64_encode($imageData);
                    $mimeType = mime_content_type($image['path']);
                    $dataUri = "data:{$mimeType};base64,{$base64Image}";
                    
                    Log::info('Converted image to base64', [
                        'image' => $image['filename'],
                        'mimeType' => $mimeType,
                        'dataUriLength' => strlen($dataUri)
                    ]);
                    
                    $payload = [
                        'model' => 'gpt-4o',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $detailed 
                                    ? 'You are an AI specialized in providing detailed analysis of data visualizations. For charts and graphs, provide a comprehensive analysis including: (1) A descriptive title, (2) A thorough explanation of what the chart represents, (3) Detailed analysis of the data trends, patterns, and anomalies, (4) At least 5 notable observations, (5) Possible interpretations or implications of the data. If the image is NOT a chart or graph, respond with "no graph detected" only.'
                                    : 'You are an AI specialized only in analyzing charts, graphs, and data visualizations. If the image contains a chart or graph (bar chart, pie chart, line graph, scatter plot, etc.), analyze it and provide a title, summary, and notable trends. If the image is NOT a chart or graph (like a photo, screenshot of text, drawing, diagram without data, etc.), return ONLY "no graph detected" without explanation. Do not attempt to analyze non-chart images.'
                            ],
                            [
                                'role' => 'user',
                                'content' => [
                                    [
                                        'type' => 'text',
                                        'text' => $detailed
                                            ? 'Please provide a detailed analysis of this chart or graph. Include the title, comprehensive explanation, data trends, at least 5 notable observations, and potential implications. If this is not a chart or graph, respond with "no graph detected" only.'
                                            : 'Is this a chart or graph? If yes, analyze it and provide the title, a brief summary, and 3-5 notable trends. If this is NOT a chart or graph, respond with "no graph detected" only.'
                                    ],
                                    [
                                        'type' => 'image_url',
                                        'image_url' => [
                                            'url' => $dataUri
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'temperature' => $detailed ? 0.5 : 0.3
                    ];

                    $response = Http::withHeaders([
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Bearer ' . $apiKey
                    ])
                    ->timeout(60)
                    ->post('https://api.openai.com/v1/chat/completions', $payload);

                    if ($response->failed()) {
                        Log::error('API call failed for image', [
                            'image' => $image['filename'], 
                            'status' => $response->status(),
                            'response' => $response->body()
                        ]);
                        $results[] = [
                            'image' => $image['filename'],
                            'image_url' => str_replace(public_path('storage'), '/storage', $image['path']),
                            'error' => 'API call failed: ' . $response->status() . ' - ' . $response->body()
                        ];
                        continue;
                    }

                    $content = $response->json('choices.0.message.content');
                    
                    if (empty($content) || trim($content) === '' || 
                        stripos($content, 'not a chart') !== false || 
                        stripos($content, 'not a graph') !== false ||
                        stripos($content, 'no chart') !== false ||
                        stripos($content, 'no graph') !== false) {
                        
                        Log::info('Image is not a graph/chart', ['image' => $image['filename']]);
                        $results[] = [
                            'image' => $image['filename'],
                            'image_url' => asset(str_replace(public_path('storage'), '/storage', $image['path'])),
                            'result' => 'no graph detected'
                        ];
                        continue;
                    }
                    
                    $processedContent = $this->processGraphAnalysisContent($content, $detailed);
                    
                    $results[] = [
                        'image' => $image['filename'],
                        'image_url' => asset(str_replace(public_path('storage'), '/storage', $image['path'])),
                        'result' => $processedContent
                    ];
                    
                    OpenAIHelper::calculateAndLogCost($response->json());
                } catch (\Exception $e) {
                    Log::error('Exception processing image', [
                        'image' => $image['filename'],
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $results[] = [
                        'image' => $image['filename'],
                        'image_url' => asset(str_replace(public_path('storage'), '/storage', $image['path'])),
                        'error' => 'Exception: ' . $e->getMessage()
                    ];
                }
            }
            
            if (count($results) === 1) {
                Log::info('Returning single image analysis result');
                return response()->json([
                    'result' => $results[0]['result'] ?? null,
                    'error' => isset($results[0]['error']) ? $results[0]['error'] : null,
                    'image_analyzed' => $results[0]['image'],
                    'image_url' => $results[0]['image_url']
                ]);
            }
            
            Log::info('Returning multiple image analysis results', ['count' => count($results)]);
            return response()->json([
                'analyses' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('General exception in analyze_graph', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to analyze graph: ' . $e->getMessage()], 500);
        }
    }

    private function processGraphAnalysisContent($content, $detailed = false)
    {
        $notGraphKeywords = ['not a chart', 'not a graph', 'is not a', 'no chart', 'no graph', 'cannot analyze', "doesn't contain", 'does not contain'];
        foreach ($notGraphKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                return 'no graph detected';
            }
        }
        
        if ($detailed) {
            $cleanContent = preg_replace('/```(?:json|)\n?|\n?```/', '', $content);
            
            $structuredContent = '';
            
            if (preg_match('/(?:title|chart title|graph title)[:\s]+(.+?)(?:\n|$)/i', $cleanContent, $titleMatches)) {
                $structuredContent .= "TITLE: " . trim($titleMatches[1]) . "\n\n";
            }
            
            if (preg_match('/(?:description|summary|explanation|overview)[:\s]+(.+?)(?=\n\n|\n[A-Z]|$)/is', $cleanContent, $descMatches)) {
                $structuredContent .= "DESCRIPTION: " . trim($descMatches[1]) . "\n\n";
            }
            
            if (preg_match('/(?:analysis|interpretation|data analysis)[:\s]+(.+?)(?=\n\n|\n[A-Z]|$)/is', $cleanContent, $analysisMatches)) {
                $structuredContent .= "ANALYSIS: " . trim($analysisMatches[1]) . "\n\n";
            }
            
            if (preg_match('/(?:trends|observations|findings|notable trends)[:\s]+(.+?)(?=\n\n|\n[A-Z]|$)/is', $cleanContent, $trendsMatches)) {
                $observations = $trendsMatches[1];
                $structuredContent .= "KEY OBSERVATIONS:\n";
                
                if (preg_match_all('/(?:^|\n)(?:\d+\.|\*|-)\s*(.+?)(?=(?:\n(?:\d+\.|\*|-))|$)/s', $observations, $pointMatches)) {
                    foreach ($pointMatches[1] as $point) {
                        $structuredContent .= "- " . trim($point) . "\n";
                    }
                } else {
                    $structuredContent .= $observations . "\n";
                }
                $structuredContent .= "\n";
            }
            
            if (preg_match('/(?:implications|conclusions|insights)[:\s]+(.+?)(?=\n\n|\n[A-Z]|$)/is', $cleanContent, $implicationsMatches)) {
                $structuredContent .= "IMPLICATIONS: " . trim($implicationsMatches[1]) . "\n";
            }
            
            return !empty($structuredContent) ? $structuredContent : $cleanContent;
        }
        
        if (strpos($content, '{') !== false && strpos($content, '}') !== false) {
            try {
                preg_match('/\{.*\}/s', $content, $matches);
                if (!empty($matches[0])) {
                    $jsonData = json_decode($matches[0], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $processedContent = "";
                        
                        if (isset($jsonData['title'])) {
                            $processedContent .= "title: " . $jsonData['title'] . "\n";
                        }
                        
                        if (isset($jsonData['summary'])) {
                            $processedContent .= "summary: " . $jsonData['summary'] . "\n";
                        }
                        
                        if (isset($jsonData['notable_trends']) && is_array($jsonData['notable_trends'])) {
                            $processedContent .= "notable_trends:\n";
                            foreach ($jsonData['notable_trends'] as $trend) {
                                $processedContent .= "- " . $trend . "\n";
                            }
                        }
                        
                        return trim($processedContent);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error processing JSON content', ['error' => $e->getMessage(), 'content' => $content]);
            }
        }

        if (preg_match('/title[:\s]+(.*?)[\r\n]+/i', $content, $titleMatches)) {
            $title = trim($titleMatches[1]);
            $summary = '';
            $trends = [];
            
            if (preg_match('/summary[:\s]+(.*?)(?=notable|trend|\n\n|$)/is', $content, $summaryMatches)) {
                $summary = trim($summaryMatches[1]);
            }
            
            if (preg_match('/(?:notable trends|trends)[:\s]+(.*?)(?=\n\n|$)/is', $content, $trendSection)) {
                $trendText = $trendSection[1];
                if (preg_match_all('/(?:^|\n)\s*(?:-|\*|\d+\.)\s*(.*?)(?=\n\s*(?:-|\*|\d+\.)|$)/s', $trendText, $trendMatches)) {
                    $trends = array_map('trim', $trendMatches[1]);
                }
            }
            
            $processedContent = "title: " . $title . "\n";
            $processedContent .= "summary: " . $summary . "\n";
            $processedContent .= "notable_trends:\n";
            
            foreach ($trends as $trend) {
                $processedContent .= "- " . $trend . "\n";
            }
            
            return trim($processedContent);
        }
        
        if (!empty(trim($content))) {
            $cleanContent = preg_replace('/```(?:json|)\n?|\n?```/', '', $content);
            return trim($cleanContent);
        }
        
        return 'no graph detected';
    }

    public function handleChat(Request $request)
    {   
        set_time_limit(300); // Set the maximum execution time to 300 seconds
        try {
            $request->validate([
                'content' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        \Log::info('OPENAI_API_KEY is ' . env('OPENAI_API_KEY'));
        
        $true = Reviewer::where(['topic_id' => $request->post('topic_id')])->get();
        if(!$true->isEmpty()){
            return response()->json(['success' => true, 'message' => "Reviewer Already Created"]);
        }else{
            try {
                $apiKey = OpenAIHelper::getApiKey();
                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $apiKey
                ])
                ->timeout(300)
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-4o-mini-2024-07-18",
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => "
                                You are an AI that helps generate organized study reviewers. A user has uploaded notes, and your task is to analyze the content  and divide the information accordingly. 
        
                                Here are the rules for processing:
                                1. Identify the main topics in the notes .
                                2. Break the topics content into smaller, logically grouped pieces that fit on individual cards.
                                3. Each piece should focus on a single concept or subtopic, with no piece being longer than 100 words.
                                4. If the notes are unclear, use your best judgment to organize the content logically while keeping it concise and easy to understand.
                                5. If there are term that you dont really know just disregard it and focus only on what you understand. do not create a reviewer in a specific topic if you doesnt know it.
                                6. Use simple and clear language suitable for a reviewer.
        
                                Example format for the output:
                                [
                                    {
                                            Topic, 
                                            Description of the topic.
                                    }
                                ]
        
                                Input notes: " . $request->post('content')
                        ]
                    ],
                    "temperature" => 0.5,
                    "max_tokens" => 4096
                ]);
        
                $responseBody = $response->body();
                $responseData = json_decode($responseBody, true);
                
                if ($response->failed()) {
                    Log::error('Failed API response', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.', 'data' => $responseData]);
                }
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    Log::error('Invalid response format', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.', 'data' => $responseData]);
                }
        
                $rawContent = $responseData['choices'][0]['message']['content'];
                Log::info('Raw API response content', ['content' => $rawContent]);
                
                $content = $this->parseJSONContent($rawContent);
                
                if (empty($content)) {
                    Log::error('Failed to parse content', ['raw_content' => $rawContent]);
                    return response()->json(['success' => false, 'message' => 'Failed to parse response content.', 'raw_content' => $rawContent]);
                }

                foreach($content as $item){
                    if (isset($item['Topic']) && isset($item['Description'])) {
                        $reviewer = new Reviewer;
                        $reviewer->topic_id = $request->post('topic_id');
                        $reviewer->reviewer_about = $item['Topic'];
                        $reviewer->reviewer_text = $item['Description'];
                        $reviewer->save();
                    } else {
                        Log::warning('Skipped an item due to missing keys', ['item' => $item]);
                    }
                }
            
                Log::info('Action : Generate Reviewer');
                OpenAIHelper::calculateAndLogCost($responseData);

                $subscription = Subscription::where('user_id', $request->user()->user_id)
                    ->where('status', 'Active')  // Only increment active subscription
                    ->first();
                if ($subscription) {
                    $subscription->increment('reviewer_created');
                }
                
                
                return response()->json(['success' => true, 'data' => $content, 'topic_id' => $request->post('topic_id')]);
            } catch (\Exception $e) {
                Log::error('Exception in handleChat', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    private function parseJSONContent($rawContent)
    {
        $content = json_decode($rawContent, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($content)) {
            return $content;
        }
        
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/', $rawContent, $matches)) {
            $jsonContent = $matches[1];
            $content = json_decode($jsonContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($content)) {
                return $content;
            }
        }
        
        if (preg_match('/\[\s*{.*}\s*\]/s', $rawContent, $matches)) {
            $jsonContent = $matches[0];
            $content = json_decode($jsonContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($content)) {
                return $content;
            }
        }
        
        Log::warning('Failed to parse JSON, attempting fallback parsing', ['raw_content' => $rawContent]);
        
        try {
            $cleaned = preg_replace('/[\x00-\x1F\x7F]/', '', $rawContent);
            $cleaned = preg_replace('/,\s*}/', '}', $cleaned);
            $cleaned = preg_replace('/,\s*\]/', ']', $cleaned);
            
            if (preg_match('/\[.*\]/s', $cleaned, $matches)) {
                $jsonContent = $matches[0];
                $content = json_decode($jsonContent, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($content)) {
                    return $content;
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception during fallback parsing', ['error' => $e->getMessage()]);
        }
        
        return [];
    }

    public function generate_quiz($topic_id, Request $request)
    {   
        set_time_limit(300); // Set the maximum execution time to 300 seconds

        Log::info('generate_quiz called', ['topic_id' => $topic_id, 'request' => $request->all()]);
    
        $topic = Topic::find($topic_id);
        if (!$topic) {
            Log::error('Topic not found', ['topic_id' => $topic_id]);
            return response()->json(['success' => false, 'message' => 'Topic not found.']);
        }
         
        $quizname = Question::where('topic_id', $topic['topic_id'])->pluck('question_title');
        if(!$quizname->isEmpty()){
            if($quizname[0] === $request->post('name')){
                return response()->json(['success' => false, 'message' => 'Quiz Name Already Take']);
            }
        }
        
        
        $reviewer = Reviewer::where('topic_id', $topic_id)->get(['reviewer_about', 'reviewer_text']);
        if ($reviewer->isEmpty()) {
            Log::error('No reviewer found for this topic', ['topic_id' => $topic_id]);
            return response()->json(['success' => false, 'message' => 'No reviewer found for this topic.']);
        }
        $reviewer = $reviewer->shuffle();
        $text = "";
        foreach($reviewer as $item){
            $text .= $item->reviewer_about . " " . $item->reviewer_text . " | ";

        }
        
        $number = $request->post('number');
        $multiple = $request->post('multiple');
        $true_or_false = $request->post('true_or_false');
        $identification = $request->post('identification');
        $difficulty = $request->post('difficulty', 'easy'); // Default to easy if not specified
        $bloomsLevels = $this->mapDifficultyToBloomsLevels($difficulty);
        $total_quiz = intval($multiple) + intval($true_or_false) + intval($identification);
        if($request->post('type') == 'Multiple Choice'){
            try {
                $apiKey = OpenAIHelper::getApiKey();
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    "Authorization" => "Bearer " . $apiKey
                ])
                ->timeout(300)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates multiple-choice quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate EXACTLY " . $number . " multiple-choice quiz questions that cover the " . $bloomsLevels . " levels of Bloom's Taxonomy. Return EXACTLY " . $number . " questions, no more, no less.

                        1. KNOWLEDGE: Questions that assess recall of facts, terms, concepts, or basic information.
                        2. COMPREHENSION: Questions that test understanding of the material.
                        3. APPLICATION: Questions that require applying knowledge to new situations.
                        4. ANALYSIS: Questions that ask students to break down information and show relationships.
                        5. SYNTHESIS: Questions that require combining ideas to create something new.
                        6. EVALUATION: Questions that ask for judgments based on criteria.
                
                        Guidelines:
                        - The difficulty level requested is '" . $difficulty . "', so focus on creating questions at the " . $bloomsLevels . " levels.
                        - Each question must have four options labeled A, B, C, and D.
                        - Only one option should be correct.
                        - IMPORTANT: Distribute the correct answers evenly across options A, B, C, and D. Do not bias toward any particular option.
                        - Ensure approximately 25% of answers are A, 25% are B, 25% are C, and 25% are D.
                        - For higher cognitive levels (Analysis, Synthesis, Evaluation), ensure questions require critical thinking.
                        - The order of the questions should be mixed to provide variety.
                        - Indicate which Bloom's level each question addresses in the JSON response.
                        - IMPORTANT: Count your questions to ensure you provide EXACTLY " . $number . " questions.
                
                        Format your response in JSON like this: 
                
                        {
                        \"questions\": [
                            {
                            \"blooms_level\": \"Knowledge\",
                            \"question\": \"What is the definition of X?\",
                            \"choices\": {
                                \"A\": \"choice\",
                                \"B\": \"choice\",
                                \"C\": \"choice\",
                                \"D\": \"choice\"
                            },
                            \"correct_answer\": \"C\"
                            }
                        ]
                        } 
                
                        Text: " . $text ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4096
                ]);
        
                if ($response->failed()) {
                    Log::error('Failed to communicate with OpenAI API', ['response' => $response->body()]);
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
                }
        
                $responseData = json_decode($response->body(), true);
                Log::info('OpenAI API Response:', ['response' => $responseData]);
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.', 'data' => $responseData]);
                }
                
                $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");

                $content = json_decode($jsonContent, true);
                Log::info('Parsed Content:', ['content' => $content]);
                Log::info('Json to be Decoded:', ['json' => $responseData['choices'][0]['message']['content']]);
        
                if (empty($content['questions'])) {
                    Log::error('No questions generated', ['content' => $content, 'reviewerText' => $text]);
                    return response()->json(['success' => false, 'message' => 'No questions generated.', 'data' => $content, 'raw' => $text , 'response' => $responseData]);
                }
                
                // Ensure we only use the exact number of questions requested
                if (count($content['questions']) > $number) {
                    $content['questions'] = array_slice($content['questions'], 0, $number);
                    Log::info('Trimmed questions to match requested number', ['requested' => $number, 'received' => count($content['questions'])]);
                }
                
                $question = Question::create([
                    'topic_id' => $topic_id,
                    'question_type' => $request->post('type'),
                    'question_title' => $request->post('name'),
                    'number_of_question' => $request->post('number'),
                ]);
        
                Log::info('Created Question:', ['question' => $question]);
        
                $quizController = new QuizController();
                // Pass only the questions array, not the entire content
                $quizController->storeMultipleChoiceQuestions($question->question_id, ['questions' => $content['questions']]);
                
                Log::info('Action : Generate Quiz Multiple Choice');
                OpenAIHelper::calculateAndLogCost($responseData);

                $quiz = Subscription::where('user_id', $request->user()->user_id)
                    ->where('status', 'Active')  // Only increment active subscription
                    ->first();
                if ($quiz) {
                    $quiz->increment('quiz_created');
                }
                return response()->json(['success' => true, 'data' => $content]);
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }else if($request->post('type') == 'True or false'){

            try {
                $apiKey = OpenAIHelper::getApiKey();
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    "Authorization" => "Bearer " . $apiKey
                ])
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates true or false quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " true or false quiz questions that cover the " . $bloomsLevels . " levels of Bloom's Taxonomy:

                        1. KNOWLEDGE: Questions that assess recall of facts, terms, concepts, or basic information.
                        2. COMPREHENSION: Questions that test understanding of the material.
                        3. APPLICATION: Questions that require applying knowledge to new situations.
                        4. ANALYSIS: Questions that ask students to break down information and show relationships.
                        5. SYNTHESIS: Questions that require combining ideas to create something new.
                        6. EVALUATION: Questions that ask for judgments based on criteria.

                        Guidelines:
                        - The difficulty level requested is '" . $difficulty . "', so focus on creating questions at the " . $bloomsLevels . " levels.
                        - Ensure statements align with the appropriate cognitive level in Bloom's Taxonomy.
                        - Some statements should be straightforward (Knowledge, Comprehension) while others should require deeper thinking (Analysis, Synthesis, Evaluation) based on the requested difficulty.
                        - The order of the questions must be mixed to provide variety in cognitive challenge.
                        - Indicate which Bloom's level each question addresses in the JSON response.

                        Format your response in JSON like this: 

                        {
                        \"questions\": [
                            {
                            \"blooms_level\": \"Analysis\",
                            \"question\": \"Based on the principles discussed, X can be classified as a direct cause of Y. True or False?\",
                            \"correct_answer\": \"True\"
                            }
                        ]
                        } 

                        Text: " . $text ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4096
                ]);
        
                if ($response->failed()) {
                    Log::error('Failed to communicate with OpenAI API', ['response' => $response->body()]);
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
                }
        
                $responseData = json_decode($response->body(), true);
                Log::info('OpenAI API Response:', ['response' => $responseData]);
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.']);
                }
                
                $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");

                $content = json_decode($jsonContent, true);
                Log::info('Parsed Content:', ['content' => $content]);
        
                if (empty($content['questions'])) {
                    Log::error('No questions generated', ['content' => $content, 'reviewerText' => $text]);
                    return response()->json(['success' => false, 'message' => 'No questions generated.', 'data' => $content, 'raw' => $text]);
                }
                
                $question = Question::create([
                    'topic_id' => $topic_id,
                    'question_type' => $request->post('type'),
                    'question_title' => $request->post('name'),
                    'number_of_question' => $request->post('number'),
                ]);
        
                Log::info('Created Question:', ['question' => $question]);
        
                foreach ($content['questions'] as $questionData) {
                    true_or_false::create([
                        'question_id' => $question->question_id,
                        'question_text' => $questionData['question'],
                        'answer' => $questionData['correct_answer'],
                        'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                    ]);
                }
                
                Log::info('Action : Generate Quiz True or false');
                OpenAIHelper::calculateAndLogCost($responseData);

                $quiz = Subscription::where('user_id', $request->user()->user_id)
                ->where('status', 'Active')  // Only increment active subscription
                ->first();
            if ($quiz) {
                $quiz->increment('quiz_created');
            }

                return response()->json(['success' => true, 'data' => $content]);
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }            
        }else if($request->post('type') == 'Identification'){

            try {
                $apiKey = OpenAIHelper::getApiKey();
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    "Authorization" => "Bearer " . $apiKey
                ])
                ->timeout(300)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates identification quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " identification quiz questions that cover the " . $bloomsLevels . " levels of Bloom's Taxonomy:

                        1. KNOWLEDGE: Questions that assess recall of facts, terms, concepts, or basic information.
                        2. COMPREHENSION: Questions that test understanding of the material.
                        3. APPLICATION: Questions that require applying knowledge to new situations.
                        4. ANALYSIS: Questions that ask students to break down information and show relationships.
                        5. SYNTHESIS: Questions that require combining ideas to create something new.
                        6. EVALUATION: Questions that ask for judgments based on criteria.
                
                        Guidelines:
                        - The difficulty level requested is '" . $difficulty . "', so focus on creating questions at the " . $bloomsLevels . " levels.
                        - Ensure questions reflect the appropriate cognitive level in Bloom's Taxonomy.
                        - The answer must be a **single word or a short phrase**.
                        - Include questions that match the requested difficulty level.
                        - The order of the questions must be mixed to provide variety in cognitive challenge.
                        - Indicate which Bloom's level each question addresses in the JSON response.
                
                        Format your response in JSON like this: 
                
                        {
                        \"questions\": [
                            {
                            \"blooms_level\": \"Application\",
                            \"question\": \"What process would you use to solve this specific problem based on the principles discussed?\",
                            \"correct_answer\": \"Correct Answer\"
                            }
                        ]
                        } 
                
                        Text: " . $text ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4096
                ]);
        
                if ($response->failed()) {
                    Log::error('Failed to communicate with OpenAI API', ['response' => $response->body()]);
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
                }
        
                $responseData = json_decode($response->body(), true);
                Log::info('OpenAI API Response:', ['response' => $responseData]);
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.']);
                }
        
                $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");

                $content = json_decode($jsonContent, true);
                Log::info('Parsed Content:', ['content' => $content]);
        
                if (empty($content['questions'])) {
                    Log::error('No questions generated', ['content' => $content, 'reviewerText' => $text]);
                    return response()->json(['success' => false, 'message' => 'No questions generated.', 'data' => $content, 'raw' => $text]);
                }
                
                $question = Question::create([
                    'topic_id' => $topic_id,
                    'question_type' => $request->post('type'),
                    'question_title' => $request->post('name'),
                    'number_of_question' => $request->post('number'),
                ]);
        
                Log::info('Action : Generate Quiz Identification');
                Log::info('Created Question:', ['question' => $question]);
        
                foreach ($content['questions'] as $questionData) {
                    Identification::create([
                        'question_id' => $question->question_id,
                        'question_text' => $questionData['question'],
                        'answer' => $questionData['correct_answer'],
                        'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                    ]);
                }
                
                OpenAIHelper::calculateAndLogCost($responseData);
                
                $quiz = Subscription::where('user_id', $request->user()->user_id)
                ->where('status', 'Active')  // Only increment active subscription
                ->first();
            if ($quiz) {
                $quiz->increment('quiz_created');
            }

                return response()->json(['success' => true, 'data' => $content]);
                
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }            
        }else if($request->post('type') == 'Mixed'){
            $prompt = "Based on the following text, generate ";
            $quizTypes = [];
            $jsonFormat = "{\n";
            
            if ($multiple > 0) {
                $quizTypes[] = "$multiple multiple-choice quiz questions that focus on the " . $bloomsLevels . " levels of Bloom's Taxonomy";
                $jsonFormat .= "    \"multiple_choice\": [\n        {\n            \"blooms_level\": \"Analysis\",\n            \"question\": \"Which of the following best explains the relationship between X and Y?\",\n            \"choices\": {\n                \"A\": \"Choice 1\",\n                \"B\": \"Choice 2\",\n                \"C\": \"Choice 3\",\n                \"D\": \"Choice 4\"\n            },\n            \"correct_answer\": \"B\"\n        }\n    ],\n";
            }
            
            if ($true_or_false > 0) {
                $quizTypes[] = "$true_or_false true or false quiz questions that focus on the " . $bloomsLevels . " levels of Bloom's Taxonomy";
                $jsonFormat .= "    \"true_or_false\": [\n        {\n            \"blooms_level\": \"Evaluation\",\n            \"question\": \"Given the evidence presented, the conclusion that X leads to Y is valid. True or False?\",\n            \"correct_answer\": \"True\"\n        }\n    ],\n";
            }
            
            if ($identification > 0) {
                $quizTypes[] = "$identification identification quiz questions that focus on the " . $bloomsLevels . " levels of Bloom's Taxonomy";
                $jsonFormat .= "    \"identification\": [\n        {\n            \"blooms_level\": \"Synthesis\",\n            \"question\": \"What term describes the process of combining concepts A and B to create a new solution?\",\n            \"correct_answer\": \"Correct Answer\"\n        }\n    ],\n";
            }
            
            $bloomsInstructions = "\n\nInclude questions from these cognitive levels of Bloom's Taxonomy:\n
            1. KNOWLEDGE: Questions that assess recall of facts, terms, concepts, or basic information.
            2. COMPREHENSION: Questions that test understanding of the material.
            3. APPLICATION: Questions that require applying knowledge to new situations.
            4. ANALYSIS: Questions that ask students to break down information and show relationships.
            5. SYNTHESIS: Questions that require combining ideas to create something new.
            6. EVALUATION: Questions that ask for judgments based on criteria.\n
            
            Guidelines:
            - The difficulty level requested is '" . $difficulty . "', so focus on creating questions at the " . $bloomsLevels . " levels.
            - Ensure each question type includes questions appropriate for the requested difficulty.
            - For multiple-choice questions, distribute the correct answers evenly across options A, B, C, and D. Do not bias toward any particular option.
            - Aim for approximately 25% of answers being A, 25% B, 25% C, and 25% D.
            - For higher levels (Analysis, Synthesis, Evaluation), the questions should require critical thinking rather than simple recall.
            - The order of the questions must be mixed to provide variety in cognitive challenge.\n";
            
            $prompt .= implode(", ", $quizTypes) . ". Format your response in JSON like this: \n\n" . rtrim($jsonFormat, ",\n") . "\n}" . $bloomsInstructions . "\nText: " . $text;
            
            try {
                $apiKey = OpenAIHelper::getApiKey();
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    "Authorization" => "Bearer " . $apiKey
                ])
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4096
                ]);

                if ($response->failed()) {
                    Log::error('Failed to communicate with OpenAI API', ['response' => $response->body()]);
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
                }

                $responseData = json_decode($response->body(), true);
                Log::info('OpenAI API Response:', ['response' => $responseData]);

                if (!isset($responseData['choices'][0]['message']['content'])) {
                    Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.']);
                }

                $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");

                $content = json_decode($jsonContent, true);
                Log::info('Parsed Content:', ['content' => $content]);

                if (empty($content['multiple_choice']) && empty($content['true_or_false']) && empty($content['identification'])) {
                    Log::error('No questions generated', ['content' => $content, 'reviewerText' => $text]);
                    return response()->json(['success' => false, 'message' => 'No questions generated.', 'data' => $content, 'raw' => $text]);
                }

                $question = Question::create([
                    'topic_id' => $topic_id,
                    'question_type' => $request->post('type'),
                    'question_title' => $request->post('name'),
                    'number_of_question' => $total_quiz,
                    'metadata' => json_encode([
                        'multiple' => intval($multiple),
                        'true_or_false' => intval($true_or_false),
                        'identification' => intval($identification)
                    ])
                ]);

                Log::info('Created Mixed Question with metadata:', [
                    'question' => $question,
                    'multiple' => $multiple,
                    'true_or_false' => $true_or_false,
                    'identification' => $identification
                ]);

                if (!empty($content['multiple_choice'])) {
                    // Ensure we only use the exact number of questions requested
                    if (count($content['multiple_choice']) > $multiple) {
                        $content['multiple_choice'] = array_slice($content['multiple_choice'], 0, $multiple);
                        Log::info('Trimmed multiple choice questions to match requested number', 
                            ['requested' => $multiple, 'received' => count($content['multiple_choice'])]);
                    }
                    
                    $quizController = new QuizController();
                    // Pass only the multiple_choice part to avoid including other question types
                    $quizController->storeMultipleChoiceQuestions($question->question_id, 
                        ['questions' => $content['multiple_choice']]);
                }

                if (!empty($content['true_or_false'])) {
                    // Ensure we only use the exact number of questions requested
                    if (count($content['true_or_false']) > $true_or_false) {
                        $content['true_or_false'] = array_slice($content['true_or_false'], 0, $true_or_false);
                        Log::info('Trimmed true/false questions to match requested number',
                            ['requested' => $true_or_false, 'received' => count($content['true_or_false'])]);
                    }
                    
                    foreach ($content['true_or_false'] as $questionData) {
                        true_or_false::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                    }
                }

                if (!empty($content['identification'])) {
                    // Ensure we only use the exact number of questions requested
                    if (count($content['identification']) > $identification) {
                        $content['identification'] = array_slice($content['identification'], 0, $identification);
                        Log::info('Trimmed identification questions to match requested number',
                            ['requested' => $identification, 'received' => count($content['identification'])]);
                    }
                    
                    foreach ($content['identification'] as $questionData) {
                        Identification::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                    }
                }

                Log::info('Action : Generate Quiz Mixed');
                OpenAIHelper::calculateAndLogCost($responseData);

                $quiz = Subscription::where('user_id', $request->user()->user_id)
                ->where('status', 'Active')  // Only increment active subscription
                ->first();
            if ($quiz) {
                $quiz->increment('quiz_created');
            }
                
                return response()->json(['success' => true, 'data' => $content]);

            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }       
        }else{
            return response()->json(['success' => false, 'message' => "Unidentified Question Type"]);
        }
    }

    private function mapDifficultyToBloomsLevels($difficulty)
    {
        switch(strtolower($difficulty)) {
            case 'easy':
                return 'Knowledge and Comprehension';
            case 'medium':
                return 'Application and Analysis';
            case 'hard':
                return 'Synthesis and Evaluation';
            default:
                return 'Knowledge and Comprehension';
        }
    }

    public function generateAdditionalQuestions($topicId, $count, $type)
    {
        Log::info("Generating {$count} additional {$type} questions for topic {$topicId}");
        
        $reviewer = Reviewer::where('topic_id', $topicId)->get(['reviewer_about', 'reviewer_text']);
        if ($reviewer->isEmpty()) {
            Log::error('No reviewer content found for this topic', ['topic_id' => $topicId]);
            return [];
        }
        
        $text = "";
        foreach ($reviewer as $item) {
            $text .= $item->reviewer_about . " " . $item->reviewer_text . " | ";
        }
        
        $difficulty = 'medium';
        $bloomsLevels = $this->mapDifficultyToBloomsLevels($difficulty);
        
        switch ($type) {
            case 'Multiple Choice':
                return $this->generateAdditionalMultipleChoice($count, $bloomsLevels, $text);
            case 'True or false':
                return $this->generateAdditionalTrueFalse($count, $bloomsLevels, $text);
            case 'Identification':
                return $this->generateAdditionalIdentification($count, $bloomsLevels, $text);
            default:
                Log::error('Unsupported question type', ['type' => $type]);
                return [];
        }
    }
    
    private function generateAdditionalMultipleChoice($count, $bloomsLevels, $text)
    {
        try {
            $apiKey = OpenAIHelper::getApiKey();
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer " . $apiKey
            ])
            ->timeout(300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini-2024-07-18',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI that generates multiple-choice quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                    ['role' => 'user', 'content' => "Based on the following text, generate EXACTLY {$count} multiple-choice quiz questions that cover the {$bloomsLevels} levels of Bloom's Taxonomy. I need EXACTLY {$count} questions, no more, no less.

                    Guidelines:
                    - Each question must have four options labeled A, B, C, and D.
                    - Only one option should be correct.
                    - Distribute the correct answers evenly across options A, B, C, and D.
                    - For higher cognitive levels (Analysis, Synthesis, Evaluation), ensure questions require critical thinking.
                    - Indicate which Bloom's level each question addresses.
                    - IMPORTANT: Count your questions before returning to ensure there are EXACTLY {$count} questions.
            
                    Format your response in JSON like this: 
            
                    {
                    \"questions\": [
                        {
                        \"blooms_level\": \"Knowledge\",
                        \"question\": \"What is the definition of X?\",
                        \"choices\": {
                            \"A\": \"choice\",
                            \"B\": \"choice\",
                            \"C\": \"choice\",
                            \"D\": \"choice\"
                        },
                        \"correct_answer\": \"C\"
                        }
                    ]
                    } 
            
                    Text: {$text}"]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4096
            ]);
            
            if ($response->failed()) {
                Log::error('Failed to generate additional multiple-choice questions', ['response' => $response->body()]);
                return [];
            }
            
            $responseData = json_decode($response->body(), true);
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                return [];
            }
            
            $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");
            $jsonContent = preg_replace('/^```json\s*|\s*```$/s', '', $jsonContent);
            
            $content = json_decode($jsonContent, true);
            
            OpenAIHelper::calculateAndLogCost($responseData);
            
            if (empty($content['questions'])) {
                Log::error('No questions generated in additional request', ['content' => $content]);
                return [];
            }
            
            return $content['questions'];
            
        } catch (\Exception $e) {
            Log::error('Exception generating additional multiple-choice questions', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    private function generateAdditionalTrueFalse($count, $bloomsLevels, $text)
    {
        try {
            $apiKey = OpenAIHelper::getApiKey();
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer " . $apiKey
            ])
            ->timeout(300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini-2024-07-18',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI that generates true or false quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                    ['role' => 'user', 'content' => "Based on the following text, generate EXACTLY {$count} true or false quiz questions that cover the {$bloomsLevels} levels of Bloom's Taxonomy. I need EXACTLY {$count} questions, no more, no less.

                    Guidelines:
                    - Ensure statements align with the appropriate cognitive level in Bloom's Taxonomy.
                    - Some statements should be straightforward while others should require deeper thinking.
                    - Balance the number of true and false statements.
                    - Indicate which Bloom's level each question addresses.
                    - IMPORTANT: Count your questions before returning to ensure there are EXACTLY {$count} questions.
            
                    Format your response in JSON like this: 
            
                    {
                    \"questions\": [
                        {
                        \"blooms_level\": \"Analysis\",
                        \"question\": \"Based on the principles discussed, X can be classified as a direct cause of Y. True or False?\",
                        \"correct_answer\": \"True\"
                        }
                    ]
                    } 
            
                    Text: {$text}"]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4096
            ]);
            
            if ($response->failed()) {
                Log::error('Failed to generate additional true/false questions', ['response' => $response->body()]);
                return [];
            }
            
            $responseData = json_decode($response->body(), true);
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                return [];
            }
            
            $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");
            $jsonContent = preg_replace('/^```json\s*|\s*```$/s', '', $jsonContent);
            
            $content = json_decode($jsonContent, true);
            
            OpenAIHelper::calculateAndLogCost($responseData);
            
            if (empty($content['questions'])) {
                Log::error('No questions generated in additional request', ['content' => $content]);
                return [];
            }
            
            return $content['questions'];
            
        } catch (\Exception $e) {
            Log::error('Exception generating additional true/false questions', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    private function generateAdditionalIdentification($count, $bloomsLevels, $text)
    {
        try {
            $apiKey = OpenAIHelper::getApiKey();
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer " . $apiKey
            ])
            ->timeout(300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini-2024-07-18',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI that generates identification quiz questions based on Bloom\'s Taxonomy to assess various cognitive levels. Return the response in JSON format.'],
                    ['role' => 'user', 'content' => "Based on the following text, generate EXACTLY {$count} identification quiz questions that cover the {$bloomsLevels} levels of Bloom's Taxonomy. I need EXACTLY {$count} questions, no more, no less.

                    Guidelines:
                    - Ensure questions reflect the appropriate cognitive level in Bloom's Taxonomy.
                    - The answer must be a single word or a short phrase.
                    - Include questions that match the medium difficulty level.
                    - Indicate which Bloom's level each question addresses.
                    - IMPORTANT: Count your questions before returning to ensure there are EXACTLY {$count} questions.
            
                    Format your response in JSON like this: 
            
                    {
                    \"questions\": [
                        {
                        \"blooms_level\": \"Application\",
                        \"question\": \"What process would you use to solve this specific problem based on the principles discussed?\",
                        \"correct_answer\": \"Correct Answer\"
                        }
                    ]
                    } 
            
                    Text: {$text}"]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4096
            ]);
            
            if ($response->failed()) {
                Log::error('Failed to generate additional identification questions', ['response' => $response->body()]);
                return [];
            }
            
            $responseData = json_decode($response->body(), true);
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Invalid response format from OpenAI API', ['response' => $responseData]);
                return [];
            }
            
            $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");
            $jsonContent = preg_replace('/^```json\s*|\s*```$/s', '', $jsonContent);
            
            $content = json_decode($jsonContent, true);
            
            OpenAIHelper::calculateAndLogCost($responseData);
            
            if (empty($content['questions'])) {
                Log::error('No questions generated in additional request', ['content' => $content]);
                return [];
            }
            
            return $content['questions'];
            
        } catch (\Exception $e) {
            Log::error('Exception generating additional identification questions', ['error' => $e->getMessage()]);
            return [];
        }
    }
}