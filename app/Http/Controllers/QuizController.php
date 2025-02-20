<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Identification;
use App\Models\multiple_choice;
use App\Models\Question;
use App\Models\true_or_false;
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
        $question_type = Question::where('question_id',$id)->pluck('question_type');
        if($question_type[0] == 'Multiple Choice'){
            $questions = multiple_choice::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '1No Question Yet']);
            }
            return response()->json(['success'=>true,'question' => $questions]);
            
        }        
        elseif($question_type[0] == 'True or false'){
            $questions = true_or_false::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '2No Question Yet']);
            }
            return response()->json(['success'=>true,'question' => $questions]);
        }
        elseif($question_type[0] == 'Identification'){
            $questions = Identification::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '3No Question Yet']);
            }
            return response()->json(['success'=>true,'question' => $questions]);
        }
    }   

    public function takeQuiz($questionId)
    {   
        $id = intval($questionId);

        $question_type = Question::where('question_id',$id)->pluck('question_type');
        
        if($question_type[0] == 'Multiple Choice'){
            $questions = multiple_choice::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '1No Question Yet']);
            }
            // return response()->json(['success'=>true,'question' => $questions]);
            return view('posts.takequiz', compact('questions'));
        }        
        elseif($question_type[0] == 'True or false'){
            $questions = true_or_false::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '2No Question Yet']);
            }
            // return response()->json(['success'=>true,'question' => $questions]);
            return view('posts.takequiz', compact('questions'));
        }
        elseif($question_type[0] == 'Identification'){
            $questions = Identification::where('question_id', $id)->get();
            if ($questions->isEmpty()) {
                return response()->json(['success' => false, 'message' => '3No Question Yet']);
            }
            // return response()->json(['success'=>true,'question' => $questions]);
            return view('posts.takequiz', compact('questions'));
        }

    }
    
    public function getQuizResult($questionId)
    {
        $id = intval($questionId);
        $question_type = Question::where('question_id', $id)->pluck('question_type');
        if($question_type[0] == 'Multiple Choice'){
            $questions = multiple_choice::where('question_id', $id)->get();
            $userAnswers = multiple_choice::where('question_id', $questionId)->pluck('user_answer');
            if ($userAnswers[0] === null) {
                return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type[0]]);
            }
            return response()->json(['success' => true, 'questions' => $questions, 'type' => $question_type[0]]);

        }elseif($question_type[0] == 'True or false'){
            $questions = true_or_false::where('question_id', $id)->get();
            $userAnswers = true_or_false::where('question_id', $questionId)->pluck('user_answer');
            if ($userAnswers[0] === null) {
                return response()->json(['success' => false, 'message' => 'No Anaswer Yet', 'type' => $question_type[0]]);
            }
            return response()->json(['success' => true, 'questions' => $questions, 'type' => $question_type[0]]);
        }elseif($question_type[0] == 'Identification'){
            $questions = Identification::where('question_id', $id)->get();
            $userAnswers = Identification::where('question_id', $questionId)->pluck('user_answer');
            if ($userAnswers[0] === null) {
                return response()->json(['success' => false, 'message' => 'No Anaswer Yet', 'type' => $question_type[0]]);
            }
            return response()->json(['success' => true, 'questions' => $questions, 'type' => $question_type[0]]);
        }
        
    }
    
    public function submitQuiz(Request $request)
    {
        $score = 0;
        $correctAnswers = [];

        // Validate and retrieve the questionId from the request
        $validatedData = $request->validate([
            'questionId' => 'required|integer',
        ]);
    
        $questionId = $validatedData['questionId'];
        $question_type = Question::where('question_id', $questionId)->pluck('question_type');
        
        //get the user answer from request
        $answers = $request->except('questionId');
        $userAnswers = [];
        foreach ($answers as $question => $answer) {
            $choiceId = intval(explode('_', $question)[1]);
            $userAnswers[$choiceId] = $answer;
        }
        
        if($question_type[0] === 'Multiple Choice'){
            
            // Retrieve all answers with the same question_id from the multiple_choice table
            $correctAnswers = multiple_choice::where('question_id', $questionId)->pluck('answer');
            $multiple_choice_id = multiple_choice::where('question_id', $questionId)->pluck('multiple_choice_id');
            
            //updating the useranswer in the multiple_choice table
            $index = 0;
            foreach($multiple_choice_id as $id){
                if(isset($userAnswers[$index])){
                    multiple_choice::where('multiple_choice_id', $id)
                    ->update(['user_answer' => $userAnswers[$index]]);
                    $index++;
                }
            }
            
        }elseif($question_type[0] == 'True or false'){
            
            // Retrieve all answers with the same question_id from the multiple_choice table
            $correctAnswers = true_or_false::where('question_id', $questionId)->pluck('answer');
            $TF_id = true_or_false::where('question_id', $questionId)->pluck('true_or_false_id');
            
            
            //updating the useranswer in the multiple_choice table
            $index = 0;
            foreach($TF_id as $id){
                if(isset($userAnswers[$index])){
                    true_or_false::where('true_or_false_id', $id)
                    ->update(['user_answer' => $userAnswers[$index]]);
                    $index++;
                }
            }
            
        }
        elseif($question_type[0] == 'Identification'){
            
            // Retrieve all answers with the same question_id from the multiple_choice table
            $correctAnswers = Identification::where('question_id', $questionId)->pluck('answer');
            $Identification_id = Identification::where('question_id', $questionId)->pluck('Identification_id');
            
            
            //updating the useranswer in the multiple_choice table
            $index = 0;
            
            foreach($Identification_id as $id){
                if(isset($userAnswers[$index])){
                    Identification::where('Identification_id', $id)
                    ->update(['user_answer' => $userAnswers[$index]]);
                    $index++;
                }
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

        return response()->json(['success' => true, 'question_id' => $questionId, 'request'=>$request->all()]);
    }
}
