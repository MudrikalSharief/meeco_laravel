<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getSubjects()
    {
        $subjects = Subject::all();
        return response()->json(['subjects' => $subjects]);
    }

    public function createSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->save();

        return response()->json(['success' => true, 'subject' => $subject]);
    }
}
