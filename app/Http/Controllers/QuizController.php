<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Identification;
use App\Models\multiple_choice;
use App\Models\Question;
use App\Models\Topic;
use App\Models\true_or_false;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        ]);
        
        $questionId = $validatedData['questionId'];
        $question_type = Question::where('question_id', $questionId)->pluck('question_type')->first();
        
        // Log the submitted answers for debugging
        Log::info('Quiz submission', [
            'question_id' => $questionId,
            'question_type' => $question_type,
            'answers' => $request->except(['questionId', '_token'])
        ]);
        
        $answers = $request->except('questionId');
        $userAnswers = [];
        
        if(!$question_type === 'Mixed'){
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

                    // Check if answer is correct, considering both letter and direct value formats
                    if ($this->isCorrectMultipleChoiceAnswer($mcAnswers[$index], $data)) {
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
            // For mixed quiz, handle each quiz type separately
            $mcAnswers = $request->input('multiple_choice', []);
            $tfAnswers = $request->input('true_or_false', []);
            $idAnswers = $request->input('identification', []);
            
            // Log each type of answer for debugging
            Log::info('Mixed quiz answers', [
                'multiple_choice' => $mcAnswers,
                'true_or_false' => $tfAnswers, 
                'identification' => $idAnswers
            ]);
            
            // Process multiple choice questions
            $multiple_choice_data = multiple_choice::where('question_id', $questionId)->get();
            foreach ($multiple_choice_data as $index => $data) {
                if (isset($mcAnswers[$index])) {
                    multiple_choice::where('multiple_choice_id', $data->multiple_choice_id)
                        ->update(['user_answer' => $mcAnswers[$index]]);

                    // Check if answer is correct, considering both letter and direct value formats
                    if ($this->isCorrectMultipleChoiceAnswer($mcAnswers[$index], $data)) {
                        $score++;
                    }
                }
            }

            // Process true/false questions
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

            // Process identification questions
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
        }

        Question::where('question_id', $questionId)->update(['score' => $score]);
        Question::where('question_id', $questionId)
                ->where('high_score', '<', $score)
                ->update(['high_score' => $score]);

        // Update timer_result only if it exists in the request
        if ($request->has('timeResult')) {
            Question::where('question_id', $questionId)
                ->update(['timer_result' => $request->timeResult]);
        }
        
        $question = Question::find(id: $questionId);
        if ($question) {
            $question->score = $score;
            $question->save();
        } else {
            return response()->json(['success' => false, 'message' => 'Question not found']);
        }
        return response()->json(['success' => true, 'question_id' => $questionId,'score' => $score, 'request' => $request->all()]);
    }
    
    /**
     * Check if the provided answer is correct for a multiple choice question
     * Handles both direct letter answers (A, B, C, D) and actual content answers
     * Also handles special case where answer might be in format "A.8000"
     *
     * @param string $userAnswer The user's answer
     * @param object $questionData The question data from the database
     * @return bool Whether the answer is correct
     */
    private function isCorrectMultipleChoiceAnswer($userAnswer, $questionData)
    {
        // If the answer in the database is a single character (A, B, C, D)
        if (in_array($questionData->answer, ['A', 'B', 'C', 'D'])) {
            // Direct comparison of letters
            return $userAnswer === $questionData->answer;
        }
        
        // Special case for answers in format "A.8000" or similar
        if (preg_match('/^([A-D])\.(.+)$/', $questionData->answer, $matches)) {
            $letterPart = $matches[1]; // Extract the letter (A, B, C, or D)
            $valuePart = $matches[2]; // Extract the value (8000 in this example)
            
            // Check if user answered with just the letter
            if ($userAnswer === $letterPart) {
                return true;
            }
            
            // Check if user answered with the full value
            if ($userAnswer === $valuePart || $userAnswer === $questionData->answer) {
                return true;
            }
        }
        
        // If the answer in the database is the actual content, check if user's answer matches any option
        if ($userAnswer === 'A' && $questionData->A === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'B' && $questionData->B === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'C' && $questionData->C === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'D' && $questionData->D === $questionData->answer) {
            return true;
        }
        
        // Also check for options that might have formatted values like "A.8000"
        if ($userAnswer === 'A' && preg_match('/^A\.(.+)$/', $questionData->A, $matches) && $matches[1] === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'B' && preg_match('/^B\.(.+)$/', $questionData->B, $matches) && $matches[1] === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'C' && preg_match('/^C\.(.+)$/', $questionData->C, $matches) && $matches[1] === $questionData->answer) {
            return true;
        }
        if ($userAnswer === 'D' && preg_match('/^D\.(.+)$/', $questionData->D, $matches) && $matches[1] === $questionData->answer) {
            return true;
        }
        
        // If user's answer is the actual content (not A, B, C, D), check direct match
        if ($userAnswer === $questionData->answer) {
            return true;
        }
        
        // Check if user's answer is just a numeric/text part of an answer like "8000" from "A.8000"
        foreach (['A', 'B', 'C', 'D'] as $option) {
            $optionValue = $questionData->{$option};
            if (preg_match('/^[A-D]\.(.+)$/', $optionValue, $matches)) {
                $valueOnly = $matches[1];
                if ($userAnswer === $valueOnly && $optionValue === $questionData->answer) {
                    return true;
                }
            }
        }
        
        return false;
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

    /**
     * Redistribute answer choices to achieve a more balanced distribution of correct answers
     * This method modifies the data in place before storing in the database
     * 
     * @param array $multipleChoiceQuestions The array of multiple choice questions
     * @return array The modified questions with balanced answer distribution
     */
    private function balanceMultipleChoiceAnswers($quizData)
    {
        // Check if we have 'questions' key (for single quiz type) or specific quiz types
        if (isset($quizData['questions'])) {
            $questions = $quizData['questions'];
            $balancedQuestions = $this->redistributeAnswers($questions);
            $quizData['questions'] = $balancedQuestions;
        } elseif (isset($quizData['multiple_choice'])) {
            $questions = $quizData['multiple_choice'];
            $balancedQuestions = $this->redistributeAnswers($questions);
            $quizData['multiple_choice'] = $balancedQuestions;
        }
        
        return $quizData;
    }
    
    /**
     * Helper method to redistribute answers evenly across A, B, C, D options
     * 
     * @param array $questions The array of questions to redistribute
     * @return array The questions with redistributed answers
     */
    private function redistributeAnswers($questions)
    {
        // Count current distribution of answers
        $answerDistribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        foreach ($questions as $question) {
            if (isset($question['correct_answer']) && in_array($question['correct_answer'], ['A', 'B', 'C', 'D'])) {
                $answerDistribution[$question['correct_answer']]++;
            }
        }
        
        // Calculate target distribution (roughly equal)
        $totalQuestions = count($questions);
        $targetPerOption = ceil($totalQuestions / 4);
        
        // Redistribute answers
        foreach ($questions as $index => &$question) {
            $currentAnswer = $question['correct_answer'];
            
            // If the current answer is overrepresented, try to find a less used option
            if ($answerDistribution[$currentAnswer] > $targetPerOption) {
                $options = ['A', 'B', 'C', 'D'];
                shuffle($options); // Randomize to avoid always picking the same alternative
                
                foreach ($options as $newAnswer) {
                    if ($answerDistribution[$newAnswer] < $targetPerOption) {
                        // Swap the current correct answer with the new one
                        $temp = $question['choices'][$newAnswer];
                        $question['choices'][$newAnswer] = $question['choices'][$currentAnswer];
                        $question['choices'][$currentAnswer] = $temp;
                        
                        // Update tracking
                        $answerDistribution[$currentAnswer]--;
                        $answerDistribution[$newAnswer]++;
                        $question['correct_answer'] = $newAnswer;
                        break;
                    }
                }
            }
        }
        
        return $questions;
    }
    
    /**
     * Store quiz questions in the database with balanced answer distribution
     * This could be used by the OPENAI controller after generating questions
     *
     * @param int $questionId The question ID to associate with
     * @param array $content The content array with questions
     * @return void
     */
    public function storeMultipleChoiceQuestions($questionId, $content)
    {
        // First log what we received for debugging
        Log::info('storeMultipleChoiceQuestions called with:', [
            'questionId' => $questionId,
            'contentKeys' => array_keys($content),
            'contentSize' => isset($content['questions']) ? count($content['questions']) : 
                          (isset($content['multiple_choice']) ? count($content['multiple_choice']) : 'unknown')
        ]);
        
        // Get question record to determine how many questions we need
        $questionRecord = Question::find($questionId);
        if (!$questionRecord) {
            Log::error('Question record not found', ['question_id' => $questionId]);
            return;
        }
        
        // For Mixed type, we need to check if multiple_choice is specified in the question record
        $requestedCount = ($questionRecord->question_type === 'Mixed') ? 
            intval(json_decode($questionRecord->metadata ?? '{"multiple": 0}', true)['multiple'] ?? 0) : 
            $questionRecord->number_of_question;
            
        // If we couldn't determine the count, log an error
        if ($requestedCount <= 0) {
            Log::warning('Could not determine requested count for multiple choice questions', [
                'questionId' => $questionId,
                'question_type' => $questionRecord->question_type,
                'metadata' => $questionRecord->metadata ?? 'null'
            ]);
            // Default to 5 as a fallback
            $requestedCount = 5;
        }
        
        // First balance the answers
        $balancedContent = $this->balanceMultipleChoiceAnswers($content);
        
        // Now get the questions, strictly preferring 'questions' key over 'multiple_choice'
        $questions = [];
        if (isset($balancedContent['questions'])) {
            $questions = $balancedContent['questions'];
        } elseif (isset($balancedContent['multiple_choice'])) {
            $questions = $balancedContent['multiple_choice'];
        }
        
        // If we have more questions than requested, trim the excess
        $actualCount = count($questions);
        if ($actualCount > $requestedCount) {
            Log::info("Trimming multiple choice questions from {$actualCount} to {$requestedCount}");
            $questions = array_slice($questions, 0, $requestedCount);
            $actualCount = $requestedCount;
        }
        
        // Only proceed if we have questions to store
        if (empty($questions)) {
            Log::error('No multiple choice questions to store', ['question_id' => $questionId]);
            return;
        }

        // Log the final set of questions we'll be storing
        Log::info("Storing {$actualCount} multiple choice questions for question ID {$questionId}");
        
        // Delete any existing multiple choice questions for this question ID
        // This ensures we don't have duplicates if this method is called multiple times
        $deleted = multiple_choice::where('question_id', $questionId)->delete();
        if ($deleted > 0) {
            Log::info("Deleted {$deleted} existing multiple choice questions for question ID {$questionId}");
        }
        
        // Save the questions to database
        foreach ($questions as $questionData) {
            multiple_choice::create([
                'question_id' => $questionId,
                'question_text' => $questionData['question'],
                'answer' => $questionData['correct_answer'],
                'A' => $questionData['choices']['A'],
                'B' => $questionData['choices']['B'],
                'C' => $questionData['choices']['C'],
                'D' => $questionData['choices']['D'],
                'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
            ]);
        }
        
        // Loop until we have enough questions only if we need more
        if ($actualCount < $requestedCount) {
            $maxAttempts = 3; // Limit to prevent infinite loops
            $attempts = 0;
            
            while ($actualCount < $requestedCount && $attempts < $maxAttempts) {
                $attempts++;
                $remaining = $requestedCount - $actualCount;
                
                Log::info("Multiple Choice: Need {$remaining} more questions (attempt {$attempts})");
                
                // Get the OpenAIController to generate more questions
                $openaiController = new OPENAIController();
                $additionalQuestions = $openaiController->generateAdditionalQuestions(
                    $questionRecord->topic_id,
                    $remaining,
                    'Multiple Choice'
                );
                
                if (!empty($additionalQuestions)) {
                    Log::info("Generated " . count($additionalQuestions) . " additional multiple choice questions");
                    
                    // Add the new questions to our collection
                    foreach ($additionalQuestions as $questionData) {
                        multiple_choice::create([
                            'question_id' => $questionId,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'A' => $questionData['choices']['A'],
                            'B' => $questionData['choices']['B'],
                            'C' => $questionData['choices']['C'],
                            'D' => $questionData['choices']['D'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                        $actualCount++;
                    }
                } else {
                    Log::warning("Failed to generate additional multiple choice questions on attempt {$attempts}");
                    
                    // If we can't get more questions after max attempts, break out
                    if ($attempts >= $maxAttempts) {
                        Log::error("Maximum attempts reached. Could only generate {$actualCount} of {$requestedCount} requested questions");
                        break;
                    }
                }
            }
        }
        
        // Update the question count if we couldn't generate all questions
        if ($actualCount < $requestedCount && $questionRecord->question_type !== 'Mixed') {
            Log::warning("Updating question record with actual count: {$actualCount} (requested: {$requestedCount})");
            $questionRecord->number_of_question = $actualCount;
            $questionRecord->save();
        }
    }
    
    /**
     * Store true or false quiz questions in the database and request additional ones if needed
     *
     * @param int $questionId The question ID to associate with
     * @param array $content The content array with questions
     * @return void
     */
    public function storeTrueFalseQuestions($questionId, $content)
    {
        // First log what we received for debugging
        Log::info('storeTrueFalseQuestions called with:', [
            'questionId' => $questionId,
            'contentKeys' => array_keys($content),
            'contentSize' => isset($content['questions']) ? count($content['questions']) : 
                          (isset($content['true_or_false']) ? count($content['true_or_false']) : 'unknown')
        ]);

        // Get question record to determine how many questions we need
        $questionRecord = Question::find($questionId);
        if (!$questionRecord) {
            Log::error('Question record not found', ['question_id' => $questionId]);
            return;
        }

        // For Mixed type, we need to check if true_or_false is specified in the question record
        $requestedCount = ($questionRecord->question_type === 'Mixed') ? 
            intval(json_decode($questionRecord->metadata ?? '{"true_or_false": 0}', true)['true_or_false'] ?? 0) : 
            $questionRecord->number_of_question;

        // If we couldn't determine the count, log an error
        if ($requestedCount <= 0) {
            Log::warning('Could not determine requested count for true or false questions', [
                'questionId' => $questionId,
                'question_type' => $questionRecord->question_type,
                'metadata' => $questionRecord->metadata ?? 'null'
            ]);
            // Default to 5 as a fallback
            $requestedCount = 5;
        }
        
        // Get the questions, try multiple possible keys based on API response format
        $questions = [];
        if (isset($content['questions'])) {
            $questions = $content['questions'];
        } elseif (isset($content['true_or_false'])) {
            $questions = $content['true_or_false'];
        }
        
        // If we have more questions than requested, trim the excess
        $actualCount = count($questions);
        if ($actualCount > $requestedCount) {
            Log::info("Trimming true or false questions from {$actualCount} to {$requestedCount}");
            $questions = array_slice($questions, 0, $requestedCount);
            $actualCount = $requestedCount;
        }
        
        // Only proceed if we have questions to store
        if (empty($questions)) {
            Log::error('No true or false questions to store', ['question_id' => $questionId]);
            return;
        }
        
        // Log the final set of questions we'll be storing
        Log::info("Storing {$actualCount} true or false questions for question ID {$questionId}");
        
        // Delete any existing true or false questions for this question ID
        $deleted = true_or_false::where('question_id', $questionId)->delete();
        if ($deleted > 0) {
            Log::info("Deleted {$deleted} existing true or false questions for question ID {$questionId}");
        }
        
        // Save the questions to database
        foreach ($questions as $questionData) {
            true_or_false::create([
                'question_id' => $questionId,
                'question_text' => $questionData['question'],
                'answer' => $questionData['correct_answer'],
                'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
            ]);
        }
        
        // Loop until we have enough questions only if we need more
        if ($actualCount < $requestedCount) {
            $maxAttempts = 3; // Limit to prevent infinite loops
            $attempts = 0;
            
            while ($actualCount < $requestedCount && $attempts < $maxAttempts) {
                $attempts++;
                $remaining = $requestedCount - $actualCount;
                
                Log::info("True/False: Need {$remaining} more questions (attempt {$attempts})");
                
                // Get the OpenAIController to generate more questions
                $openaiController = new OPENAIController();
                $additionalQuestions = $openaiController->generateAdditionalQuestions(
                    $questionRecord->topic_id,
                    $remaining,
                    'True or false'
                );
                
                if (!empty($additionalQuestions)) {
                    Log::info("Generated " . count($additionalQuestions) . " additional true/false questions");
                    
                    // Add the new questions to our collection
                    foreach ($additionalQuestions as $questionData) {
                        true_or_false::create([
                            'question_id' => $questionId,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                        $actualCount++;
                    }
                } else {
                    Log::warning("Failed to generate additional true/false questions on attempt {$attempts}");
                    
                    // If we can't get more questions after max attempts, break out
                    if ($attempts >= $maxAttempts) {
                        Log::error("Maximum attempts reached. Could only generate {$actualCount} of {$requestedCount} requested questions");
                        break;
                    }
                }
            }
        }
        
        // Update the question count if we couldn't generate all questions
        if ($actualCount < $requestedCount && $questionRecord->question_type !== 'Mixed') {
            Log::warning("Updating question record with actual count: {$actualCount} (requested: {$requestedCount})");
            $questionRecord->number_of_question = $actualCount;
            $questionRecord->save();
        }
    }
    
    /**
     * Store identification quiz questions in the database and request additional ones if needed
     *
     * @param int $questionId The question ID to associate with
     * @param array $content The content array with questions
     * @return void
     */
    public function storeIdentificationQuestions($questionId, $content)
    {
        // First log what we received for debugging
        Log::info('storeIdentificationQuestions called with:', [
            'questionId' => $questionId,
            'contentKeys' => array_keys($content),
            'contentSize' => isset($content['questions']) ? count($content['questions']) : 
                          (isset($content['identification']) ? count($content['identification']) : 'unknown')
        ]);

        // Get question record to determine how many questions we need
        $questionRecord = Question::find($questionId);
        if (!$questionRecord) {
            Log::error('Question record not found', ['question_id' => $questionId]);
            return;
        }

        // For Mixed type, we need to check if identification is specified in the question record
        $requestedCount = ($questionRecord->question_type === 'Mixed') ? 
            intval(json_decode($questionRecord->metadata ?? '{"identification": 0}', true)['identification'] ?? 0) : 
            $questionRecord->number_of_question;

        // If we couldn't determine the count, log an error
        if ($requestedCount <= 0) {
            Log::warning('Could not determine requested count for identification questions', [
                'questionId' => $questionId,
                'question_type' => $questionRecord->question_type,
                'metadata' => $questionRecord->metadata ?? 'null'
            ]);
            // Default to 5 as a fallback
            $requestedCount = 5;
        }
        
        // Get the questions, try multiple possible keys based on API response format
        $questions = [];
        if (isset($content['questions'])) {
            $questions = $content['questions'];
        } elseif (isset($content['identification'])) {
            $questions = $content['identification'];
        }
        
        // If we have more questions than requested, trim the excess
        $actualCount = count($questions);
        if ($actualCount > $requestedCount) {
            Log::info("Trimming identification questions from {$actualCount} to {$requestedCount}");
            $questions = array_slice($questions, 0, $requestedCount);
            $actualCount = $requestedCount;
        }
        
        // Only proceed if we have questions to store
        if (empty($questions)) {
            Log::error('No identification questions to store', ['question_id' => $questionId]);
            return;
        }
        
        // Log the final set of questions we'll be storing
        Log::info("Storing {$actualCount} identification questions for question ID {$questionId}");
        
        // Delete any existing identification questions for this question ID
        $deleted = Identification::where('question_id', $questionId)->delete();
        if ($deleted > 0) {
            Log::info("Deleted {$deleted} existing identification questions for question ID {$questionId}");
        }
        
        // Save the questions to database
        foreach ($questions as $questionData) {
            Identification::create([
                'question_id' => $questionId,
                'question_text' => $questionData['question'],
                'answer' => $questionData['correct_answer'],
                'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
            ]);
        }
        
        // Loop until we have enough questions only if we need more
        if ($actualCount < $requestedCount) {
            $maxAttempts = 3; // Limit to prevent infinite loops
            $attempts = 0;
            
            while ($actualCount < $requestedCount && $attempts < $maxAttempts) {
                $attempts++;
                $remaining = $requestedCount - $actualCount;
                
                Log::info("Identification: Need {$remaining} more questions (attempt {$attempts})");
                
                // Get the OpenAIController to generate more questions
                $openaiController = new OPENAIController();
                $additionalQuestions = $openaiController->generateAdditionalQuestions(
                    $questionRecord->topic_id,
                    $remaining,
                    'Identification'
                );
                
                if (!empty($additionalQuestions)) {
                    Log::info("Generated " . count($additionalQuestions) . " additional identification questions");
                    
                    // Add the new questions to our collection
                    foreach ($additionalQuestions as $questionData) {
                        Identification::create([
                            'question_id' => $questionId,
                            'question_text' => $questionData['question'],
                            'answer' => $questionData['correct_answer'],
                            'blooms_level' => $questionData['blooms_level'] ?? 'Knowledge',
                        ]);
                        $actualCount++;
                    }
                } else {
                    Log::warning("Failed to generate additional identification questions on attempt {$attempts}");
                    
                    // If we can't get more questions after max attempts, break out
                    if ($attempts >= $maxAttempts) {
                        Log::error("Maximum attempts reached. Could only generate {$actualCount} of {$requestedCount} requested questions");
                        break;
                    }
                }
            }
        }
        
        // Update the question count if we couldn't generate all questions
        if ($actualCount < $requestedCount && $questionRecord->question_type !== 'Mixed') {
            Log::warning("Updating question record with actual count: {$actualCount} (requested: {$requestedCount})");
            $questionRecord->number_of_question = $actualCount;
            $questionRecord->save();
        }
    }
}