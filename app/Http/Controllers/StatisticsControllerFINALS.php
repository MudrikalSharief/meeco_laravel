<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    public function get_weekly_statistics(Request $request){

        try{
        $weekData = $request->json()->all();
        $startDate = Carbon::parse($weekData[0]);
        $startDate_day_start = $startDate->day;
        $startDate_day_end = $startDate->copy()->addDays(6)->day;


        $daily_rev = Subscription::selectRaw('DATE(subscriptions.start_date) as date, SUM(price) as total_amount, COUNT(subscriptions.subscription_id) as total_subs')
            ->join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
            ->where('promos.name', '!=', 'Free Trial')
            ->where('subscriptions.subscription_type', '!=', 'Admin granted')
            ->whereBetween('subscriptions.start_date', [$startDate, $startDate->copy()->addDays(6)])
            ->groupBy('subscriptions.start_date')
            ->get();


        $totalRevenue = $daily_rev->sum('total_amount');
        $totalSubs = $daily_rev->sum('total_subs');
        $averageRevenue = ($totalSubs > 0) ? ($totalRevenue / $totalSubs) : 0;

        $daily_rev_arr = [];

        $week_labels = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $formattedDate = $currentDate->format('F j, Y (l)');
            $week_labels[] = $formattedDate;
        }

        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        if($daily_rev->isNotEmpty()){
            return response()->json([
                'week_labels'=> $week_labels,
                'daily_rev_arr'=> $daily_rev_arr,
                'total_amount'=>$totalRevenue,
                'average_rev'=>$averageRevenue,
                'total_subs'=>$totalSubs
            ]);  
        }else{
            return response()->json([
                'week_labels'=>$week_labels,
                'daily_rev_arr'=> [0,0,0,0,0,0,0],
                'total_amount'=>'0',
                'average_rev'=>'0',
                'total_subs'=>'0'
            ]);  
        }
    }

    public function get_yearly_statistics(Request $request){
        $yearData = $request->json()->all();
        $selectedYear = (int) $yearData[0];

        $yearly_rev = Subscription::selectRaw('MONTH(subscriptions.start_date) as month, SUM(price) as total_amount, COUNT(subscriptions.subscription_id) as total_subs')
        ->join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
        ->where('promos.name', '!=', 'Free Trial')
        ->where('subscriptions.subscription_type', '!=', 'Admin granted')
        ->whereYear('subscriptions.start_date', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        $fromDate = Carbon::parse(trim($dateVals[0], '"')); 
        $toDate = Carbon::parse(trim($dateVals[1], '"'));

        $fromDateDay = $fromDate->day; 
        $toDateDay = $toDate->day;

        $filter_rev = Subscription::selectRaw('DATE(subscriptions.start_date) as date, SUM(price) as total_amount, COUNT(subscriptions.subscription_id) as total_subs')
            ->join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
            ->where('promos.name', '!=', 'Free Trial')
            ->where('subscriptions.subscription_type', '!=', 'Admin granted')
            ->whereBetween('subscriptions.start_date', [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(subscriptions.start_date)'))
            ->get();

        $totalRevenue = $filter_rev->sum('total_amount');
        $totalSubs = $filter_rev->sum('total_subs');
        $averageRevenue = ($totalSubs > 0) ? ($totalRevenue / $totalSubs) : 0;
        $filter_rev_arr = [];

        // Generate the date range from $fromDate to $toDate
        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $datesInRange = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $datesInRange[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Initialize the revenue array with zeros
        $filter_rev_arr = array_fill(0, count($datesInRange), 0);

        // Fill the revenue array with actual data
        if ($filter_rev->isNotEmpty()) {
            foreach ($filter_rev as $revenue) {
                $revenueDate = Carbon::parse($revenue->date)->format('Y-m-d');
                $index = array_search($revenueDate, $datesInRange);
                if ($index !== false) {
                    $filter_rev_arr[$index] = $revenue->total_amount;
                }
            }
        }

        Log::info($filter_rev);
        $date_labels = [];
        $currentDate = $fromDate->copy();
        $totalDays = $fromDate->diffInDays($toDate) + 1;
        
        // Determine interval based on date range duration
        $interval = match(true) {
            $totalDays > 365 => 40,
            $totalDays > 180 => 25,
            $totalDays > 90 => 15,
            $totalDays > 30 => 5,
            $totalDays > 7 => 2,
            default => 1
        };
        
        $counter = 0;
        while ($currentDate <= $toDate) {
            // Add label or empty string maintaining position
            $date_labels[] = ($counter % $interval === 0) 
                ? $currentDate->format('F j, Y (l)')
                : '';
            
            $currentDate->addDay();
            $counter++;
        }
        
        if($filter_rev->isNotEmpty()){
            return response()->json([
                'date_labels'=> $date_labels,
                'filter_rev_arr'=> $filter_rev_arr,
                'total_amount'=>$totalRevenue,
                'average_rev'=>$averageRevenue,
                'total_subs'=>$totalSubs
            ]);  
        }else{
            return response()->json([
                'date_labels'=>$date_labels,
                'filter_rev_arr'=> [0,0,0,0,0,0,0],
                'total_amount'=>'PHP 0',
                'average_rev'=>'PHP 0',
                'total_subs'=>'0'
            ]);  
        }
    }

    public function filter_yearly_statistics(Request $request){
        $monthVals = $request->json()->all();
        
        $fromMonth = Carbon::parse(trim($monthVals[0], '"')); 
        $toMonth = Carbon::parse(trim($monthVals[1], '"'));
    
        $fromMonthmonth = $fromMonth->month; 
        $toMonthmonth = $toMonth->month;
    
        $filter_rev = Subscription::selectRaw('MONTH(subscriptions.start_date) as date, SUM(price) as total_amount, COUNT(subscriptions.subscription_id) as total_subs')
            ->join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
            ->where('promos.name', '!=', 'Free Trial')
            ->where('subscriptions.subscription_type', '!=', 'Admin granted')
            ->whereBetween('subscriptions.start_date', [$fromMonth, $toMonth])
            ->groupBy('date')
            ->get();
    
        $totalRevenue = $filter_rev->sum('total_amount');
        $totalSubs = $filter_rev->sum('total_subs');
        $averageRevenue = ($totalSubs > 0) ? ($totalRevenue / $totalSubs) : 0;
        
        $filter_rev_arr = [];
        $month_labels = [];
        $currentMonth = $fromMonth->copy();
        $lastMonth = $toMonth->copy();
    
        while ($currentMonth <= $lastMonth) {
            $month_labels[] = $currentMonth->format('F');
            $currentMonth->addMonth();
        }

        Log::info($month_labels);
    
        if($filter_rev->isNotEmpty()){
            $filter_rev_date = $filter_rev[0]->date;
    
            for($i = $fromMonthmonth; $i <= $toMonthmonth; $i++){
                if($i === $filter_rev_date){
                    array_push($filter_rev_arr, $filter_rev[0]->total_amount);
                }else{
                    array_push($filter_rev_arr, 0);
                }
            }
    
            return response()->json([
                'month_labels'=> $month_labels,
                'filter_rev_arr'=> $filter_rev_arr,
                'total_amount'=>$totalRevenue,
                'average_rev'=>$averageRevenue,
                'total_subs'=>$totalSubs
            ]);  
        }else{
            return response()->json([
                'month_labels'=> $month_labels,
                'filter_rev_arr'=> array_fill(0, count($month_labels), 0),
                'total_amount'=>'0',
                'average_rev'=>'0',
                'total_subs'=>'0'
            ]);  
        }
    }
}
