<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\JobController; 

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ], [
            'email.email' => 'Email must be valid',
        ]);

        // Generate Reference ID like MSG-8842-GJ
        $rand1 = rand(1000, 9999);
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randChars = $chars[rand(0, 25)] . $chars[rand(0, 25)];
        $refId = "MSG-" . $rand1 . "-" . $randChars;

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'reference_id' => $refId,
        ]);

        return back()->with([
            'contact_success' => true,
            'ref_id' => $refId,
            'timestamp' => Carbon::now()->toDateTimeString() . ' UTC',
        ]);
    }
}