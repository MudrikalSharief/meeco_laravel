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
            'perks' => 'nullable|string',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image_limit' => 'required|integer',
            'reviewer_limit' => 'required|integer',
            'quiz_limit' => 'required|integer',
            'quiz_questions_limit' => 'required|integer',
            'can_mix_quiz' => 'required|boolean',
            'mix_quiz_limit' => 'required_if:can_mix_quiz,1|integer',
            'status' => 'required|string',
        ]);

        $create = Promo::updateOrCreate(['promo_id' => $request->id], $data);
    
        return redirect()->route('admin.subscription')->with('success', 'Promo saved successfully!');
    }

    // Update the specified promo in the database
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'perks' => 'nullable|string',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image_limit' => 'required|integer',
            'reviewer_limit' => 'required|integer',
            'quiz_limit' => 'required|integer',
            'quiz_questions_limit' => 'required|integer',
            'can_mix_quiz' => 'required|boolean',
            'mix_quiz_limit' => 'required_if:can_mix_quiz,1|integer',
            'status' => 'required|string|in:active,inactive',
        ]);

        $promo = Promo::findOrFail($id);
        $promo->update($data);

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
        $promos = Promo::where('status', 'active')->get();
    
        // Add a subscribed attribute to each promo
        foreach ($promos as $promo) {
            $promo->subscribed = $user->subscriptions()->where('promo_id', $promo->promo_id)->where('status', 'active')->exists();
        }
    
        return view('subscriptionFolder.upgrade', compact('promos'));
    }
}
