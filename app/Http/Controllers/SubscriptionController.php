<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Laravel\Pail\ValueObjects\Origin\Console;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyStatisticsExport;
use App\Exports\MonthlyStatisticsExport;

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

    
    public function checkSubscription(Request $request)
    {
        $user = $request->user();
    
        // Check if the user has an active subscription
        $subscription = Subscription::where('user_id', $user->user_id)
            ->whereIn('status', ['Active', 'Limit Reached'])
            ->with('promo')
            ->orderBy('start_date', 'desc')
            ->first();
    

        // Check if the subscription has expired
        if ($subscription) {
            if ($subscription->status == 'Limit Reached'  && $subscription->end_date < Carbon::now()) {
               Log::info('Subscription has expired', ['end_date' => $subscription->end_date]);
                $subscription->status = 'Expired';
                $subscription->save();
            }
        }

        
        if (!$subscription) {
            // Check if the user had a subscription that expired recently (e.g., within the last 7 days)
            $recentlyExpiredSubscription = Subscription::where('user_id', $user->user_id)
                ->where('status', 'Expired')
                ->where('end_date', '<', Carbon::now())
                ->where('end_date', '>=', Carbon::now()->subDays(1))
                ->orderBy('end_date', 'desc')
                ->first();
    
            if ($recentlyExpiredSubscription) {
                $recentlyExpiredMessage = 'Your Last subscription expired last ' . Carbon::parse($recentlyExpiredSubscription->end_date)->format('F j, Y g:i A');
                return response()->json(['success' => true, 'notSubscribed' => true, 'recentlyExpired' => true, 'recentlyExpiredMessage' => $recentlyExpiredMessage, 'reviewerLimitReached' => false, 'quizLimitReached' => false]);
            }
    
            return response()->json(['success' => true, 'notSubscribed' => true, 'recentlyExpired' => false, 'reviewerLimitReached' => false, 'quizLimitReached' => false]);
        }
    
        // Check if the subscription is close to expiring
        $daysToExpire = Carbon::now()->diffInDays($subscription->end_date, false);
        $isCloseToExpiring = $daysToExpire <= 1;
        $isCloseToExpiringMessage = 'Your subscription is expiring on ' . Carbon::parse($subscription->end_date)->format('F j, Y g:i A');
    
        // Check the limits for reviewers and quizzes
        $reviewerLimit = $subscription->promo->reviewer_limit;
        $quizLimit = $subscription->promo->quiz_limit;
    
        $reviewerCreated = $subscription->reviewer_created;
        $quizCreated = $subscription->quiz_created;
    
        $reviewerLimitReached = $reviewerCreated >= $reviewerLimit;
        $quizLimitReached = $quizCreated >= $quizLimit;
    
        if ($reviewerLimitReached && $quizLimitReached) {
            if ($subscription->status == 'Active') {
                $subscription->status = 'Limit Reached';
                $subscription->save();
            }
        }
    
        return response()->json([
            'success' => true,
            'reviewerLimitReached' => $reviewerLimitReached,
            'quizLimitReached' => $quizLimitReached,
            'isCloseToExpiring' => $isCloseToExpiring,
            'isCloseToExpiringMessage' => $isCloseToExpiringMessage,
            'recentlyExpired' => false
        ]);
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


    // public function paymentEmail($promo_id)
    // {
    //     Log::info('PaymentEmail method called with promo_id: ' . $promo_id);

    //     $promo = Promo::find($promo_id);

    //     if (!$promo) {
    //         Log::error('Promo not found with promo_id: ' . $promo_id);
    //         return redirect()->route('upgrade.payment', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
    //     }

    //     Log::info('Promo found: ' . $promo->name);

    //     return view('subscriptionFolder.paymentEmail', compact('promo'));
    // }

    // public function gcashNumber($promo_id)
    // {
    //     Log::info('GcashNumber method called with promo_id: ' . $promo_id);

    //     $promo = Promo::find($promo_id);

    //     if (!$promo) {
    //         Log::error('Promo not found with promo_id: ' . $promo_id);
    //         return redirect()->route('upgrade.paymentEmail', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
    //     }

    //     Log::info('Promo found: ' . $promo->name);

    //     return view('subscriptionFolder.gcashNumber', compact('promo'));
    // }

    // public function mpin($promo_id)
    // {
    //     Log::info('Mpin method called with promo_id: ' . $promo_id);

    //     $promo = Promo::find($promo_id);

    //     if (!$promo) {
    //         Log::error('Promo not found with promo_id: ' . $promo_id);
    //         return redirect()->route('upgrade.gcashNumber', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
    //     }

    //     Log::info('Promo found: ' . $promo->name);

    //     return view('subscriptionFolder.mpin', compact('promo'));
    // }

    // public function payment1($promo_id)
    // {
    //     Log::info('Payment1 method called with promo_id: ' . $promo_id);

    //     $promo = Promo::find($promo_id);

    //     if (!$promo) {
    //         Log::error('Promo not found with promo_id: ' . $promo_id);
    //         return redirect()->route('upgrade.mpin', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
    //     }

    //     Log::info('Promo found: ' . $promo->name);

    //     return view('subscriptionFolder.payment1', compact('promo'));
    // }

    // public function receipt($promo_id)
    // {
    //     Log::info('Receipt method called with promo_id: ' . $promo_id);

    //     $promo = Promo::find($promo_id);

    //     if (!$promo) {
    //         Log::error('Promo not found with promo_id: ' . $promo_id);
    //         return redirect()->route('upgrade.payment1', ['promo_id' => $promo_id])->with('error', 'Promo not found. Please check again.');
    //     }

    //     Log::info('Promo found: ' . $promo->name);

    //     $user = Auth::user();
    //     $userName = "{$user->firstname} {$user->middlename} {$user->lastname}";
    //     $startDate = Carbon::now();
    //     $endDate = $startDate->copy()->addDays($promo->duration);

    //     // Ensure name is always set
    //     $subscription = Subscription::create([
    //         'promo_id' => $promo_id,
    //         'user_id' => Auth::id(),
    //         'name' => $userName, // Set the name to the authenticated user's name
    //         'pricing' => $promo->price,
    //         'duration' => $promo->duration,
    //         'start_date' => $startDate,
    //         'end_date' => $endDate,
    //         'status' => 'active', // Set the status to active
    //         'reference_number' => Subscription::max('reference_number') + 1, // Ensure reference_number is auto-incrementing
    //     ]);

    //     Log::info('Subscription created with ID: ' . $subscription->subscription_id);

    //     return view('subscriptionFolder.receipt', compact('promo', 'subscription', 'userName'));
    // }

    /**
     * Get subscription data for editing
     *
     * @param int $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscriptionData($subscription)
    {
        try {
            $subscription = Subscription::with('user', 'promo')->findOrFail($subscription);
            return response()->json($subscription);
        } catch (\Exception $e) {
            Log::error('Error fetching subscription data: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription not found'], 404);
        }
    }

    /**
     * Update the specified subscription
     *
     * @param Request $request
     * @param int $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSubscription(Request $request, $subscription)
    {
        try {
            $subscription = Subscription::findOrFail($subscription);
            
            $now = Carbon::now();
            
            // Dump all request data for debugging
            Log::info('Raw request data:', $request->all());
            
            // Check if subscription_type is in the request
            if (!$request->has('subscription_type')) {
                Log::error('subscription_type field is missing from the request');
            } else {
                Log::info('subscription_type in request: ' . $request->subscription_type);
                Log::info('Current subscription_type in DB: ' . $subscription->subscription_type);
            }
            
            $validatedData = $request->validate([
                'start_date' => 'required|date',
                'end_date' => [
                    'required',
                    'date',
                    'after:start_date', 
                    function ($attribute, $value, $fail) use ($now) {
                        // Ensure end_date is not in the past
                        if (Carbon::parse($value)->lt($now)) {
                            $fail('The end date must not be in the past.');
                        }
                    },
                ],
                'reviewer_created' => 'required|integer|min:0',
                'quiz_created' => 'required|integer|min:0',
                'status' => 'required|in:Active,Expired,Cancelled,Limit Reached',
                // Modify the validation to be more permissive for testing
                'subscription_type' => 'required|string',
            ]);

            // Use the original start date to ensure it doesn't change
            $validatedData['start_date'] = $subscription->start_date;
            
            // Try to update each field individually to pinpoint issues
            try {
                $subscription->status = $validatedData['status'];
                $subscription->save();
                Log::info('Status updated successfully');
            } catch (\Exception $e) {
                Log::error('Failed to update status: ' . $e->getMessage());
            }
            
            try {
                $subscription->end_date = $validatedData['end_date'];
                $subscription->save();
                Log::info('End date updated successfully');
            } catch (\Exception $e) {
                Log::error('Failed to update end_date: ' . $e->getMessage());
            }
            
            try {
                $subscription->reviewer_created = $validatedData['reviewer_created'];
                $subscription->quiz_created = $validatedData['quiz_created'];
                $subscription->save();
                Log::info('Counts updated successfully');
            } catch (\Exception $e) {
                Log::error('Failed to update counts: ' . $e->getMessage());
            }
            
            // Try updating subscription_type separately with direct query
            try {
                $subscription->subscription_type = $validatedData['subscription_type'];
                $subscription->save();
                Log::info('Subscription type updated successfully to: ' . $subscription->subscription_type);
            } catch (\Exception $e) {
                Log::error('Failed to update subscription_type: ' . $e->getMessage());
                
                // Try a direct DB update as a last resort
                try {
                    DB::table('subscriptions')
                        ->where('subscription_id', $subscription->subscription_id)
                        ->update(['subscription_type' => $validatedData['subscription_type']]);
                    Log::info('Used direct DB update for subscription_type');
                } catch (\Exception $dbEx) {
                    Log::error('Even direct DB update failed: ' . $dbEx->getMessage());
                }
            }
            
            // Verify the update worked
            $subscription->refresh();
            Log::info('Subscription after update:', [
                'status' => $subscription->status,
                'subscription_type' => $subscription->subscription_type,
                'end_date' => $subscription->end_date
            ]);
            
            return redirect()->route('admin.newtransactions')->with('success', 'Subscription updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating subscription: ' . $e->getMessage());
            return back()->with('error', 'Failed to update subscription: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the subscriptions for admin transactions page.
     * Show only active subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTransactions()
    {
        // Get only active subscriptions with their associated user
        $subscriptions = Subscription::with('user', 'promo')
            ->where('status', 'Active')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.admin_newtransactions', compact('subscriptions'));
    }

    /**
     * Display a listing of inactive subscriptions (Expired, Cancelled, Limit Reached).
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveTransactions()
    {
        // Get only inactive subscriptions
        $subscriptions = Subscription::with('user', 'promo')
            ->whereIn('status', ['Expired', 'Cancelled', 'Limit Reached'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.admin_inactive_transactions', compact('subscriptions'));
    }

    /**
     * Get subscription statistics for the graph
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscriptionStats(Request $request)
    {
        try {
            $fromDate = $request->input('from_date') 
                ? Carbon::parse($request->input('from_date')) 
                : Carbon::now()->subDays(6);
            
            $toDate = $request->input('to_date') 
                ? Carbon::parse($request->input('to_date')) 
                : Carbon::now();
            
            // Debug what we're looking for
            Log::info('Fetching subscription stats', [
                'from_date' => $fromDate->format('Y-m-d'),
                'to_date' => $toDate->format('Y-m-d')
            ]);
            
            // Modify the query to exclude "Free Trial" promos
            $rawData = DB::select('
                SELECT s.start_date, p.price 
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE s.start_date BETWEEN ? AND ?
                AND p.name != "Free Trial"
                ORDER BY s.start_date
            ', [$fromDate->startOfDay()->toDateTimeString(), $toDate->endOfDay()->toDateTimeString()]);
            
            Log::info('Raw subscription data count (excluding Free Trials): ' . count($rawData));
            
            // Initialize the day-by-day data structure
            $dailyData = [];
            $currentDate = $fromDate->copy();
            
            while ($currentDate <= $toDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $dailyData[$dateKey] = [
                    'date' => $currentDate->format('D, M j'),
                    'total' => 0,
                    'count' => 0
                ];
                $currentDate->addDay();
            }
            
            // Now process the raw data into daily totals
            $totalRevenue = 0;
            $totalSubscriptions = 0;
            
            foreach ($rawData as $row) {
                $date = Carbon::parse($row->start_date)->format('Y-m-d');
                $price = (float) $row->price;
                
                if (isset($dailyData[$date])) {
                    $dailyData[$date]['total'] += $price;
                    $dailyData[$date]['count']++;
                    
                    $totalRevenue += $price;
                    $totalSubscriptions++;
                }
                
                // Log individual row for debugging
                Log::info('Processing row', [
                    'date' => $date,
                    'price' => $price,
                    'running_total' => $dailyData[$date]['total']
                ]);
            }
            
            // Extract values for the chart
            $labels = [];
            $values = [];
            
            foreach ($dailyData as $date => $data) {
                $labels[] = $data['date'];
                $values[] = $data['total'];
                
                // Additional logging for debugging
                Log::info('Daily summary', [
                    'date' => $date,
                    'display_date' => $data['date'],
                    'total' => $data['total'],
                    'count' => $data['count']
                ]);
            }
            
            // Calculate average revenue per subscription
            $avgRevenue = $totalSubscriptions > 0 ? $totalRevenue / $totalSubscriptions : 0;
            
            // Log final summary
            Log::info('Summary statistics', [
                'totalRevenue' => $totalRevenue,
                'totalSubscriptions' => $totalSubscriptions,
                'avgRevenue' => $avgRevenue
            ]);
            
            return response()->json([
                'dailyData' => [
                    'labels' => $labels,
                    'values' => $values
                ],
                'summary' => [
                    'totalRevenue' => $totalRevenue,
                    'totalSubscriptions' => $totalSubscriptions,
                    'avgRevenue' => $avgRevenue
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching subscription statistics: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to retrieve subscription statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly subscription statistics for the graph
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyStats(Request $request)
    {
        try {
            $year = $request->input('year', Carbon::now()->year);
            
            // Get beginning and end of the year
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            
            Log::info('Fetching monthly subscription stats for year: ' . $year);
            
            // Modify the SQL to exclude "Free Trial" promos
            $monthlyData = DB::select('
                SELECT 
                    MONTH(s.start_date) as month,
                    SUM(p.price) as total,
                    COUNT(*) as count
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE YEAR(s.start_date) = ?
                AND p.name != "Free Trial"
                GROUP BY MONTH(s.start_date)
                ORDER BY MONTH(s.start_date)
            ', [$year]);
            
            Log::info('Monthly data rows found (excluding Free Trials): ' . count($monthlyData));
            
            // Initialize array with all months
            $months = [
                1 => ['label' => 'Jan', 'total' => 0, 'count' => 0],
                2 => ['label' => 'Feb', 'total' => 0, 'count' => 0],
                3 => ['label' => 'Mar', 'total' => 0, 'count' => 0],
                4 => ['label' => 'Apr', 'total' => 0, 'count' => 0],
                5 => ['label' => 'May', 'total' => 0, 'count' => 0],
                6 => ['label' => 'Jun', 'total' => 0, 'count' => 0],
                7 => ['label' => 'Jul', 'total' => 0, 'count' => 0],
                8 => ['label' => 'Aug', 'total' => 0, 'count' => 0],
                9 => ['label' => 'Sep', 'total' => 0, 'count' => 0],
                10 => ['label' => 'Oct', 'total' => 0, 'count' => 0],
                11 => ['label' => 'Nov', 'total' => 0, 'count' => 0],
                12 => ['label' => 'Dec', 'total' => 0, 'count' => 0]
            ];
            
            // Fill in the actual data
            foreach ($monthlyData as $row) {
                $month = (int)$row->month;
                $months[$month]['total'] = (float)$row->total;
                $months[$month]['count'] = (int)$row->count;
            }
            
            // Calculate summary statistics
            $annualRevenue = 0;
            $annualSubscriptions = 0;
            
            foreach ($months as $month) {
                $annualRevenue += $month['total'];
                $annualSubscriptions += $month['count'];
            }
            
            // Average monthly revenue (from months with actual subscriptions)
            $monthsWithRevenue = count(array_filter($months, function($month) {
                return $month['count'] > 0;
            }));
            
            $avgMonthlyRevenue = $monthsWithRevenue > 0 
                ? $annualRevenue / $monthsWithRevenue 
                : 0;
            
            // Extract labels and values for the chart
            $labels = array_column($months, 'label');
            $values = array_column($months, 'total');
            
            Log::info('Monthly summary calculated', [
                'annualRevenue' => $annualRevenue,
                'annualSubscriptions' => $annualSubscriptions,
                'avgMonthlyRevenue' => $avgMonthlyRevenue
            ]);
            
            return response()->json([
                'monthlyData' => [
                    'labels' => $labels,
                    'values' => $values
                ],
                'summary' => [
                    'annualRevenue' => $annualRevenue,
                    'annualSubscriptions' => $annualSubscriptions,
                    'avgMonthlyRevenue' => $avgMonthlyRevenue
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching monthly subscription statistics: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to retrieve monthly subscription statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total reviewer and quiz creation counts
     * 
     * @return array
     */
    public function getTotalCounts()
    {
        try {
            // Get total reviewer created across all subscriptions
            $totalReviewerCreated = Subscription::sum('reviewer_created');
            
            // Get total quiz created across all subscriptions
            $totalQuizCreated = Subscription::sum('quiz_created');
            
            Log::info('Total counts retrieved', [
                'totalReviewerCreated' => $totalReviewerCreated,
                'totalQuizCreated' => $totalQuizCreated
            ]);
            
            return [
                'totalReviewerCreated' => $totalReviewerCreated,
                'totalQuizCreated' => $totalQuizCreated
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving total counts: ' . $e->getMessage());
            return [
                'totalReviewerCreated' => 0,
                'totalQuizCreated' => 0
            ];
        }
    }

    /**
     * Get recent monthly revenue statistics for the dashboard
     * 
     * @param int $months Number of months to include (default: 6)
     * @return array
     */
    public function getDashboardMonthlyStats($months = 6)
    {
        try {
            // Get current year
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            
            // Determine which half of the year to display
            $isFirstHalf = $currentMonth <= 6;
            
            // Set start and end dates for current period
            if ($isFirstHalf) {
                $startMonth = Carbon::createFromDate($currentYear, 1, 1)->startOfMonth();
                $endMonth = Carbon::createFromDate($currentYear, 6, 30)->endOfMonth();
                $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                
                // Previous period is Jul-Dec of previous year
                $prevStartMonth = Carbon::createFromDate($currentYear - 1, 7, 1)->startOfMonth();
                $prevEndMonth = Carbon::createFromDate($currentYear - 1, 12, 31)->endOfMonth();
                $prevPeriodName = 'Jul - Dec ' . ($currentYear - 1);
            } else {
                $startMonth = Carbon::createFromDate($currentYear, 7, 1)->startOfMonth();
                $endMonth = Carbon::createFromDate($currentYear, 12, 31)->endOfMonth();
                $monthNames = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                
                // Previous period is Jan-Jun of current year
                $prevStartMonth = Carbon::createFromDate($currentYear, 1, 1)->startOfMonth();
                $prevEndMonth = Carbon::createFromDate($currentYear, 6, 30)->endOfMonth();
                $prevPeriodName = 'Jan - Jun ' . $currentYear;
            }
            
            $currentPeriodName = ($isFirstHalf ? 'Jan - Jun ' : 'Jul - Dec ') . $currentYear;
            
            Log::info('Fetching dashboard monthly stats', [
                'current_period' => [
                    'start_month' => $startMonth->format('Y-m-d'),
                    'end_month' => $endMonth->format('Y-m-d'),
                    'period' => $currentPeriodName
                ],
                'previous_period' => [
                    'start_month' => $prevStartMonth->format('Y-m-d'),
                    'end_month' => $prevEndMonth->format('Y-m-d'),
                    'period' => $prevPeriodName
                ]
            ]);
            
            // Get subscription data for current period
            $monthlyData = DB::select('
                SELECT 
                    MONTH(s.start_date) as month,
                    SUM(p.price) as total
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE s.start_date BETWEEN ? AND ?
                AND p.name != "Free Trial"
                GROUP BY MONTH(s.start_date)
                ORDER BY month ASC
            ', [$startMonth->toDateTimeString(), $endMonth->toDateTimeString()]);
            
            // Get total revenue for previous period
            $previousPeriodData = DB::select('
                SELECT 
                    SUM(p.price) as total
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE s.start_date BETWEEN ? AND ?
                AND p.name != "Free Trial"
            ', [$prevStartMonth->toDateTimeString(), $prevEndMonth->toDateTimeString()]);
            
            $previousPeriodTotal = $previousPeriodData[0]->total ?? 0;
            
            // Initialize monthly values with zeros
            $monthValues = array_fill(0, 6, 0);
            
            // Fill in actual data
            $totalRevenue = 0;
            foreach ($monthlyData as $row) {
                $month = (int)$row->month;
                $index = $isFirstHalf ? ($month - 1) : ($month - 7);
                
                if ($index >= 0 && $index < 6) {
                    $monthValues[$index] = (float)$row->total;
                    $totalRevenue += (float)$row->total;
                }
            }
            
            // Calculate growth percentage compared to previous 6-month period
            // Fixed growth calculation to handle when previous period is zero but current has revenue
            $growthPercentage = 0;
            if ($previousPeriodTotal > 0) {
                $growthPercentage = (($totalRevenue - $previousPeriodTotal) / $previousPeriodTotal) * 100;
            } else if ($totalRevenue > 0) {
                // If previous period had zero revenue but current period has revenue, growth is 100%
                $growthPercentage = 100;
            }
            
            // Format the data for the dashboard
            $result = [
                'labels' => $monthNames,
                'values' => $monthValues,
                'total_revenue' => $totalRevenue,
                'growth_percentage' => round($growthPercentage, 1),
                'period' => $currentPeriodName,
                'previous_period' => $prevPeriodName,
                'is_first_half' => $isFirstHalf,
                'previous_period_total' => $previousPeriodTotal // Adding for debugging
            ];
            
            Log::info('Dashboard monthly stats calculated', [
                'current_period_total' => $totalRevenue,
                'previous_period_total' => $previousPeriodTotal,
                'growth_percentage' => $result['growth_percentage']
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Error retrieving dashboard monthly stats: ' . $e->getMessage());
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $isFirstHalf = $currentMonth <= 6;
            
            return [
                'labels' => $isFirstHalf ? 
                    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] : 
                    ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'values' => [0, 0, 0, 0, 0, 0],
                'total_revenue' => 0,
                'growth_percentage' => 0,
                'period' => $isFirstHalf ? 'Jan - Jun ' . $currentYear : 'Jul - Dec ' . $currentYear,
                'previous_period' => $isFirstHalf ? 
                    'Jul - Dec ' . ($currentYear - 1) : 
                    'Jan - Jun ' . $currentYear,
                'is_first_half' => $isFirstHalf,
                'previous_period_total' => 0
            ];
        }
    }

    /**
     * Generate PDF for daily subscription statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateDailyStatsPdf(Request $request)
    {
        try {
            $fromDate = $request->input('from_date') 
                ? Carbon::parse($request->input('from_date')) 
                : Carbon::now()->subDays(6);
            
            $toDate = $request->input('to_date') 
                ? Carbon::parse($request->input('to_date')) 
                : Carbon::now();
            
            // Get daily subscription data
            $rawData = DB::select('
                SELECT 
                    DATE(s.start_date) as date,
                    SUM(p.price) as total,
                    COUNT(*) as count
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE s.start_date BETWEEN ? AND ?
                AND p.name != "Free Trial"
                GROUP BY DATE(s.start_date)
                ORDER BY date
            ', [$fromDate->startOfDay()->toDateTimeString(), $toDate->endOfDay()->toDateTimeString()]);
            
            // Calculate summary statistics
            $totalRevenue = 0;
            $totalSubscriptions = 0;
            
            $tableData = [
                'headerLeft' => 'Date',
                'headerRight' => 'Revenue (PHP)',
                'rows' => []
            ];
            
            foreach ($rawData as $row) {
                $date = Carbon::parse($row->date);
                $revenue = (float) $row->total;
                $count = (int) $row->count;
                
                $totalRevenue += $revenue;
                $totalSubscriptions += $count;
                
                $tableData['rows'][] = [
                    'label' => $date->format('D, M j, Y'),
                    'value' => 'PHP ' . number_format($revenue, 2)
                ];
            }
            
            // Calculate average revenue per subscription
            $avgRevenue = $totalSubscriptions > 0 ? $totalRevenue / $totalSubscriptions : 0;
            
            // Prepare data for PDF view
            $summaryItems = [
                [
                    'label' => 'Total Revenue',
                    'value' => 'PHP ' . number_format($totalRevenue, 2)
                ],
                [
                    'label' => 'Total Subscriptions',
                    'value' => $totalSubscriptions
                ],
                [
                    'label' => 'Avg. Revenue Per User',
                    'value' => 'PHP ' . number_format($avgRevenue, 2)
                ]
            ];
            
            $title = 'Daily Subscription Income Report';
            $dateRange = $fromDate->format('M j, Y') . ' - ' . $toDate->format('M j, Y');
            
            $pdf = PDF::loadView('pdf.statistics', [
                'title' => $title,
                'dateRange' => $dateRange,
                'summaryItems' => $summaryItems,
                'tableData' => $tableData
            ]);
            
            return $pdf->download('daily-subscription-report-' . $fromDate->format('Y-m-d') . '-to-' . $toDate->format('Y-m-d') . '.pdf');
        
        } catch (\Exception $e) {
            Log::error('Error generating daily stats PDF: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate PDF for monthly subscription statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateMonthlyStatsPdf(Request $request)
    {
        try {
            $year = $request->input('year', Carbon::now()->year);
            
            // Get monthly subscription data
            $monthlyData = DB::select('
                SELECT 
                    MONTH(s.start_date) as month,
                    SUM(p.price) as total,
                    COUNT(*) as count
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE YEAR(s.start_date) = ?
                AND p.name != "Free Trial"
                GROUP BY MONTH(s.start_date)
                ORDER BY month ASC
            ', [$year]);
            
            // Initialize table data
            $tableData = [
                'headerLeft' => 'Month',
                'headerRight' => 'Revenue (PHP)',
                'rows' => []
            ];
            
            // Month names
            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            
            // Calculate summary statistics
            $annualRevenue = 0;
            $annualSubscriptions = 0;
            $monthsWithRevenue = 0;
            
            foreach ($monthlyData as $row) {
                $month = (int) $row->month;
                $revenue = (float) $row->total;
                $count = (int) $row->count;
                
                $annualRevenue += $revenue;
                $annualSubscriptions += $count;
                $monthsWithRevenue++;
                
                $tableData['rows'][] = [
                    'label' => $monthNames[$month],
                    'value' => 'PHP ' . number_format($revenue, 2)
                ];
            }
            
            // Calculate average monthly revenue
            $avgMonthlyRevenue = $monthsWithRevenue > 0 
                ? $annualRevenue / $monthsWithRevenue 
                : 0;
            
            // Prepare data for PDF view
            $summaryItems = [
                [
                    'label' => 'Annual Revenue',
                    'value' => 'PHP ' . number_format($annualRevenue, 2)
                ],
                [
                    'label' => 'Annual Subscriptions',
                    'value' => $annualSubscriptions
                ],
                [
                    'label' => 'Avg. Monthly Revenue',
                    'value' => 'PHP ' . number_format($avgMonthlyRevenue, 2)
                ]
            ];
            
            $title = 'Monthly Subscription Income Report - ' . $year;
            $dateRange = 'January - December ' . $year;
            
            $pdf = PDF::loadView('pdf.statistics', [
                'title' => $title,
                'dateRange' => $dateRange,
                'summaryItems' => $summaryItems,
                'tableData' => $tableData
            ]);
            
            return $pdf->download('monthly-subscription-report-' . $year . '.pdf');
        
        } catch (\Exception $e) {
            Log::error('Error generating monthly stats PDF: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate Excel for daily subscription statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateDailyStatsExcel(Request $request)
    {
        try {
            $fromDate = $request->input('from_date') 
                ? Carbon::parse($request->input('from_date')) 
                : Carbon::now()->subDays(6);
            
            $toDate = $request->input('to_date') 
                ? Carbon::parse($request->input('to_date')) 
                : Carbon::now();
            
            // Get daily subscription data
            $rawData = DB::select('
                SELECT 
                    DATE(s.start_date) as date,
                    SUM(p.price) as total,
                    COUNT(*) as count
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE s.start_date BETWEEN ? AND ?
                AND p.name != "Free Trial"
                GROUP BY DATE(s.start_date)
                ORDER BY date
            ', [$fromDate->startOfDay()->toDateTimeString(), $toDate->endOfDay()->toDateTimeString()]);
            
            // Create a map from the raw data for easier lookup
            $dateDataMap = [];
            foreach ($rawData as $row) {
                $dateKey = Carbon::parse($row->date)->format('Y-m-d');
                $dateDataMap[$dateKey] = [
                    'total' => (float)$row->total,
                    'count' => (int)$row->count
                ];
            }
            
            // Create a consistent date range with all days, including those with zero income
            $dailyData = [];
            $currentDate = $fromDate->copy();
            $totalRevenue = 0;
            $totalSubscriptions = 0;
            
            // Loop through all days in the date range
            while ($currentDate <= $toDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $dateDisplay = $currentDate->format('D, M j, Y');
                
                // Check if we have data for this date
                if (isset($dateDataMap[$dateKey])) {
                    $revenue = $dateDataMap[$dateKey]['total'];
                    $count = $dateDataMap[$dateKey]['count'];
                } else {
                    // If no data, explicitly set to zero
                    $revenue = 0;
                    $count = 0;
                }
                
                // Ensure this day's data has all required fields
                $dailyData[$dateKey] = [
                    'date' => $dateDisplay,     // String: formatted date
                    'total' => $revenue,        // Float: revenue amount
                    'count' => $count           // Integer: subscription count
                ];
                
                // Log each day's data to verify it's properly formatted
                Log::info("Day data for {$dateDisplay}", [
                    'key' => $dateKey,
                    'revenue' => $revenue,
                    'count' => $count
                ]);
                
                // Add to totals
                $totalRevenue += $revenue;
                $totalSubscriptions += $count;
                
                // Move to next day
                $currentDate->addDay();
            }
            
            // Log the full dataset structure before passing to Excel export
            Log::info('Full daily data structure:', [
                'days_count' => count($dailyData),
                'date_range' => $fromDate->format('Y-m-d') . ' to ' . $toDate->format('Y-m-d'),
                'first_day_data' => json_encode(reset($dailyData))
            ]);
            
            // Calculate average revenue per subscription
            $avgRevenue = $totalSubscriptions > 0 ? $totalRevenue / $totalSubscriptions : 0;
            
            // Summary data
            $summary = [
                'totalRevenue' => $totalRevenue,
                'totalSubscriptions' => $totalSubscriptions,
                'avgRevenue' => $avgRevenue
            ];
            
            // Date range for the report title
            $dateRange = $fromDate->format('M j, Y') . ' to ' . $toDate->format('M j, Y');
            
            // Generate the Excel file
            return Excel::download(
                new DailyStatisticsExport($dailyData, $summary, $dateRange),
                'daily-subscription-report-' . $fromDate->format('Y-m-d') . '-to-' . $toDate->format('Y-m-d') . '.xlsx'
            );
            
        } catch (\Exception $e) {
            Log::error('Error generating daily stats Excel: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Failed to generate Excel: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate Excel for monthly subscription statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateMonthlyStatsExcel(Request $request)
    {
        try {
            $year = $request->input('year', Carbon::now()->year);
            
            // Fetch monthly data
            $monthlyData = DB::select('
                SELECT 
                    MONTH(s.start_date) as month,
                    SUM(p.price) as total,
                    COUNT(*) as count
                FROM subscriptions s
                JOIN promos p ON s.promo_id = p.promo_id
                WHERE YEAR(s.start_date) = ?
                AND p.name != "Free Trial"
                GROUP BY MONTH(s.start_date)
                ORDER BY month ASC
            ', [$year]);
            
            // Process the data
            $processedData = [];
            $annualRevenue = 0;
            $annualSubscriptions = 0;
            
            // Month abbreviations
            $monthAbbr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            foreach ($monthlyData as $row) {
                $month = (int) $row->month;
                $revenue = (float) $row->total;
                $count = (int) $row->count;
                
                $processedData[] = [
                    'label' => $monthAbbr[$month - 1],
                    'total' => $revenue,
                    'count' => $count
                ];
                
                $annualRevenue += $revenue;
                $annualSubscriptions += $count;
            }
            
            // Calculate average monthly revenue
            $monthsWithRevenue = count($processedData);
            $avgMonthlyRevenue = $monthsWithRevenue > 0 
                ? $annualRevenue / $monthsWithRevenue 
                : 0;
            
            // Summary data
            $summary = [
                'annualRevenue' => $annualRevenue,
                'annualSubscriptions' => $annualSubscriptions,
                'avgMonthlyRevenue' => $avgMonthlyRevenue
            ];
            
            // Generate the Excel file
            return Excel::download(
                new MonthlyStatisticsExport($processedData, $summary, $year),
                'monthly-subscription-report-' . $year . '.xlsx'
            );
            
        } catch (\Exception $e) {
            Log::error('Error generating monthly stats Excel: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Excel: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all available promos for the dropdown
        $promos = Promo::where('status', 'active')->orderBy('name')->get();
        
        return view('admin.admin_add_subscription', compact('promos'));
    }

    /**
     * Store a newly created subscription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function storeAdminSubscription(Request $request)
    {
        try {
            // Validate the input
            $validatedData = $request->validate([
                'email' => 'required|email',
                'promo_id' => 'required|exists:promos,promo_id',
                'start_date' => 'required|date',
                'status' => 'required|in:Active,Limit Reached',
                'subscription_type' => 'required|string|max:255',
            ]);

            // Find user by email
            $user = User::where('email', $validatedData['email'])->first();
            
            // Check if user exists
            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'User with this email does not exist.');
            }
            
            // Get the promo details
            $promo = Promo::findOrFail($validatedData['promo_id']);
            
            // Check if user already has an active subscription
            $activeSubscription = Subscription::where('user_id', $user->user_id)
                ->where('status', 'Active')
                ->where('end_date', '>=', Carbon::now())
                ->first();
                
            if ($activeSubscription) {
                // Check if the active subscription is for the same promo
                if ($activeSubscription->promo_id == $validatedData['promo_id']) {
                    return redirect()->back()->withInput()->with(
                        'error', 
                        'This user already has an active subscription with the same plan. The current subscription expires on ' . 
                        Carbon::parse($activeSubscription->end_date)->format('M d, Y') . '.'
                    );
                } else {
                    // If it's a different promo, show a more detailed error message
                    $currentPromo = Promo::find($activeSubscription->promo_id);
                    $currentPromoName = $currentPromo ? $currentPromo->name : 'Unknown Plan';
                    $newPromoName = $promo->name;
                    
                    // Use single quotes for HTML attributes to avoid double quote escaping issues
                    $errorMessage = "This user already has an active subscription with a different plan ({$currentPromoName}). " .
                        "You need to change the status of the current subscription to 'Expired' or 'Cancelled' before adding a new subscription. " .
                        "The current subscription expires on " . Carbon::parse($activeSubscription->end_date)->format('M d, Y') . ".";
                        
                    return redirect()->back()->withInput()->with('error', $errorMessage);
                }
            }

            // Calculate end date based on promo duration and start date
            $startDate = Carbon::parse($validatedData['start_date']);
            $endDate = $startDate->copy()->addDays($promo->duration);
            
            // Generate a truly unique reference number
            $latestReferenceNumber = Subscription::max('reference_number');
            
            // Try to find an unused reference number
            $referenceNumber = null;
            $attempts = 0;
            $maxAttempts = 10; // Prevent infinite loops
            
            do {
                // If we have a latest reference, increment it, otherwise start with a random number
                if (is_numeric($latestReferenceNumber)) {
                    $referenceNumber = (int)$latestReferenceNumber + 1 + $attempts;
                } else {
                    // Generate a random 6-digit number as a starting point if no existing references
                    $referenceNumber = mt_rand(100000, 999999);
                }
                
                // Check if this reference number exists
                $exists = Subscription::where('reference_number', $referenceNumber)->exists();
                $attempts++;
                
            } while ($exists && $attempts < $maxAttempts);
            
            // If we couldn't find a unique reference after max attempts, use a timestamp-based one
            if ($exists) {
                $referenceNumber = time() . mt_rand(100, 999);
                Log::info('Using timestamp-based reference number due to collision: ' . $referenceNumber);
            }
            
            // Log the reference number we're using
            Log::info('Using reference number: ' . $referenceNumber);
            
            // Create the new subscription
            $subscription = Subscription::create([
                'user_id' => $user->user_id,
                'promo_id' => $promo->promo_id,
                'reference_number' => $referenceNumber,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $validatedData['status'],
                'subscription_type' => $validatedData['subscription_type'],
                'reviewer_created' => 0,
                'quiz_created' => 0,
            ]);

            Log::info('New subscription added by admin', [
                'subscription_id' => $subscription->subscription_id,
                'user_email' => $user->email,
                'promo' => $promo->name,
                'admin' => Auth::user()->email ?? 'system'
            ]);

            return redirect()->route('admin.newtransactions')
                ->with('success', 'Subscription added successfully for ' . $user->email);
                
        } catch (\Exception $e) {
            Log::error('Error creating subscription: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }
    }
}
