<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Topic; // Add this import statement

class CaptureController extends Controller
{
    public function getImages()
    {
        $files = Storage::disk('public')->files('uploads');
        $images = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return response()->json(['images' => $images]);
    }

    public function delete(Request $request)
    {
        $filePath = $request->input('filePath');
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function extractText(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|integer|exists:topics,topic_id', // Update 'id' to the actual primary key column name
            
        ]);

        $topicId = $request->input('topic_id');
        $topic = Topic::find($topicId);

        return redirect()->route('capture.extracted', ['topic_id' => $topicId, 'topic_name' => $topic->name]);
    }
}