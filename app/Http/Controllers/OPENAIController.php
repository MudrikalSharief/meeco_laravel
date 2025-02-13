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
        try {
            $request->validate([
                'content' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('OPENAI_API_KEY')
            ])
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-4o-mini",
                "messages" => [
                    [
                        "role" => "user",
                        "content" => "
                            You are an AI that helps generate organized study reviewers. A user has uploaded notes, and your task is to analyze the content, identify the subjects, and divide the information accordingly. 

                            Here are the rules for processing:
                            1. Identify the main subjects/topics in the notes (e.g., Math, Science, English).
                            2. For each subject, break the content into smaller, logically grouped pieces that fit on individual cards.
                            3. Each piece should focus on a single concept or subtopic, with no piece being longer than 100 words.
                            4. If the notes are unclear, use your best judgment to organize the content logically while keeping it concise and easy to understand.
                            5. Use simple and clear language suitable for a reviewer.

                            Example format for the output:
                            ---
                            
                            **Subject:** Subject Name
                            **Card 1:** Title for card 1 Content for Card 1
                            **Card 2:** Title for card 1 Content for Card 2
                            ...
                            ---
                            
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
            return response()->json(json_decode($responseBody, true));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

   
    public function generate_quiz($topic_id, Request $request)
    {
        $topic = Topic::find($topic_id);
        
        if (!$topic) {
            return response()->json(['success' => false, 'message' => 'Topic not found.']);
        }
    
        // Retrieve reviewer text and check if it exists
        $reviewer = Reviewer::where('topic_id', $topic_id)->first();
        if (!$reviewer) {
            return response()->json(['success' => false, 'message' => 'No reviewer found for this topic.']);
        }
    
        $reviewerText = $reviewer->reviewer_text;
        $number = $request->post('number');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI that generates multiple-choice quiz questions. Return the response in JSON format.'],
                    ['role' => 'user', 'content' => "Based on the following text, generate ". $number ." multiple-choice quiz questions. Each question must have four options labeled A, B, C, and D. Only one option should be correct. Format your response in JSON like this: 
    
                    {
                    \"questions\": [
                        {
                        \"question\": \"Which browser was the first widely popular web browser?\",
                        \"choices\": {
                            \"A\": \"Internet Explorer\",
                            \"B\": \"Mozilla Firefox\",
                            \"C\": \"Netscape Navigator\",
                            \"D\": \"Google Chrome\"
                        },
                        \"correct_answer\": \"C\"
                        }
                    ]
                    } 
    
                    Text: " . $reviewerText]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4096
            ]);
    
            if ($response->failed()) {
                return response()->json(['success' => false, 'message' => 'Failed to communicate with OpenAI API.']);
            }
    
            $responseData = json_decode($response->body(), true);
            Log::info('OpenAI API Response:', ['response' => $responseData]);

            if (!isset($responseData['choices'][0]['message']['content'])) {
                return response()->json(['success' => false, 'message' => 'Invalid response format from OpenAI API.']);
            }

            $content = json_decode($responseData['choices'][0]['message']['content'], true);
            Log::info('Parsed Content:', ['content' => $content]);

            if (empty($content['questions'])) {
                return response()->json(['success' => false, 'message' => 'No questions generated'.$number.'.', 'data' => $content,'raw' => $reviewerText]);
            }
            
            $question = Question::create([
                'topic_id' => $topic_id,
                'question_type' => 'multiple_choice',
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
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}