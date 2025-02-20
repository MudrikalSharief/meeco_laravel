<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Str;

class ContactUsController extends Controller
{
    public function submitInquiry(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'category' => 'required',
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'upload.*' => 'nullable|file|mimes:jpg,png,svg|max:3072'
        ]);

        $ticketReference = Str::random(10);

        $contactUs = new ContactUs();
        $contactUs->ticket_reference = $ticketReference;
        $contactUs->email = $request->email;
        $contactUs->category = $request->category;
        $contactUs->question = $request->question;

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
    }
}
