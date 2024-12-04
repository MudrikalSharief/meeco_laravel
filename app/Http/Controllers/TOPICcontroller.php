<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Topic;

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
        $request->validate([
            'name' => 'required|string|max:255|unique:topics,name,NULL,id,subject_id,' . $request->subject_id,
            'subject_id' => 'required|integer|exists:subjects,subject_id',
        ]);

        $topic = new Topic();
        $topic->name = $request->name;
        $topic->subject_id = $request->subject_id;
        $topic->save();

        return response()->json(['success' => true, 'topic' => $topic]);
    }
}
