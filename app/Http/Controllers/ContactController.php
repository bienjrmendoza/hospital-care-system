<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;
use App\Mail\ContactUserMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);

        try {
            Mail::to('johnhowellatienza@gmail.com')->send(new ContactAdminMail($data));
            Mail::to($data['email'])->send(new ContactUserMail($data));

            return redirect()->back()
                ->with('success', 'Message sent successfully!')
                ->withFragment('inpatient');

        } catch (\Exception $e) {
            \Log::error('Contact form email failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Unable to send message. Please try again later.')
                ->withFragment('inpatient');
        }
    }
}
