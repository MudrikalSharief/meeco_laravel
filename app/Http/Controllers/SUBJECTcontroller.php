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
        $user = Auth::user();
        $user_id = $user->user_id;
        $subjects = Subject::where('user_id', $user_id)->get();
        return response()->json(['subjects' => $subjects]);
    }

    public function createSubject(Request $request)
    {   
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:subjects,name',
            ]);

            $subject = new Subject();
            $subject->name = $request->name;
            $subject->user_id = Auth::id(); // Set the user_id to the logged-in user's ID
            $subject->save();

            return response()->json(['success' => true, 'subject' => $subject]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating subject'], 500);
        }
    }

    public function getSubjectsForDropdown()
    {
        $subjects = Subject::all(['id', 'name']);
        return response()->json(['subjects' => $subjects]);
    }

    public function deleteSubject(Request $request)
    {
        try {
            $subject = Subject::findOrFail($request->id); // Ensure the correct key is used
            $subject->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting subject'], 500);
        }
    }

    public function editSubject(Request $request)
    {  
        try {
            $request->validate([
                'subject_id' => 'required|exists:subjects,subject_id',
                'name' => 'required|string|max:255|unique:subjects,name,' . $request->subject_id . ',subject_id',
            ]);
            // return response()->json(['success' => false, 'message' => $request->name], 500);
            
            $subject = Subject::findOrFail($request->subject_id);
            
            $subject->name = $request->name;
            $subject->save();
            return response()->json(['success' => true, 'subject' => $subject]);
        } catch (\Exception $e) {
            Log::error('Error editing subject: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error editing subject'], 500);
        }
    }
}
