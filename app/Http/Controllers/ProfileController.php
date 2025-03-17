<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $subscription = Subscription::where('user_id', $user->user_id)
                ->with('promo')
                ->orderBy('start_date', 'desc')
                ->first();
        
        // Check the limits for reviewers and quizzes
        $reviewerLimit = $subscription->promo->reviewer_limit;
        $quizLimit = $subscription->promo->quiz_limit;

        $reviewerCreated = $subscription->reviewer_created;
        $quizCreated = $subscription->quiz_created;
    

        $reviewerLimitReached = $reviewerCreated >= $reviewerLimit ;
        $quizLimitReached = $quizCreated >= $quizLimit;

        // if($reviewerLimitReached && $quizLimitReached){
        //     if($subscription->status == 'Active'){
        //         $subscription->status = 'Limit Reached';
        //         $subscription->save();
        //     }
        // }
        return view('components.profile', compact('user', 'subscription'));
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Delete the old profile picture if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = $path;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile picture updated successfully.');
    }

    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->user_id)
                                    ->whereIn('status', ['Active', 'Limit Reached',])
                                    ->orderBy('start_date', 'desc')
                                    ->first();

        if ($subscription) {
            $subscription->status = 'cancelled'; 
            $subscription->end_date = now(); 
            $subscription->save();
            return redirect()->route('profile')->with('success', 'Subscription cancelled successfully.');
        }

        return redirect()->route('profile')->with('error', 'No active subscription found.');
    }
}
