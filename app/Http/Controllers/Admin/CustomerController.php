<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // 1. List all customers (Enhanced with Search & Pagination)
    public function index(Request $request)
    {
        $query = User::where('usertype', 'user')
                    ->withCount('orders')
                    ->latest();

        // Admin Customer Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Optimized for performance with Pagination
        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    // 2. Show Customer Details, Order History, Streak Progress, and Point Ledger
    public function show($id)
    {
        $customer = User::with(['orders' => function($query) {
            $query->latest();
        }, 'orders.items', 'pointTransactions' => function($query) {
            $query->latest();
        }, 'referrals'])->findOrFail($id);

        // Calculate milestones for streak tracking
        $streakCount = $customer->streak_count ?? 0;
        $nextMilestone = (floor($streakCount / 3) + 1) * 3;
        $streakProgress = (($streakCount % 3) / 3) * 100;

        return view('admin.customers.show', compact('customer', 'streakCount', 'nextMilestone', 'streakProgress'));
    }

    // 3. Reset Password
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $customer = User::findOrFail($id);
        
        $customer->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password has been changed securely.');
    }
}