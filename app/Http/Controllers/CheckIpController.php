<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckIpController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'detected_ip' => $request->ip(),
            'x_forwarded_for' => $request->header('X-Forwarded-For'),
            'is_from_trusted_proxy' => $request->isFromTrustedProxy(),
        ]);
    }
}