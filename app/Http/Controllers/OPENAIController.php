<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-4",
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
                            **Subject:** [Subject Name]
                            **Card 1:** [Content for Card 1]
                            **Card 2:** [Content for Card 2]
                            ...

                            ---

                            Input notes: " . $request->post('content')
                    ]
                ],
                "temperature" => 0.5,
                "max_tokens" => 2048
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
}