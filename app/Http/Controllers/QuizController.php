<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Identification;
use App\Models\multiple_choice;
use App\Models\Question;
use App\Models\Topic;
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
        $subjectId = Topic::where('topic_id', $topicId)->pluck('subject_id')->first();
        return response()->json(['success'=>true,'questions' => $questions, 'subject_id' => $subjectId]);
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
        $score = 0;

        $validatedData = $request->validate([
            'questionId' => 'required|integer',
            'elapsed_time' => 'required|integer', // Validate elapsed time
        ]);

        $questionId = $validatedData['questionId'];
        $elapsedTime = $validatedData['elapsed_time'];

        $question_type = Question::where('question_id', $questionId)->pluck('question_type')->first();

        // Process answers (existing logic)
        $answers = $request->except(['questionId', 'elapsed_time']);
        $userAnswers = [];
        
        if ($question_type !== 'Mixed') {
            foreach ($answers as $question => $answer) {
                $choiceId = intval(explode('_', $question)[1]);
                $userAnswers[$choiceId] = $answer;
            }
        }

        if ($question_type === 'Multiple Choice') {
            $mcAnswers = $request->input('multiple_choice', []);
            $multiple_choice_data = multiple_choice::where('question_id', $questionId)->get();
            foreach ($multiple_choice_data as $index => $data) {
                if (isset($mcAnswers[$index])) {
                    multiple_choice::where('multiple_choice_id', $data->multiple_choice_id)
                        ->update(['user_answer' => $mcAnswers[$index]]);

                    if ($mcAnswers[$index] === $data->answer) {
                        $score++;
                    }
                }
            }
        } elseif ($question_type == 'True or false') {
            $tfAnswers = $request->input('true_or_false', []);
            $tf_data = true_or_false::where('question_id', $questionId)->get();
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
            $mcAnswers = $request->input('multiple_choice', []);
            $tfAnswers = $request->input('true_or_false', []);
            $idAnswers = $request->input('identification', []);
            
            $multiple_choice_data = multiple_choice::where('question_id', $questionId)->get();
            $tf_data = true_or_false::where('question_id', $questionId)->get();
            $identification_data = Identification::where('question_id', $questionId)->get();
            
            foreach ($multiple_choice_data as $index => $data) {
                if (isset($mcAnswers[$index])) {
                    multiple_choice::where('multiple_choice_id', $data->multiple_choice_id)
                        ->update(['user_answer' => $mcAnswers[$index]]);

                    if ($mcAnswers[$index] === $data->answer) {
                        $score++;
                    }
                }
            }

            foreach ($tf_data as $index => $data) {
                if (isset($tfAnswers[$index])) {
                    true_or_false::where('true_or_false_id', $data->true_or_false_id)
                        ->update(['user_answer' => $tfAnswers[$index]]);

                    if ($tfAnswers[$index] === $data->answer) {
                        $score++;
                    }
                }
            }

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

        // Save the elapsed time in the database
        Question::where('question_id', $questionId)->update([
            'score' => $score,
            'timer_result' => $elapsedTime,
        ]);

        return response()->json(['success' => true, 'question_id' => $questionId]);
    }

    public function resetQuiz($id)
    {
        $quiz = Question::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found']);
        }
        
        $quiz->score = 0;
        $quiz->save();
        
        multiple_choice::where('question_id', $id)->update(['user_answer' => null]);
        true_or_false::where('question_id', $id)->update(['user_answer' => null]);
        Identification::where('question_id', $id)->update(['user_answer' => null]);
        
        return response()->json(['success' => true, 'message' => 'Quiz reset successfully']);
    }

    public function deleteQuiz($id)
    {
        $quiz = Question::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found']);
        }

        $quiz->delete();

        return response()->json(['success' => true, 'message' => 'Quiz deleted successfully']);
    }

    public function editQuizName(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $quiz = Question::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found']);
        }
    
        $quiz->question_title = $validatedData['name'];
        $quiz->save();
    
        return response()->json(['success' => true, 'message' => 'Quiz name updated successfully']);
    }
}