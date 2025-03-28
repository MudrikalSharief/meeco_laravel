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


        \Log::info('OPENAI_API_KEY: ' . env('OPENAI_API_KEY'));

        $true = Reviewer::where(['topic_id' => $request->post('topic_id')])->get();
        if(!$true->isEmpty()){
            return response()->json(['success' => true, 'message' => "Reviewer Already Created"]);
        }else{

            try {
                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . env('OPENAI_API_KEY')
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
        $total_quiz = intval($multiple) + intval($true_or_false) + intval($identification);
        if($request->post('type') == 'Multiple Choice'){
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(300)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates multiple-choice quiz questions designed to assess analytical thinking. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " multiple-choice quiz questions that require analysis. Each question must encourage critical thinking by asking the user to compare, categorize, identify relationships, recognize patterns, or evaluate cause and effect. 
                
                        Guidelines:
                        - Each question must have four options labeled A, B, C, and D.
                        - Only one option should be correct.
                        - Ensure that the questions challenge the user to analyze the information, not just recall facts.
                        - The order of the questions must be rearranged so they do not follow the same sequence as the text.
                
                        Format your response in JSON like this: 
                
                        {
                        \"questions\": [
                            {
                            \"question\": \"Which of the following best explains the relationship between X and Y?\",
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
        
                // Save the questions and multiple choices
                foreach ($content['questions'] as $questionData) {
                    multiple_choice::create([
                        'question_id' => $question->question_id,
                        'question_text' => $questionData['question'],
                        'answer' => $questionData['correct_answer'],
                        'A' => $questionData['choices']['A'],
                        'B' => $questionData['choices']['B'],
                        'C' => $questionData['choices']['C'],
                        'D' => $questionData['choices']['D'],
                    ]);
                }

                // Calculate and log the cost using the helper function
                Log::info('Action : Generate Quiz Multple Choice');
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
                $response = Http::withHeaders([
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates true or false quiz questions designed to assess analytical thinking. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " true or false quiz questions that require analysis. Each question must challenge the user to evaluate relationships, identify patterns, assess cause and effect, or detect logical inconsistencies.

                        Guidelines:
                        - Ensure that the statements require analysis rather than simple recall.
                        - Some statements should contain subtle logical twists or require recognizing underlying principles.
                        - The order of the questions must be rearranged so they do not follow the same sequence as the text.

                        Format your response in JSON like this: 

                        {
                        \"questions\": [
                            {
                            \"question\": \"Analyzing X and Y, it can be concluded that Z is a direct result. True or False?\",
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
                $response = Http::withHeaders([
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(300)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates identification quiz questions designed to assess analytical thinking. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate " . $number . " identification quiz questions that require analysis. Each question must challenge the user to recognize relationships, identify causes, classify concepts, or draw conclusions based on the given text.
                
                        Guidelines:
                        - The question must require critical thinking rather than simple recall.
                        - Ensure that the answer is a **single word or a short phrase**.
                        - The order of the questions must be rearranged so they do not follow the same sequence as the text.
                
                        Format your response in JSON like this: 
                
                        {
                        \"questions\": [
                            {
                            \"question\": \"What process involves the breakdown of complex molecules into simpler ones to release energy?\",
                            \"correct_answer\": \"Catabolism\"
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
                $quizTypes[] = "$multiple multiple-choice quiz questions that assess analytical thinking";
                $jsonFormat .= "    \"multiple_choice\": [\n        {\n            \"question\": \"Which of the following best explains the relationship between X and Y?\",\n            \"choices\": {\n                \"A\": \"Choice 1\",\n                \"B\": \"Choice 2\",\n                \"C\": \"Choice 3\",\n                \"D\": \"Choice 4\"\n            },\n            \"correct_answer\": \"B\"\n        }\n    ],\n";
            }
            
            if ($true_or_false > 0) {
                $quizTypes[] = "$true_or_false true or false quiz questions that require analysis";
                $jsonFormat .= "    \"true_or_false\": [\n        {\n            \"question\": \"Given the cause-and-effect relationship between X and Y, does Z logically follow? True or False?\",\n            \"correct_answer\": \"True\"\n        }\n    ],\n";
            }
            
            if ($identification > 0) {
                $quizTypes[] = "$identification identification quiz questions that assess understanding of relationships and classification";
                $jsonFormat .= "    \"identification\": [\n        {\n            \"question\": \"What principle explains the connection between A and B?\",\n            \"correct_answer\": \"Correct Answer\"\n        }\n    ],\n";
            }
            
            $prompt .= implode(", ", $quizTypes) . ". Format your response in JSON like this: \n\n" . rtrim($jsonFormat, ",\n") . "\n}\n\nText: " . $text . " The order of the questions must not be the same as the order of the text I gave you. Rearrange the order of the questions to promote deeper understanding.";
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates quiz questions to test analytical thinking at Level 4 of Bloom\'s Taxonomy. Return the response in JSON format.'],
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

                if (!empty($content['multiple_choice'])) {
                    foreach ($content['multiple_choice'] as $questionData) {
                        multiple_choice::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'A' => $questionData['choices']['A'],
                            'B' => $questionData['choices']['B'],
                            'C' => $questionData['choices']['C'],
                            'D' => $questionData['choices']['D'],
                        ]);
                    }
                }

                if (!empty($content['true_or_false'])) {
                    foreach ($content['true_or_false'] as $questionData) {
                        true_or_false::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                        ]);
                    }
                }

                if (!empty($content['identification'])) {
                    foreach ($content['identification'] as $questionData) {
                        Identification::create([
                            'question_id' => $question->question_id,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
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
}