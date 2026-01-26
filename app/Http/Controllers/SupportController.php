<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\SupportReply;

class SupportController extends Controller
{
    public function index() {
        // 🟢 Logic: When a customer visits the support/dashboard, mark their 'replied' tickets as 'resolved' (or seen)
        // This makes the notification dot disappear.
        if (Auth::check()) {
            SupportTicket::where('user_id', Auth::id())
                ->where('status', 'replied')
                ->update(['status' => 'resolved']);
        }

        return view('support.index');
    }

   public function getActiveTickets()
    {
        if (!Auth::check() || Auth::user()->usertype !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tickets = SupportTicket::with(['user', 'replies.user'])
            ->whereIn('status', ['pending', 'replied'])
            ->latest()
            ->get();

        return response()->json($tickets);
    }

    public function send(Request $request) {
        if (!Auth::check()) {
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
                'subject' => 'required',
                'message' => 'required',
            ]);
        }

        SupportTicket::create([
            'user_id' => Auth::id(), 
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request sent! We will contact you soon.');
    }

    public function adminIndex(Request $request) {
        $query = SupportTicket::with(['user', 'replies.user'])->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    public function resolve($id) {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'resolved']);
        return back()->with('success', 'Ticket marked as resolved.');
    }

    public function reply(Request $request, SupportTicket $ticket) {
        $request->validate(['message' => 'required|string|min:5']);

        SupportReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // 🟢 Logic: Status is changed to 'replied' so the Notification Dot appears for the user
        $ticket->update(['status' => 'replied']);

        $customerEmail = $ticket->user ? $ticket->user->email : $ticket->guest_email;
        if ($customerEmail) {
            try {
                Mail::send('emails.support_reply', [
                    'name' => $ticket->user ? $ticket->user->name : $ticket->guest_name,
                    'replyMessage' => $request->message,
                    'ticketSubject' => $ticket->subject,
                ], function($message) use ($customerEmail, $ticket) {
                    $message->to($customerEmail)->subject('Re: ' . $ticket->subject);
                });
            } catch (\Exception $e) { }
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}