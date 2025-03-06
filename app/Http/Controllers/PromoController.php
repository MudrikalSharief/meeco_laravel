<?php  
namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    // Display a listing of the promos and subscriptions
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchSubscribers = $request->input('search_subscribers');

        $promos = Promo::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('price', 'like', "%{$search}%")
                             ->orWhere('duration', 'like', "%{$search}%")
                             ->orWhere('start_date', 'like', "%{$search}%")
                             ->orWhere('end_date', 'like', "%{$search}%")
                             ->orWhere('status', 'like', "%{$search}%");
            })
            ->get();

        $subscriptions = Subscription::query()
            ->when($searchSubscribers, function ($query, $searchSubscribers) {
                return $query->where('name', 'like', "%{$searchSubscribers}%")
                             ->orWhere('email', 'like', "%{$searchSubscribers}%")
                             ->orWhere('subscription_type', 'like', "%{$searchSubscribers}%")
                             ->orWhere('start_date', 'like', "%{$searchSubscribers}%")
                             ->orWhere('end_date', 'like', "%{$searchSubscribers}%");
            })
            ->get();

        $activePromosCount = Promo::where('status', 'active')->count();
        $inactivePromosCount = Promo::where('status', 'inactive')->count();
        $totalSubscribersCount = Subscription::count();

        return view('admin.admin_subscription', compact('promos', 'subscriptions', 'activePromosCount', 'inactivePromosCount', 'totalSubscribersCount'));
    }

    // Show the form for creating or editing a promo
    public function createOrEdit(Promo $promo = null)
    {
        return view('admin.admin_addPromo', compact('promo'));
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
            'photo_to_text' => 'required|string',
            'photo_limit' => 'nullable|integer',
            'reviewer_generator' => 'required|string',
            'reviewer_limit' => 'nullable|integer',
            'mock_quiz_generator' => 'required|string',
            'mock_quiz_limit' => 'nullable|integer',
            'save_reviewer' => 'required|string',
            'save_reviewer_limit' => 'nullable|integer',
            'perks' => 'nullable|string',
        ]);

        Promo::updateOrCreate(['promo_id' => $request->id], $data);

        return redirect()->route('admin.subscription')->with('success', 'Promo saved successfully!');
    }

    // Update the specified promo in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string|in:active,inactive',
            'perks' => 'nullable|string',
            'photo_to_text' => 'required|string|in:unlimited,limited',
            'reviewer_generator' => 'required|string|in:unlimited,limited',
            'mock_quiz_generator' => 'required|string|in:unlimited,limited',
            'save_reviewer' => 'required|string|in:unlimited,limited',
            'photo_to_text_limit' => 'nullable|integer',
            'reviewer_generator_limit' => 'nullable|integer',
            'mock_quiz_generator_limit' => 'nullable|integer',
            'save_reviewer_limit' => 'nullable|integer',
        ]);

        $promo = Promo::findOrFail($id);
        $promo->update($request->all());

        return redirect()->route('promos.index')->with('success', 'Promo updated successfully.');
    }

    // Delete the specified promo from the database
    public function destroy(Promo $promo)
    {
        $promo->delete();

        // Redirect with success message
        return redirect()->route('admin.subscription')->with('success', 'Promo deleted successfully!');
    }

    // Show the active promos on the upgrade page
    public function showPromos()
    {
        $user = Auth::user();
        $promos = Promo::all();
    
        // Add a subscribed attribute to each promo
        foreach ($promos as $promo) {
            $promo->subscribed = $user->subscriptions()->where('promo_id', $promo->promo_id)->exists();
        }
    
        return view('subscriptionFolder.upgrade', compact('promos'));
    }
}
