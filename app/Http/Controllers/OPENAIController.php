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
                        "content" => $request->post('content')
                    ]
                ],
                "temperature" => 0,
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