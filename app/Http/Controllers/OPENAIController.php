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
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.', 'data' => $responseData]);
                }
        
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.', 'data' => $responseData]);
                }
        
                $content = json_decode($responseData['choices'][0]['message']['content'], true);
                
    
                foreach($content as $item){
                    $reviewer = new Reviewer;
                    $reviewer->topic_id = $request->post('topic_id');
                    $reviewer->reviewer_about = $item['Topic'];
                    $reviewer->reviewer_text = $item['Description'];
                    $reviewer->save();
                }
            
                // Calculate and log the cost using the helper function
                Log::info('Action : Generate Reviewer');
                OpenAIHelper::calculateAndLogCost($responseData);

                // Update the reviewer count in the subscription
                $subscription = Subscription::where('user_id', $request->user()->user_id)->first();
                if ($subscription) {
                    $subscription->increment('reviewer_created');
                }
                
                return response()->json(['success' => true, 'data' => $content, 'topic_id' => $request->post('topic_id')]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
    }
    public function generate_quiz($topic_id, Request $request)
    {   
        // return response()->json(['success' => false, 'Request' => $request->post()]);
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
        
        
        // Retrieve reviewer text and check if it exists
        $reviewer = Reviewer::where('topic_id', $topic_id)->get(['reviewer_about', 'reviewer_text']);
        if ($reviewer->isEmpty()) {
            Log::error('No reviewer found for this topic', ['topic_id' => $topic_id]);
            return response()->json(['success' => false, 'message' => 'No reviewer found for this topic.']);
        }
        // Shuffle the collection to randomize the order
        $reviewer = $reviewer->shuffle();
        // Combine the reviewer text into a single string
        $text = "";
        foreach($reviewer as $item){
            $text .= $item->reviewer_about . " " . $item->reviewer_text . " | ";

        }
        // return response()->json(['success' => false, 'message' => $text ]);
        
        
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
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " multiple-choice quiz questions that cover the " . $bloomsLevels . " levels of Bloom's Taxonomy:

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
                
                // Strip the code block formatting
                $jsonContent = trim($responseData['choices'][0]['message']['content'], "```json\n");

                $content = json_decode($jsonContent, true);
                Log::info('Parsed Content:', ['content' => $content]);
                Log::info('Json to be Decoded:', ['json' => $responseData['choices'][0]['message']['content']]);
        
                if (empty($content['questions'])) {
                    Log::error('No questions generated', ['content' => $content, 'reviewerText' => $text]);
                    return response()->json(['success' => false, 'message' => 'No questions generated.', 'data' => $content, 'raw' => $text , 'response' => $responseData]);
                }
                
                $question = Question::create([
                    'topic_id' => $topic_id,
                    'question_type' => $request->post('type'),
                    'question_title' => $request->post('name'),
                    'number_of_question' => $request->post('number'), // Assuming each question is a single question
                ]);
        
                // Log the created question to check if the id is set
                Log::info('Created Question:', ['question' => $question]);
        
                // Use QuizController to store and balance the questions
                $quizController = new QuizController();
                $quizController->storeMultipleChoiceQuestions($question->question_id, $content);
                
                // Calculate and log the cost using the helper function
                Log::info('Action : Generate Quiz Multiple Choice');
                OpenAIHelper::calculateAndLogCost($responseData);

                // Update the Quiz count in the subscription
                $quiz = Subscription::where('user_id', $request->user()->user_id)->first();
                if ($quiz) {
                    $quiz->increment('quiz_created');
                }
        
                return response()->json(['success' => true, 'data' => $content]);
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
//===============================================================================================================================
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
                
                // Strip the code block formatting
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
                    'number_of_question' => $request->post('number'), // Assuming each question is a single question
                ]);
        
                // Log the created question to check if the id is set
                Log::info('Created Question:', ['question' => $question]);
        
                // Save the questions and multiple choices
                foreach ($content['questions'] as $questionData) {
                    true_or_false::create([
                        'question_id' => $question->question_id,
                        'question_text' => $questionData['question'],
                        'answer' => $questionData['correct_answer'],
                        'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                    ]);
                }
                
                // Calculate and log the cost using the helper function
                Log::info('Action : Generate Quiz True or false');
                OpenAIHelper::calculateAndLogCost($responseData);

                // Update the Quiz count in the subscription
                $quiz = Subscription::where('user_id', $request->user()->user_id)->first();
                if ($quiz) {
                    $quiz->increment('quiz_created');
                }

                return response()->json(['success' => true, 'data' => $content]);
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }            
//===============================================================================================================================
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
        
                // Strip the code block formatting
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
                    'number_of_question' => $request->post('number'), // Assuming each question is a single question
                ]);
        
                // Log the created question to check if the id is set
                Log::info('Action : Generate Quiz Identification');
                Log::info('Created Question:', ['question' => $question]);
        
                // Save the questions and multiple choices
                foreach ($content['questions'] as $questionData) {
                    Identification::create([
                        'question_id' => $question->question_id,
                        'question_text' => $questionData['question'],
                        'answer' => $questionData['correct_answer'],
                        'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                    ]);
                }
                
                // Calculate and log the cost using the helper function
                OpenAIHelper::calculateAndLogCost($responseData);
                
                // Update the Quiz count in the subscription
                $quiz = Subscription::where('user_id', $request->user()->user_id)->first();
                if ($quiz) {
                    $quiz->increment('quiz_created');
                }

                return response()->json(['success' => true, 'data' => $content]);
                
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }            
//===============================================================================================================================
        }else if($request->post('type') == 'Mixed'){
            // Build the prompt dynamically
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

                // Strip the code block formatting
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
                ]);

                Log::info('Created Question:', ['question' => $question]);

                // Use QuizController to store and balance the multiple choice questions
                if (!empty($content['multiple_choice'])) {
                    $quizController = new QuizController();
                    $quizController->storeMultipleChoiceQuestions($question->question_id, $content);
                }

                // Store true/false and identification questions directly
                if (!empty($content['true_or_false'])) {
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
                    foreach ($content['identification'] as $questionData) {
                        Identification::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                    }
                }

                // Calculate and log the cost using the helper function
                Log::info('Action : Generate Quiz Mixed');
                OpenAIHelper::calculateAndLogCost($responseData);

                $quiz = Subscription::where('user_id', $request->user()->user_id)->first();
                if ($quiz) {
                    $quiz->increment('quiz_created');
                }
                
                return response()->json(['success' => true, 'data' => $content]);

            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }       
            
//===============================================================================================================================
        }else{
            return response()->json(['success' => false, 'message' => "Unidentified Question Type"]);
        }
    }

    /**
     * Map difficulty level to Bloom's taxonomy levels
     * 
     * @param string $difficulty The difficulty level (easy, medium, hard)
     * @return string The corresponding Bloom's taxonomy levels
     */
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
                return 'Knowledge and Comprehension'; // Default to easy
        }
    }
}