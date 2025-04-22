<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Exception;

class OpenAIHelper
{
    /**
     * Get the OpenAI API key with proper error handling
     * 
     * @return string|null
     * @throws Exception If API key is not set
     */
    public static function getApiKey()
    {
        // Try multiple methods to get the API key
        $apiKey = config('services.openai.key');
        
        if (empty($apiKey)) {
            $apiKey = env('OPENAI_API_KEY');
        }
        
        // If still empty, check if there's a file-based key (sometimes used for deployments)
        if (empty($apiKey) && file_exists(base_path('.openai'))) {
            $apiKey = trim(file_get_contents(base_path('.openai')));
        }
        
        if (empty($apiKey)) {
            Log::error('OPENAI_API_KEY is missing or empty');
            throw new Exception('OpenAI API key is not configured. Please check your environment settings.');
        }
        
        // Log a masked version of the key for security
        $maskedKey = substr($apiKey, 0, 3) . '...' . substr($apiKey, -3);
        Log::info('OPENAI_API_KEY available: ' . $maskedKey);
        
        return $apiKey;
    }
    
    /**
     * Calculate and log the cost of an OpenAI API request
     *
     * @param array $responseData The response from OpenAI API
     * @return void
     */
    public static function calculateAndLogCost($responseData)
    {
        if (!isset($responseData['usage'])) {
            return;
        }
        
        $usage = $responseData['usage'];
        $promptTokens = $usage['prompt_tokens'] ?? 0;
        $completionTokens = $usage['completion_tokens'] ?? 0;
        
        // Current pricing per 1K tokens (adjust as needed)
        $promptCostPer1K = 0.15; // $0.15 per 1K tokens input for gpt-4o-mini
        $completionCostPer1K = 0.60; // $0.60 per 1K tokens output for gpt-4o-mini
        
        // Calculate costs
        $promptCostPerToken = $promptCostPer1K / 1000;
        $completionCostPerToken = $completionCostPer1K / 1000;
        
        $promptCost = $promptTokens * $promptCostPerToken;
        $completionCost = $completionTokens * $completionCostPerToken;
        $totalCost = $promptCost + $completionCost;
        
        // Log detailed information
        Log::info('OpenAI API Input Cost Per Token: ' . $promptCostPerToken);
        Log::info('OpenAI API Input Token: ' . $promptTokens);
        Log::info('OpenAI API Input Cost: $' . number_format($promptCost, 7));
        
        Log::info('OpenAI API Output Cost Per Tokens: ' . $completionCostPerToken);
        Log::info('OpenAI API Output Tokens: ' . $completionTokens);
        Log::info('OpenAI API Output Cost: $' . number_format($completionCost, 7));
        
        Log::info('Total Token used: ' . ($promptTokens + $completionTokens));
        Log::info('OpenAI API Total Cost: $' . number_format($totalCost, 7));
    }
}