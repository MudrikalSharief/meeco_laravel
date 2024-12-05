<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Log;

class TOPICcontroller extends Controller
{
    public function getTopics()
    {
        $topics = Topic::all();
        // return view('posts.topics', compact('topics'));
        return response()->json(['topics' => $topics]);
    }

    public function getTopicsBySubject($subjectId)
    {
        $topics = Topic::where('subject_id', $subjectId)->get(['topic_id', 'name']);
        return response()->json(['topics' => $topics]);
    }

    public function getTopicsBySubjectName($subjectName)
    {
        $subject = Subject::where('name', $subjectName)->firstOrFail();
        $topics = Topic::where('subject_id', $subject->id)->get();
        return view('posts.topics', compact('topics', 'subject'));
    }

    public function createTopic(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:topics,name,NULL,id,subject_id,' . $request->subject_id,
                'subject_id' => 'required|integer|exists:subjects,subject_id',
            ]);

            $topic = new Topic();
            $topic->name = $request->name;
            $topic->subject_id = $request->subject_id;
            $topic->save();

            return response()->json(['success' => true, 'topic' => $topic]);
        } catch (\Exception $e) {
            Log::error('Error creating topic: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating topic'], 500);
        }
    }

    public function deleteTopic(Request $request)
    {
        try {
            $topic = Topic::findOrFail($request->id); // Ensure the correct key is used
            $topic->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting topic: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error deleting topic'], 500);
        }
    }
}
