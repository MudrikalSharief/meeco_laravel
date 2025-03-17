<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class OpenAIHelper
{
    public static function calculateAndLogCost($responseData)
    {
        $pertoken = 1000000;
        $input_price = 0.15;
        $output_price = 0.6;

        $input_cost_per_token = $input_price / $pertoken; // Cost per token in dollars
        $input_tokens = $responseData['usage']['prompt_tokens']; // Number of input tokens used
        $input_cost = $input_cost_per_token * $input_tokens; // Cost of input in dollars
        
        $output_cost_per_token = $output_price / $pertoken; // Cost per token in dollars
        $output_tokens = $responseData['usage']['completion_tokens']; // Number of output tokens used
        $output_cost = $output_cost_per_token * $output_tokens; // Cost of output in dollars

        $tokens_used = $input_tokens + $output_tokens; // Total tokens used
        $totalcost = $input_cost + $output_cost; // Total cost in dollars

        // Log the cost
        Log::info('OpenAI API Input Cost Per Token: ' . $input_cost_per_token);
        Log::info('OpenAI API Input Token: ' . $input_tokens);
        Log::info('OpenAI API Input Cost: $' . $input_cost);
        Log::info('OpenAI API Output Cost Per Tokens: ' . $output_cost_per_token);
        Log::info('OpenAI API Output Tokens: ' . $output_tokens);
        Log::info('OpenAI API Output Cost: $' . $output_cost);
        Log::info('Total Token used: ' . $tokens_used);
        Log::info('OpenAI API Total Cost: $' . $totalcost);
    }
}