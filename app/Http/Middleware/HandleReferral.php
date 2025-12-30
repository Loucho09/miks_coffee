<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleReferral
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref')) {
            // Use put to ensure it's stored in the current session
            session()->put('referrer_code', $request->query('ref'));
        }

        return $next($request);
    }
}