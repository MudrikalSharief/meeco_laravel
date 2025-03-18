<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    public function get_statistics(Request $request){

        try{
        $weekData = $request->json()->all();
        $startDate = Carbon::parse($weekData[0]);
        $startDate->copy()->addDays(6);
        $startDate_day_start = $startDate->day;
        $startDate_day_end = $startDate->copy()->addDays(6)->day;

        Log::info($startDate);

        $daily_rev = Subscription::selectRaw('DATE(subscriptions.start_date) as date, SUM(price) as total_amount')
            ->join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
            ->whereBetween('subscriptions.start_date', [$startDate, $startDate->copy()->addDays(6)])
            ->groupBy('subscriptions.start_date')
            ->get();

        $daily_rev_arr = [];
        
        if($daily_rev->isNotEmpty()){
            $daily_rev_date = Carbon::parse($daily_rev[0]->date)->day;

            for($i = $startDate_day_start; $i <= $startDate_day_end; $i++){
                if($i === $daily_rev_date){
                    array_push($daily_rev_arr, $daily_rev[0]->total_amount);
                }else{
                    array_push($daily_rev_arr, 0);
                }
            }
        }

        $week_labels = [];
        for ($i = 0; $i < 7; $i++) {
            $week_labels[] = $startDate->copy()->addDays($i)->format('l');
        }

        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        if($daily_rev->isNotEmpty()){
            return response()->json([
                'week_labels'=> $week_labels,
                'daily_rev_arr'=> $daily_rev_arr
            ]);  
        }else{
            return response()->json([
                'week_labels'=>['Saturday', 'Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday', 'Sunday'],
                'daily_rev_arr'=> [0,0,0,0,0,0,0]
            ]);  
        }
    }
}
