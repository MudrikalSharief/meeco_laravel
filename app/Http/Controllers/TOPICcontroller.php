<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
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


    public function getTopicByTopicId($topic_id){
        $id = intval($topic_id);

        if(!$id){
            return response()->json(['message'=>false, 'error' => 'problem in validation']);
        }

        $topic = Topic::where('topic_id', $topic_id)->pluck('name');
        if(!$topic){
            return response()->json(['message'=>false, 'error' => 'problem in query']);
        }
        
        return response()->json(['message'=>true, 'name' => $topic[0]]);
    }

    public function getTopicsBySubject($subjectId)
    {
        $checksubject = Subject::where('subject_id', $subjectId)
                    ->where('user_id', auth()->user()->user_id)          
                    ->get(['subject_id', 'name']);
        $topics = Topic::where('subject_id', $checksubject[0]->subject_id)
                    ->get(['topic_id', 'name']);
        if($topics->isEmpty()){
            return response()->json(['message'=>'No topics found for subject id: ' . $subjectId]);
        }
        return response()->json(['message'=>'','topics' => $topics, 'subject_id' => $subjectId , 'name' => $checksubject[0]->name]);
    }

    public function getTopicsBySubjectName($subjectID)
    {   
        if(ctype_digit($subjectID)){
            $subject = Subject::where('subject_id', $subjectID)->firstOrFail();
            if($subject === null){
                return view('posts.subject');
            }
            $topic = Topic::where('subject_id', $subject->id)->get();
            return view('posts.topics', compact('topic','subject'));
        }else{
            // return response()->json(['success' => false, 'message' => 'Invalid subject id', 'id' => $subjectID], 400);
            return view('posts.subject');
        }
    }

    public function createTopic(Request $request)
    {
        try {
            $messages = "$request->name, $request->subject_id";
            $request->validate([
                'name' => 'required|string|max:255|unique:topics,name,NULL,id,subject_id,' . $request->subject_id,
                'subject_id' => 'required|integer|exists:subjects,subject_id',
            ]);
            $messages = $messages . "validation passed";

            $topic = new Topic();
            $topic->name = $request->name;
            $topic->subject_id = $request->subject_id;
            $topic->save();
            $messages = $messages . "save done";
            return response()->json(['success' => true, 'topic' => $topic]);
        } catch (\Exception $e) {
            Log::error('Error creating topic: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $messages], 500);
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

    public function editTopic(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:topics,topic_id',
                'name' => 'required|string|max:255|unique:topics,name,NULL,id,subject_id,' . $request->subject_id,
            ]);

            $topic = Topic::findOrFail($request->id);
            $topic->name = $request->name;
            $topic->save();

            return response()->json(['success' => true, 'topic' => $topic]);
        } catch (\Exception $e) {
            Log::error('Error editing topic: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error editing topic'], 500);
        }
    }
}
