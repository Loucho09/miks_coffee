<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalesReport;
use Carbon\Carbon;

class SendSalesReport extends Command
{
    /**
     * The signature allows you to run: php artisan report:send daily
     * Options: daily, weekly, monthly, yearly
     */
    protected $signature = 'report:send {period}';

    protected $description = 'Send a sales summary report for a specific period';

    public function handle()
    {
        $period = $this->argument('period');
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        // 1. Determine the Date Range based on the argument
        switch ($period) {
            case 'daily':
                // "Today"
                $startDate = Carbon::today(); 
                $endDate = Carbon::now();
                break;
            case 'weekly':
                // Last 7 days
                $startDate = Carbon::now()->subWeek();
                break;
            case 'monthly':
                // Last 30 days (or use startOfMonth() for calendar month)
                $startDate = Carbon::now()->subMonth();
                break;
            case 'yearly':
                // Last 365 days
                $startDate = Carbon::now()->subYear();
                break;
            default:
                $this->error("Invalid period. Use: daily, weekly, monthly, or yearly.");
                return;
        }

        // 2. Calculate Stats
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalSales = $orders->sum('total_price');
        $totalOrders = $orders->count();

        // 3. Send Email
        // Note: Change to your actual admin email
        $adminEmail = 'jmloucho09@gmail.com'; 

        try {
            Mail::to($adminEmail)->send(new SalesReport(ucfirst($period), $totalSales, $totalOrders));
            $this->info("{$period} report sent successfully to {$adminEmail}!");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }
    }
}