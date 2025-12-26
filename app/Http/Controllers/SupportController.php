<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\SupportReply;

class SupportController extends Controller
{
    // For Customers: Show form (Accessible to all)
    public function index() {
        // Fix: Removed code that expected $tickets variable for customers
        return view('support.index');
    }

    // For Customers: Submit form
    public function send(Request $request) {
        if (!Auth::check()) {
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
                'subject' => 'required',
                'message' => 'required',
            ]);
        }

        $userId = Auth::check() ? Auth::id() : null;

        $ticket = SupportTicket::create([
            'user_id' => $userId, 
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request sent! We will contact you soon.');
    }

    // FOR ADMIN: List all tickets
    public function adminIndex(Request $request) {
        $query = SupportTicket::with(['user', 'replies.user'])->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    // FOR ADMIN: Resolve a ticket
    public function resolve($id) {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'resolved']);
        return back()->with('success', 'Ticket marked as resolved.');
    }

    // FOR ADMIN: Reply to Ticket
    public function reply(Request $request, SupportTicket $ticket) {
        $request->validate(['message' => 'required|string|min:5']);

        SupportReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'replied']);

        $customerEmail = $ticket->user ? $ticket->user->email : $ticket->guest_email;
        $customerName = $ticket->user ? $ticket->user->name : $ticket->guest_name;

        if ($customerEmail) {
            $details = [
                'name' => $customerName,
                'replyMessage' => $request->message,
                'ticketSubject' => $ticket->subject,
            ];

            // Ensure the view emails.support_reply exists to avoid 500 errors
            try {
                Mail::send('emails.support_reply', $details, function($message) use ($customerEmail, $ticket) {
                    $message->to($customerEmail)->subject('Re: ' . $ticket->subject);
                });
            } catch (\Exception $e) {
                // Email failed but logic continues
            }
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}