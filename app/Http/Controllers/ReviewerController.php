<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raw;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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
        $topic_id = $request->validate([
            'topicId' => 'required|int',
        ]);
    
        $data = [];

        $reviewer = Reviewer::where(['topic_id' => $topic_id])->get();
        if(empty($reviewer)){
            return response()->json(['success' => false, 'message' => 'There are no reviewers for this topic.']);
        }else{
            foreach($reviewer as $item){
                $data[] = [$item->reviewer_about, $item->reviewer_text];
            }
        }
        if($data == null){
            return response()->json(['success' => false, 'message' => 'There are no reviewers for this topic.']);
        }

        return response()->json(['success' => true,'data' => $data]);
        
    }
    //=======================================================================================================

    public function downloadReviewer(Request $request)
    {
        try {
            $topicId = $request->input('topicId');
            $content = $request->input('content');
            $topicName = Topic::where('topic_id', $topicId)->pluck('name')->first();
            if (empty($content)) {
                return response()->json(['success' => false, 'message' => 'No reviewer content found for this topic.']);
            }
    
            $fileName = $topicName . '.txt';
            Storage::disk('local')->put($fileName, $content);
    
            return response()->json(['success' => true, 'file' => $fileName]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error downloading reviewer: ' . $e->getMessage());
    
            // Return a JSON response with the error message
            return response()->json(['success' => false, 'message' => 'An error occurred while downloading the reviewer. Please try again later.']);
        }
    }

    public function serveFile($fileName)
    {
        $filePath = storage_path('app/' . $fileName);
        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['success' => false, 'message' => 'File not found.']);
        }
    }
}