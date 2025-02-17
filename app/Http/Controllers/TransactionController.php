<?php 


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;

    //In bash, enter: tail -f storage/logs/laravel.log to see logs in real time

class TransactionController extends Controller
{
    public function get_transactions(Request $request)
    {
        $status = session('status');
        
        $query = Transaction::with('user', 'promo');
    
        if (!empty($status)) {
            $query->where('status', $status);
        }
    
        $transactions = $query->get();

        Log::info($transactions);
    
        return view('admin.admin_transactions', compact('transactions'));
    }

    public function filter_transactions(Request $request){
        $status = $request->input('status');
        session(['status' => $status]);
        return redirect()->route('admin.transactions');
    }
}
