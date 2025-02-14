<?php  
namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Subscription; // Ensure this line is present
use Illuminate\Http\Request;

class PromoController extends Controller
{
    // Display a listing of the promos and subscriptions
    public function index()
    {
        $promos = Promo::all();
        $subscriptions = Subscription::all();
        $activePromosCount = Promo::where('status', 'active')->count();
        $totalSubscribersCount = Subscription::count();

        return view('admin.admin_subscription', compact('promos', 'subscriptions', 'activePromosCount', 'totalSubscribersCount'));
    }

    // Store a newly created promo in the database
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
            // Add other fields as necessary
        ]);

        Promo::create($data);

        return redirect()->route('admin.subscription')->with('success', 'Promo saved successfully!');
    }

    // Delete the specified promo from the database
    public function destroy(Promo $promo)
    {
        $promo->delete();

        // Redirect with success message
        return redirect()->route('promos.index')->with('success', 'Promo deleted successfully!');
    }
}
