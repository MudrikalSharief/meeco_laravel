<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\User;
use App\Models\Reply;
use App\Models\AdminReply;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    public function submitInquiry(Request $request)
    {
        // Check if user is authenticated 
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit an inquiry.');
        }
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'upload.*' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:5120'
        ]);

        try {
            $ticketReference = Str::upper(Str::random(3)) . mt_rand(1000000, 9999999);

            $contactUs = new ContactUs();
            $contactUs->ticket_reference = $ticketReference;
            $contactUs->email = Auth::user()->email; // Use authenticated user's email
            $contactUs->category = $request->category;
            $contactUs->subject = $request->subject;
            $contactUs->question = $request->question;
            $contactUs->status = 'Pending';

            if ($request->hasFile('upload')) {
                $uploads = [];
                foreach ($request->file('upload') as $file) {
                    $path = $file->store('uploads', 'public');
                    $uploads[] = $path;
                }
                $contactUs->upload = json_encode($uploads);
            }

            $contactUs->save();

            return redirect()->route('contact')->with('success', 'Your inquiry has been submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Inquiry submission failed: ' . $e->getMessage());
            return redirect()->route('inquiry')->with('error', 'There was an issue submitting your inquiry. Please try again.');
        }
    }

    /**
     * Display the inquiry history for the authenticated user.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function inquiryHistory()
    {
        $user = Auth::user(); // Get the authenticated user
        
        // If user is authenticated, get their email, otherwise redirect to login
        if ($user) {
            $email = $user->email;
            
            // Fetch only inquiries that match the logged-in user's email
            $inquiries = ContactUs::where('email', $email)
                               ->orderBy('created_at', 'desc')
                               ->get();
                               
            return view('Contact.inquiry_history', compact('inquiries'));
        }
        
        // If not authenticated, redirect to login page
        return redirect()->route('login')->with('error', 'Please login to view your inquiry history');
    }

    /**
     * Display a listing of support tickets for admin
     */
    public function SupportTicketAdmin()
    {
        $InquiriesAdmin = ContactUs::latest()->paginate(10);
        return view('admin.admin_support', compact('InquiriesAdmin'));
    }

    public function getInquiryDetails($ticket_reference)
    {
        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();
        return view('Contact.inquiry_history2', compact('inquiry'));
    }

    public function getAdminInquiryDetails($ticket_reference)
    {
        $inquiry = ContactUs::with('replies', 'adminReplies')->where('ticket_reference', $ticket_reference)->firstOrFail();
        return view('admin.admin_supportReply', compact('inquiry'));
    }

    public function submitReply(Request $request, $ticket_reference)
    {
        $request->validate([
            'reply_user_question' => 'required|string',
            'reply_user_upload.*' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:5120'
        ]);

        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();

        $reply = new Reply();
        $reply->ticket_id = $inquiry->ticket_id;
        $reply->reply_user_question = $request->reply_user_question;

        if ($request->hasFile('reply_user_upload')) {
            $uploads = [];
            foreach ($request->file('reply_user_upload') as $file) {
                $path = $file->store('uploads', 'public');
                $uploads[] = $path;
            }
            $reply->reply_user_upload = json_encode($uploads);
        }

        $reply->save();

        // Update the status to "Responded"
        ContactUs::where('ticket_id', $inquiry->ticket_id)->update(['status' => 'Pending']);

        return redirect()->route('inquiry.details', ['ticket_reference' => $ticket_reference])->with('success', 'Reply submitted successfully.');
    }

    public function submitAdminReply(Request $request, $ticket_reference)
    {
        $request->validate([
            'reply_admin_question' => 'required|string',
            'reply_admin_upload.*' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:5120'
        ]);

        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();

        $reply = new AdminReply();
        $reply->ticket_id = $inquiry->ticket_id;
        $reply->reply_admin_question = $request->reply_admin_question;

        if ($request->hasFile('reply_admin_upload')) {
            $uploads = [];
            foreach ($request->file('reply_admin_upload') as $file) {
                $path = $file->store('uploads', 'public');
                $uploads[] = $path;
            }
            $reply->reply_admin_upload = json_encode($uploads);
        }
        
        $reply->save();

       // Update the status to "Responded"
       ContactUs::where('ticket_id', $inquiry->ticket_id)->update(['status' => 'Responded']);

       return redirect()->route('admin.reply', ['ticket_reference' => $ticket_reference])->with('success', 'Reply submitted successfully.');
    }

 

    public function filterInquiriesByStatus(Request $request)
    {
        $query = ContactUs::query();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('ticket_reference', 'like', "%{$search}%");
            });
        }
        
        $InquiriesAdmin = $query->latest()->paginate(10)->appends([
            'status' => $request->status,
            'search' => $request->search
        ]);
        
        return view('admin.admin_support', compact('InquiriesAdmin'));
    }
}
