<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raw;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Log;

class ReviewerController extends Controller
{
    public function storeReviewer(Request $request)
    {
        // Validate the request
        $request->validate([
            'topic_id' => 'required|integer',
            'reviewer_text' => 'required|string',
        ]);

        // Find the Reviewer record by topic_id
        $reviewer = Reviewer::where('topic_id', $request->topic_id)->first();

        // If the record exists, update the reviewer_text
        if ($reviewer) {
            $reviewer->reviewer_text = $request->reviewer_text;
            $reviewer->save();
        } else {
            // If the record does not exist, create a new one
            $reviewer = Reviewer::create([
                'topic_id' => $request->topic_id,
                'reviewer_text' => $request->reviewer_text,
            ]);
        }

        return response()->json(['success' => true, 'reviewerText' => $reviewer->reviewer_text]);
        
    }

    public function showReviewPage($topicId)
    {   
            $topic = Topic::findOrFail($topicId);
            $reviewerText = Reviewer::where('topic_id', $topicId)->first()->reviewer_text ?? '';
            $rawText = Raw::where('topic_id', $topicId)->first()->raw_text ?? '';
            return view('posts.reviewer', compact('topic', 'reviewerText', 'topicId','rawText'));
    }
    //=======================================================================================================
    public function disectReviewer(Request $request)
    {
        $request->validate([
            'reviewerText' => 'required|string',
        ]);
    
        $response = $request->reviewerText;
        $response = preg_replace('/^---$/m', '', $response);
    
        // Match subjects and their associated cards
        preg_match_all('/\*\*Subject:\*\* (.*?)\s*(?=\*\*Subject:|\z)/s', $response, $matches, PREG_SET_ORDER);
    
        $data = [];
    
        foreach ($matches as $match) {
            $subject = trim($match[1]); // Extract the subject
            Log::info('Subject: ' . $subject);
    
            $cards = [];
            // Now, extract the content after subject to get all card content correctly
            preg_match_all('/\*\*Card (\d+):\*\*\s*(.*?)(?=\*\*Card (\d+):|\*\*Subject:|\z)/s', $match[0], $cardMatches);
    
            Log::info('Card Matches Count: ' . count($cardMatches[1]));
            Log::info('Card Matches: ' . json_encode($cardMatches[2])); // This should be card contents
    
            // Loop through the matched cards
            foreach ($cardMatches[2] as $cardContent) {
                Log::info('Extracted Card: ' . $cardContent);
                $cards[] = trim($cardContent); // Add the card content
            }
    
            $data[] = [
                'subject' => $subject,
                'cards' => $cards,
            ];
        }
    
        // If no matches found, return an error message
        if (empty($matches)) {
            return response()->json(['message' => 'No subjects or cards found.', 'input' => $request->reviewerText], 400);
        }
    
        return response()->json($data);
    }
    
    
    //=======================================================================================================
}