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
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Quiz not found']);
        }

        $question_type = $question->question_type;

        if ($question_type == 'Multiple Choice') {
            $questions = multiple_choice::where('question_id', $id)->get();
        } elseif ($question_type == 'True or false') {
            $questions = true_or_false::where('question_id', $id)->get();
        } elseif ($question_type == 'Identification') {
            $questions = Identification::where('question_id', $id)->get();
        } elseif ($question_type == 'Mixed') {
            $questions = [
                'multiple_choice' => multiple_choice::where('question_id', $id)->get(),
                'true_or_false' => true_or_false::where('question_id', $id)->get(),
                'identification' => Identification::where('question_id', $id)->get(),
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Unidentified Question Type']);
        }

        return response()->json([
            'success' => true,
            'questions' => $questions,
            'type' => $question_type,
            'score' => $question->score,
            'timer_result' => $question->timer_result, // Include the time taken
        ]);
    }

    public function submitQuiz(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'questionId' => 'required|integer',
                'elapsedTime' => 'required|integer',
                'multiple_choice' => 'array',
                'true_or_false' => 'array',
                'identification' => 'array',
            ]);

            $questionId = $validatedData['questionId'];
            $elapsedTime = $validatedData['elapsedTime'];
            $multipleChoiceAnswers = $validatedData['multiple_choice'] ?? [];
            $trueOrFalseAnswers = $validatedData['true_or_false'] ?? [];
            $identificationAnswers = $validatedData['identification'] ?? [];

            $score = 0;

            // Process multiple-choice answers
            $multipleChoiceQuestions = multiple_choice::where('question_id', $questionId)->get();
            foreach ($multipleChoiceQuestions as $index => $question) {
                if (isset($multipleChoiceAnswers[$index]) && $multipleChoiceAnswers[$index] === $question->answer) {
                    $score++;
                }
            }

            // Process true/false answers
            $trueOrFalseQuestions = true_or_false::where('question_id', $questionId)->get();
            foreach ($trueOrFalseQuestions as $index => $question) {
                if (isset($trueOrFalseAnswers[$index]) && $trueOrFalseAnswers[$index] === $question->answer) {
                    $score++;
                }
            }

            // Process identification answers
            $identificationQuestions = Identification::where('question_id', $questionId)->get();
            foreach ($identificationQuestions as $index => $question) {
                if (isset($identificationAnswers[$index]) && strtolower(trim($identificationAnswers[$index])) === strtolower(trim($question->answer))) {
                    $score++;
                }
            }

            // Save the elapsed time and score (if applicable)
            Question::where('question_id', $questionId)->update([
                'timer_result' => $elapsedTime,
                'score' => $score,
            ]);

            // Return a JSON response
            return response()->json([
                'success' => true,
                'question_id' => $questionId,
                'score' => $score,
                'elapsed_time' => $elapsedTime,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in submitQuiz: ' . $e->getMessage());

            // Return a JSON error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the quiz.',
            ], 500);
        }
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