<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubscriptionController extends Controller
{   

    // New function to add a new subscription
    public function addSubscription(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'promo_id' => 'required|exists:promos,id',
            'reference_number' => 'required|numeric|unique:subscriptions,reference_number',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
            'subscription_type' => 'required|string|max:255',
        ]);

        $subscription = Subscription::create($validatedData);

        Log::info('New subscription added with ID: ' . $subscription->subscription_id);

        return redirect()->route('admin.subscription')->with('success', 'Subscription added successfully.');
    }
    
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('admin.admin_subscription', compact('subscriptions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pricing' => 'required|numeric',
            'duration' => 'required|string|max:255',
            'photo_to_text' => 'required|in:unlimited,limited',
            'reviewer_generator' => 'required|in:unlimited,limited',
            'mock_quiz_generator' => 'required|in:unlimited,limited',
            'save_reviewer' => 'required|in:unlimited,limited',
            'download_reviewer' => 'required|in:unlimited,limited',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'discount_type' => 'required|in:percent,fixed',
            'percent_discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $user = Auth::user();
        $promo = Promo::find($request->promo_id);

        Subscription::create([
            'reference_number' => $request->reference_number,
            'name' => $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname,
            'pricing' => $promo->price,
            'duration' => $promo->duration,
            'start_date' => now(),
            'end_date' => now()->addDays($promo->duration),
            'status' => 'active',
            'promo_id' => $promo->promo_id,
            'user_id' => $user->user_id,
        ]);

        return redirect()->route('profile.show')->with('success', 'Subscription created successfully.');
    }

    public function payment($promo_id)
    {
        Log::info('Payment method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.paymentEmail', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        return view('subscriptionFolder.payment', compact('promo'));
    }

    public function paymentEmail($promo_id)
    {
        Log::info('PaymentEmail method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.payment', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        return view('subscriptionFolder.paymentEmail', compact('promo'));
    }

    public function gcashNumber($promo_id)
    {
        Log::info('GcashNumber method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.paymentEmail', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        return view('subscriptionFolder.gcashNumber', compact('promo'));
    }

    public function mpin($promo_id)
    {
        Log::info('Mpin method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.gcashNumber', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        return view('subscriptionFolder.mpin', compact('promo'));
    }

    public function payment1($promo_id)
    {
        Log::info('Payment1 method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.mpin', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        return view('subscriptionFolder.payment1', compact('promo'));
    }

    public function receipt($promo_id)
    {
        Log::info('Receipt method called with promo_id: ' . $promo_id);

        $promo = Promo::find($promo_id);

        if (!$promo) {
            Log::error('Promo not found with promo_id: ' . $promo_id);
            return redirect()->route('upgrade.payment1', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
        }

        Log::info('Promo found: ' . $promo->name);

        $user = Auth::user();
        $userName = "{$user->firstname} {$user->middlename} {$user->lastname}";
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($promo->duration);

        // Ensure name is always set
        $subscription = Subscription::create([
            'promo_id' => $promo_id,
            'user_id' => Auth::id(),
            'name' => $userName, // Set the name to the authenticated user's name
            'pricing' => $promo->price,
            'duration' => $promo->duration,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active', // Set the status to active
            'reference_number' => Subscription::max('reference_number') + 1, // Ensure reference_number is auto-incrementing
        ]);

        Log::info('Subscription created with ID: ' . $subscription->subscription_id);

        return view('subscriptionFolder.receipt', compact('promo', 'subscription', 'userName'));
    }

    public function checkSubscription(Request $request)
    {
        $user = $request->user();

        // Check if the user has an active subscription
        $subscription = Subscription::where('user_id', $user->user_id)
            ->where('end_date', '>=', Carbon::now())
            ->whereIn('status', ['Active','Limit Reached'])
            ->with('promo')
            ->first();

        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'No active subscription found.','subscription' => $subscription]);
        }

        // Check the limits for reviewers and quizzes
        $reviewerLimit = $subscription->promo->reviewer_limit;
        $quizLimit = $subscription->promo->quiz_limit;

        $reviewerCreated = $subscription->reviewer_created;
        $quizCreated = $subscription->quiz_created;
    

        $reviewerLimitReached = $reviewerCreated >= $reviewerLimit ;
        $quizLimitReached = $quizCreated >= $quizLimit;

        if($reviewerLimitReached && $quizLimitReached){
            if($subscription->status == 'Active'){
                $subscription->status = 'Limit Reached';
                $subscription->save();
            }

            return response()->json(['success' => true, 'reviewerLimitReached' => $reviewerLimitReached, 'quizLimitReached' => $quizLimitReached]);
        }
        
        
    }

    public function getQuizQuestionLimit(Request $request)
    {
        $user = $request->user();

        // Check if the user has an active subscription
        $subscription = Subscription::where('user_id', $user->user_id)
            ->whereIn('status', ['Active','Limit Reached'])
            ->where('end_date', '>=', Carbon::now())
            ->with('promo')
            ->first();
            
        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'No active subscription found.']);
        }

        $quizQuestionLimit = $subscription->promo->quiz_questions_limit ?? 0;
        $mixQuizLimit = $subscription->promo->mix_quiz_limit ?? 0; 
        $MixQuizType = $subscription->promo->can_mix_quiz ?? 0;
        return response()->json([
            'success' => true,
            'quiz_questions_limit' => $quizQuestionLimit,
            'mixQuizLimit' => $mixQuizLimit,
            'MixQuizType' => $MixQuizType,
        ]);
    }
}
