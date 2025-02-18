<?php 


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;

    //In bash, enter: tail -f storage/logs/laravel.log to monitor logs

class TransactionController extends Controller
{
    public function get_transactions(Request $request)
    {
        $status = session('status');
        $sort = session('sort');
        
        $query = Transaction::with('user', 'promo')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.user_id')
            ->leftJoin('promos', 'transactions.promo_id', '=', 'promos.promo_id')
            ->select('transactions.*');
    
        if (!empty($status) && $status != 'All') {
            $query->where('status', $status);
        }
        if (!empty($sort) && $sort != 'Sort By') {
            $query->orderBy($sort, 'asc');
        }
        
        // session()->flush();
        $transactions = $query->paginate(7);

        return view('admin.admin_transactions', compact('transactions'));
    }

    public function filter_transactions(Request $request){
        $status = $request->input('status');
        session(['status' => $status]);
        return redirect()->route('admin.transactions');
    }

    public function sort_transactions(Request $request){
        if($request->input('sortValue')== 'Users'){
            $sort = 'users.name';
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
