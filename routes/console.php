<?php

use Illuminate\Support\Facades\Schedule;

// 1. Daily Report (Sent every night at 11:59 PM)
Schedule::command('report:send daily')
    ->dailyAt('23:59')
    ->name('daily-sales-report');

// 2. Weekly Report (Sent every Sunday at 11:59 PM)
Schedule::command('report:send weekly')
    ->weeklyOn(0, '23:59') // 0 = Sunday
    ->name('weekly-sales-report');

// 3. Monthly Report (Sent on the 1st of every month at 8:00 AM)
Schedule::command('report:send monthly')
    ->monthlyOn(1, '08:00')
    ->name('monthly-sales-report');

// 4. Yearly Report (Sent on Jan 1st at 8:00 AM)
Schedule::command('report:send yearly')
    ->yearlyOn(1, 1, '08:00')
    ->name('yearly-sales-report');

    Illuminate\Support\Facades\Schedule::command('report:send daily')->everyMinute();