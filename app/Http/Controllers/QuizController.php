<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\multiple_choice;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getAllQuiz($topicId)
    {   
        $questions = Question::where('topic_id', $topicId)->get();
        if($questions->isEmpty()){
            return response()->json(['success'=>false,'message' => `No Question Yet, {{$questions}} `]);
        }
        return response()->json(['success'=>true,'questions' => $questions]);

    }

    public function getQuiz($quizId)
    {   
        $id = intval($quizId);
        $question = Question::find($id);
        if($question === null){
            return response()->json(['success'=>false,'message' => `Question not found, {{$question}} `]);
        }
        return response()->json(['success'=>true,'question' => $question]);
    }

    public function startQuiz($questionId){
        $id = intval($questionId);
        $question = multiple_choice::where('question_id',$id)->get();
        if($question === null){
            return response()->json(['success'=>false,'message' => `No Question Yet, {{$question}} `]);
        }
        return response()->json(['success'=>true,'question' => $question]);
    }

    public function takeQuiz($questionId)
    {
        $id = intval($questionId);
        $questions = multiple_choice::where('question_id', $id)->get();
        if ($questions->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No Question Yet']);
        }
        return view('posts.takequiz', ['questions' => $questions]);
    }
    
    public function getQuizResult($questionId)
    {
        $id = intval($questionId);
        $questions = multiple_choice::where('question_id', $id)->get();
        $correctAnswers = multiple_choice::where('question_id', $questionId)->pluck('answer');
        if ($correctAnswers[0] === null) {
            return response()->json(['success' => false, 'message' => 'No Answer Yet']);
        }
        return response()->json(['success' => true, 'questions' => $questions]);
    }
    
    public function submitQuiz(Request $request)
    {
        $score = 0;
    
        // Validate and retrieve the questionId from the request
        $validatedData = $request->validate([
            'questionId' => 'required|integer',
        ]);
    
        $questionId = $validatedData['questionId'];
    
        // Retrieve all answers with the same question_id from the multiple_choice table
        $correctAnswers = multiple_choice::where('question_id', $questionId)->pluck('answer');
        $multiple_choice_id = multiple_choice::where('question_id', $questionId)->pluck('multiple_choice_id');

    
        $answers = $request->except('questionId');
        $userAnswers = [];
        foreach ($answers as $question => $answer) {
            $choiceId = intval(explode('_', $question)[1]);
            $userAnswers[$choiceId] = $answer;
        }

        //updating the useranswer in the multiple_choice table
        $index = 0;
        foreach($multiple_choice_id as $id){
            if(isset($userAnswers[$index])){
                multiple_choice::where('multiple_choice_id', $id)
                ->update(['user_answer' => $userAnswers[$index]]);
                $index++;
            }
        }

            // Compare the request answers with the correct answers
        foreach ($userAnswers as $choiceId => $answer) {
            if (isset($correctAnswers[$choiceId]) && $answer === $correctAnswers[$choiceId]) {
                $score++;
            }
        }
       
        //updateing the score in the multiple_choice table
        Question::where('question_id', $questionId)->update(['score' => $score]);

        return response()->json(['success' => true, 'question_id' => $questionId]);
    }
}
