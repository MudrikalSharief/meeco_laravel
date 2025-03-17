<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

    //In bash, enter: tail -f storage/logs/laravel.log to monitor logs

class TransactionController extends Controller
{
    public function get_transactions(Request $request)
    {
        $status = session('status');
        $sort = session('sort');
        
        $query = Subscription::with('user', 'promo')
            ->leftJoin('users', 'subscriptions.user_id', '=', 'users.user_id')
            ->leftJoin('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
            ->select('subscriptions.*');
    
        if (!empty($status) && $status != 'All') {
            $query->where('subscription_type', $status);
        }
        if (!empty($sort) && $sort != 'Sort By') {
            $query->orderBy($sort, 'asc');
        }
        
        // session()->flush();
        $transactions = $query;

        return view('admin.admin_transactions', compact('transactions'));
    }

    public function search_transactions(Request $request){
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search', '')['value'];
        $orderColumnIndex = $request->input('order.0.column', 0); 
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = Subscription::selectRaw('*')
        ->Join('promos', 'subscriptions.promo_id', '=', 'promos.promo_id')
        ->Join('users', 'subscriptions.user_id', '=', 'users.user_id')
        ->get();


    }

    public function filter_transactions(Request $request){
        $status = $request->input('status');
        session(['status' => $status]);
        return redirect()->route('admin.transactions');
    }

    public function sort_transactions(Request $request){
        if($request->input('sortValue')== 'Users'){
            $sort = 'users.lastname';
        }
        if($request->input('sortValue')== 'Date'){
            $sort = 'start_date';
        }
        if($request->input('sortValue')== 'Promo Type'){
            $sort = 'promos.name';
        }
        if($request->input('sortValue')== 'Amount'){
            $sort = 'amount';
        }
        if($request->input('sortValue')== 'Sort By'){
            $sort = 'Sort By';
        }
        session(['sort' => $sort]);

        return redirect()->route('admin.transactions');
    }

}
