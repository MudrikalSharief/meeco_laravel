<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function getSubjects()
    {
        $subjects = Subject::all();
        return response()->json(['subjects' => $subjects]);
    }

    public function createSubject(Request $request)
    {   
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $subject = new Subject();
            $subject->name = $request->name;
            $subject->user_id = Auth::id(); // Set the user_id to the logged-in user's ID
            $subject->save();

            return response()->json(['success' => true, 'subject' => $subject]);
        } catch (\Exception $e) {
            Log::error('Error creating subject: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error creating subject'], 500);
        }
    }
}
