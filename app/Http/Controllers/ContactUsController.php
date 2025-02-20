<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ContactUsController extends Controller
{
    public function submitInquiry(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'category' => 'required',
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'upload.*' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:5120'
        ]);

        try {
            $ticketReference = Str::upper(Str::random(3)) . mt_rand(1000000, 9999999);

            $contactUs = new ContactUs();
            $contactUs->ticket_reference = $ticketReference;
            $contactUs->email = $request->email;
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

    public function inquiryHistory()
    {
        $inquiries = ContactUs::all();
        return view('website.footer.inquiry_history', compact('inquiries'));
    }

    public function getInquiryDetails($ticket_reference)
    {
        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();
        return view('website.footer.inquiry_history2', compact('inquiry'));
    }

    public function submitReply(Request $request, $ticket_reference)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();
        // Assuming there's a replies column in the ContactUs model to store replies
        $replies = json_decode($inquiry->replies, true) ?? [];
        $replies[] = [
            'reply' => $request->reply,
            'created_at' => now(),
        ];
        $inquiry->replies = json_encode($replies);
        $inquiry->save();

        return redirect()->route('inquiry.details', ['ticket_reference' => $ticket_reference])->with('success', 'Reply submitted successfully.');
    }

    public function closeInquiry($ticket_reference)
    {
        $inquiry = ContactUs::where('ticket_reference', $ticket_reference)->firstOrFail();
        $inquiry->status = 'Closed';
        $inquiry->save();

        return redirect()->route('inquiry.details', ['ticket_reference' => $ticket_reference])->with('success', 'Inquiry closed successfully.');
    }
}
