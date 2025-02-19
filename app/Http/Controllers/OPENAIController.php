<?php

namespace App\Http\Controllers;

use App\Models\multiple_choice;
use App\Models\Question;
use App\Models\Reviewer;
use App\Models\Topic;

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
                    "model" => "gpt-4-turbo",
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
                                5. Use simple and clear language suitable for a reviewer.
        
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
        
                if ($response->failed()) {
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
                }
        
                $responseBody = $response->body();
                $responseData = json_decode($responseBody, true);
        
                if (!isset($responseData['choices'][0]['message']['content'])) {
                    return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.']);
                }
        
                $content = json_decode($responseData['choices'][0]['message']['content'], true);
                
    
                foreach($content as $item){
                    $reviewer = new Reviewer;
                    $reviewer->topic_id = $request->post('topic_id');
                    $reviewer->reviewer_about = $item['Topic'];
                    $reviewer->reviewer_text = $item['Description'];
                    $reviewer->save();
                }
                
    
                return response()->json(['success' => true, 'data' => $content, 'topic_id' => $request->post('topic_id')]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
    }
    public function generate_quiz($topic_id, Request $request)
    {    set_time_limit(300); // Set the maximum execution time to 300 seconds

        Log::info('generate_quiz called', ['topic_id' => $topic_id, 'request' => $request->all()]);
    
        $topic = Topic::find($topic_id);
        if (!$topic) {
            Log::error('Topic not found', ['topic_id' => $topic_id]);
            return response()->json(['success' => false, 'message' => 'Topic not found.']);
        }
        
        $quizname = Question::where('topic_id', $topic_id)->pluck('question_title');
        if($quizname[0] === $request->post('name')){
            return response()->json(['success' => false, 'message' => 'Quiz Name Already Take']);
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

        if($request->post('type') == 'Multiple Choice'){
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates multiple-choice quiz questions. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate ". $number ." multiple-choice quiz questions. Each question must have four options labeled A, B, C, and D. Only one option should be correct. Format your response in JSON like this: 
        
                        {
                        \"questions\": [
                            {
                            \"question\": \"Question in here?\",
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
        
                        Text: " . $text . " the order of the question is must be not the same as the order of the Text i gave to you, re arrenge the order of the question."]
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
        
                $content = json_decode($responseData['choices'][0]['message']['content'], true);
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
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI that generates true or false quiz questions. Return the response in JSON format.'],
                        ['role' => 'user', 'content' => "Based on the following text, generate ". $number ." true or false quiz questions. Each question must have one answer either True or False. Format your response in JSON like this: 
        
                        {
                        \"questions\": [
                            {
                            \"question\": \"Question in here?\",
                            \"correct_answer\": \"True or False\"
                            }
                        ]
                        } 
        
                        Text: " . $text . " the order of the question is must be not the same as the order of the Text i gave to you, re arrenge the order of the question."]
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
        
                $content = json_decode($responseData['choices'][0]['message']['content'], true);
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
        
                return response()->json(['success' => true, 'data' => $content]);
        
            } catch (\Exception $e) {
                Log::error('Exception occurred in generate_quiz', ['exception' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }            
//===============================================================================================================================
        }else if($request->post('type') == 'Identification'){
//===============================================================================================================================
        }else{
            //return an error
        }
    
       
    }
}