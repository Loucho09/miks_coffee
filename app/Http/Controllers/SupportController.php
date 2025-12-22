<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket; // 🟢 ADD THIS

class SupportController extends Controller
{
    // For Customers: Show form
    public function index() {
        return view('support.index');
    }

    // For Customers: Submit form
    public function send(Request $request) {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        // 🟢 SAVE TO DATABASE
        SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        $details = [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => $request->subject,
            'customerMessage' => $request->message, 
        ];

        Mail::send('emails.support_request', $details, function($message) use ($details) {
            $message->to('admin@mikscoffee.com')->subject('New Support Req: ' . $details['subject']);
        });

        return back()->with('success', 'Request sent! We will contact you soon.');
    }

    // 🟢 FOR ADMIN: List all tickets
    public function adminIndex() {
        $tickets = SupportTicket::with('user')->latest()->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    // 🟢 FOR ADMIN: Resolve a ticket
    public function resolve($id) {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'resolved']);
        return back()->with('success', 'Ticket marked as resolved.');
    }
}