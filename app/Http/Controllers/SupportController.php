<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;

class SupportController extends Controller
{
    // For Customers: Show form (Accessible to all)
    public function index() {
        return view('support.index');
    }

    // For Customers: Submit form (Accessible to all)
   public function send(Request $request) {
    // If user is NOT logged in, require name and email fields
    if (!Auth::check()) {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'subject' => 'required',
            'message' => 'required',
        ]);
    }

    // Determine sender details safely
    $name = Auth::check() ? Auth::user()->name : $request->guest_name;
    $email = Auth::check() ? Auth::user()->email : $request->guest_email;
    $userId = Auth::check() ? Auth::id() : null;

    // Save to database (Ensure user_id is NULLABLE in your migration)
    SupportTicket::create([
        'user_id' => $userId, 
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

        $details = [
            'name' => $name,
            'email' => $email,
            'subject' => $request->subject,
            'customerMessage' => $request->message, 
        ];

        // Send email notification to Admin
        Mail::send('emails.support_request', $details, function($message) use ($details) {
            $message->to('admin@mikscoffee.com')->subject('New Support Req: ' . $details['subject']);
        });

        return back()->with('success', 'Request sent! We will contact you soon.');
    }

    // FOR ADMIN: List all tickets (Still requires Admin auth via route middleware)
    public function adminIndex() {
        $tickets = SupportTicket::with('user')->latest()->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    // FOR ADMIN: Resolve a ticket
    public function resolve($id) {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'resolved']);
        return back()->with('success', 'Ticket marked as resolved.');
    }
}