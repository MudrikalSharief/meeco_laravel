<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raw;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Topic;

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
}
