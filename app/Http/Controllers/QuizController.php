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
            return response()->json(['success'=>false,'message' => 'No Question Yet']);
        }
        return response()->json(['success'=>true,'questions' => $questions]);
    }

    public function getQuiz($quizId)
    {   
        $id = intval($quizId);
        $question = Question::find($id);
        if($question === null){
            return response()->json(['success'=>false,'message' => 'Question not found']);
        }
        return response()->json(['success'=>true,'question' => $question]);
    }

    public function startQuiz($questionId)
    {
        $id = intval($questionId);
        $question_type = Question::where('question_id', $id)->pluck('question_type')->first();

        if ($question_type == 'Multiple Choice') {
            $questions = multiple_choice::where('question_id', $id)->get();
        } elseif ($question_type == 'True or false') {
            $questions = true_or_false::where('question_id', $id)->get();
        } elseif ($question_type == 'Identification') {
            $questions = Identification::where('question_id', $id)->get();
        } elseif ($question_type == 'Mixed') {
            $multiple_choice_questions = multiple_choice::where('question_id', $id)->get();
            $true_or_false_questions = true_or_false::where('question_id', $id)->get();
            $identification_questions = Identification::where('question_id', $id)->get();

            $questions = [
                'multiple_choice' => $multiple_choice_questions,
                'true_or_false' => $true_or_false_questions,
                'identification' => $identification_questions,
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Unidentified Question Type']);
        }

        if (empty($questions)) {
            return response()->json(['success' => false, 'message' => 'No Question Yet']);
        }

        return response()->json(['success' => true, 'questions' => $questions]);
    }

    public function takeQuiz($questionId)
    {   
        $id = intval($questionId);
        $question_type = Question::where('question_id', $id)->pluck('question_type')->first();

        if ($question_type == 'Multiple Choice') {
            $questions = multiple_choice::where('question_id', $id)->get();
        } elseif ($question_type == 'True or false') {
            $questions = true_or_false::where('question_id', $id)->get();
        } elseif ($question_type == 'Identification') {
            $questions = Identification::where('question_id', $id)->get();
        } elseif ($question_type == 'Mixed') {
            $multiple_choice_questions = multiple_choice::where('question_id', $id)->get();
            $true_or_false_questions = true_or_false::where('question_id', $id)->get();
            $identification_questions = Identification::where('question_id', $id)->get();

            $questions = [
                'multiple_choice' => $multiple_choice_questions,
                'true_or_false' => $true_or_false_questions,
                'identification' => $identification_questions,
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Unidentified Question Type']);
        }

        if (empty($questions)) {
            return response()->json(['success' => false, 'message' => 'No Question Yet']);
        }

        return view('posts.takequiz', compact('questions'));
    }

    public function getQuizResult($questionId)
    {
        $id = intval($questionId);
        $question_type = Question::where('question_id', $id)->pluck('question_type')->first();

        if ($question_type == 'Multiple Choice') {
            $questions = multiple_choice::where('question_id', $id)->get();
            $userAnswers = multiple_choice::where('question_id', $questionId)->pluck('user_answer')->filter();
           
            
            if($userAnswers->isEmpty()){
                return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type]);
            }
            
        } elseif ($question_type == 'True or false') {
            $questions = true_or_false::where('question_id', $id)->get();
            $userAnswers = true_or_false::where('question_id', $questionId)->pluck('user_answer')->filter();
            if($userAnswers->isEmpty()){
                return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type]);
            }
        } elseif ($question_type == 'Identification') {
            $questions = Identification::where('question_id', $id)->get();
            $userAnswers = Identification::where('question_id', $questionId)->pluck('user_answer')->filter();
            if($userAnswers->isEmpty()){
                return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type]);
            }
        } elseif ($question_type == 'Mixed') {
            $multiple_choice_questions = multiple_choice::where('question_id', $id)->get();
            $true_or_false_questions = true_or_false::where('question_id', $id)->get();
            $identification_questions = Identification::where('question_id', $id)->get();

            $questions = [
                'multiple_choice' => $multiple_choice_questions,
                'true_or_false' => $true_or_false_questions,
                'identification' => $identification_questions,
            ];

            $userAnswers = [
                'multiple_choice' => multiple_choice::where('question_id', $questionId)->pluck('user_answer')->toArray(),
                'true_or_false' => true_or_false::where('question_id', $questionId)->pluck('user_answer')->toArray(),
                'identification' => Identification::where('question_id', $questionId)->pluck('user_answer')->toArray(),
            ];

            // Check if all user answers are empty or null
            $allEmpty = true;
            foreach ($userAnswers as $answers) {
                if (!empty(array_filter($answers, function($answer) { return $answer !== null; }))) {
                    $allEmpty = false;
                    break;
                }
            }

            if ($allEmpty) {
                return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type]);
            }

        } else {    
            return response()->json(['success' => false, 'message' => 'Unidentified Question Type!']);
        }

        if (empty($userAnswers)) {
            return response()->json(['success' => false, 'message' => 'No Answer Yet', 'type' => $question_type]);
        }

        return response()->json(['success' => true, 'questions' => $questions, 'type' => $question_type]);
    }

        public function submitQuiz(Request $request)
        {
            
            return response()->json(['success' => false, 'request' => $request->all()]);
            $score = 0;
            
            $validatedData = $request->validate([
                'questionId' => 'required|integer',
            ]);
            
            $questionId = $validatedData['questionId'];
            $question_type = Question::where('question_id', $questionId)->pluck('question_type')->first();
            
            $answers = $request->except('questionId');
            $userAnswers = [];
            
            if(!$question_type === 'Mixed'){
                //this is the code for the non mixed question
                foreach ($answers as $question => $answer) {
                    $choiceId = intval(explode('_', $question)[1]);
                    $userAnswers[$choiceId] = $answer;
                }
            }            
           
            if ($question_type === 'Multiple Choice') {
                $mcAnswers = $request->input('multiple_choice', []);
                $multiple_choice_data = multiple_choice::where('question_id', $questionId)->get();
                // Update multiple choice answers
                foreach ($multiple_choice_data as $index => $data) {
                    if (isset($mcAnswers[$index])) {
                        multiple_choice::where('multiple_choice_id', $data->multiple_choice_id)
                            ->update(['user_answer' => $mcAnswers[$index]]);

                        if ($mcAnswers[$index] === $data->answer) {
                            $score++;
                        }
                    }
                }
                // return response()->json(['success' => false, 'request' => $request->all()]);
            } elseif ($question_type == 'True or false') {
                $tfAnswers = $request->input('true_or_false', []);
                
                $tf_data = true_or_false::where('question_id', $questionId)->get();
                // Update true or false answers
                foreach ($tf_data as $index => $data) {
                    if (isset($tfAnswers[$index])) {
                        true_or_false::where('true_or_false_id', $data->true_or_false_id)
                            ->update(['user_answer' => $tfAnswers[$index]]);

                        if ($tfAnswers[$index] === $data->answer) {
                            $score++;
                        }
                    }
                }
                

            } elseif ($question_type == 'Identification') {
                
                $idAnswers = $request->input('identification', []);
                $identification_data = Identification::where('question_id', $questionId)->get();
                // Update identification answers
                 
                 foreach ($identification_data as $index => $data) {
                    if (isset($idAnswers[$index])) {
                        Identification::where('Identification_id', $data->Identification_id)
                            ->update(['user_answer' => $idAnswers[$index]]);

                        if (strtolower(trim($idAnswers[$index])) === strtolower(trim($data->answer))) {
                            $score++;
                        }
                    }
                }
                
            } elseif ($question_type == 'Mixed') {
                
                // Separate user answers by type based on the data structure submitted by JavaScript
                $mcAnswers = $request->input('multiple_choice', []);
                $tfAnswers = $request->input('true_or_false', []);
                $idAnswers = $request->input('identification', []);
                
                // Get the correct answers and IDs
                $multiple_choice_data = multiple_choice::where('question_id', $questionId)->get();
                $tf_data = true_or_false::where('question_id', $questionId)->get();
                $identification_data = Identification::where('question_id', $questionId)->get();
                
                // Update multiple choice answers
                foreach ($multiple_choice_data as $index => $data) {
                    if (isset($mcAnswers[$index])) {
                        multiple_choice::where('multiple_choice_id', $data->multiple_choice_id)
                            ->update(['user_answer' => $mcAnswers[$index]]);

                        if ($mcAnswers[$index] === $data->answer) {
                            $score++;
                        }
                    }
                }

                // Update true or false answers
                foreach ($tf_data as $index => $data) {
                    if (isset($tfAnswers[$index])) {
                        true_or_false::where('true_or_false_id', $data->true_or_false_id)
                            ->update(['user_answer' => $tfAnswers[$index]]);

                        if ($tfAnswers[$index] === $data->answer) {
                            $score++;
                        }
                    }
                }

                // Update identification answers
                foreach ($identification_data as $index => $data) {
                    if (isset($idAnswers[$index])) {
                        Identification::where('Identification_id', $data->Identification_id)
                            ->update(['user_answer' => $idAnswers[$index]]);

                        if (strtolower(trim($idAnswers[$index])) === strtolower(trim($data->answer))) {
                            $score++;
                        }
                    }
                }
            }

            Question::where('question_id', $questionId)->update(['score' => $score]);
            
            return response()->json(['success' => true, 'question_id' => $questionId, 'request' => $request->all()]);
        }
}