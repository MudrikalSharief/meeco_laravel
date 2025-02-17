<?php 


namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function get_transactions(Request $request)
    {
        $transactions = Transaction::with('user', 'promo')->get();

        return view('admin.admin_transactions', compact('transactions'));
    }
}
