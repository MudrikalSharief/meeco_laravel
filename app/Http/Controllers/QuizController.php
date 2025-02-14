<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getAllQuiz()
    {   
        $questions = Question::all();
        return response()->json(['success'=>true,'questions' => $questions]);
    }

    public function submitQuiz(Request $request)
    {
        $answers = $request->all();
        $score = 0;

        foreach ($answers as $question => $answer) {
            if ($answer === 'correct') {
                $score++;
            }
        }

        return response()->json(['score' => $score]);
    }
}
