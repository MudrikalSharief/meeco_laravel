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
    public function get_statistics(){
        $recent_monday = Carbon::now()->startOfWeek();
        $recent_january = Carbon::now()->startOfMonth();
        
        $recent_5_years = [];
        for ($i = 0; $i < 5; $i++) {
            $recent_5_years[] = Carbon::now()->subYears($i)->startOfYear()->year;
        }
        $recent_5_years_str = array_map('strval', $recent_5_years);

        $daily_rev = Subscription::selectRaw('SUM(price) as total_amount')
        ->Join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
        ->where('start_date', '>=', $recent_monday)
        ->groupBy(DB::raw('DATE(start_date)'))
        ->get();

        $monthly_rev =  Subscription::selectRaw('SUM(price) as total_amount')
        ->leftJoin('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
        ->where('start_date', '>=', $recent_january)
        ->groupBy(DB::raw('DATE(start_date)'))
        ->get();

        $yearly_rev = Subscription::selectRaw('SUM(price) as total_amount')
        ->leftJoin('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
        ->where('start_date', '>=', $recent_5_years)
        ->groupBy(DB::raw('DATE(start_date)'))
        ->get();

        $daily_ol = User::selectRaw('COUNT(user_id) as total_users')
        ->where('last_login', '>=', $recent_monday)
        ->groupBy(DB::raw('DATE(last_login)'))
        ->get();

        $monthly_ol = User::selectRaw('COUNT(user_id) as total_users')
        ->where('last_login', '>=', $recent_january)
        ->groupBy(DB::raw('DATE(last_login)'))
        ->get();

        $yearly_ol = User::selectRaw('COUNT(user_id) as total_users')
        ->where('last_login', '>=', $recent_5_years)
        ->groupBy(DB::raw('DATE(last_login)'))
        ->get();

        return response()->json([
            'recent_5_years_str' => $recent_5_years_str,
            'daily_rev' => $daily_rev,
            'monthly_rev' => $monthly_rev,
            'yearly_rev' => $yearly_rev,
            'daily_ol' => $daily_ol,
            'monthly_ol' => $monthly_ol,
            'yearly_ol' => $yearly_ol
        ]);
    }
}
