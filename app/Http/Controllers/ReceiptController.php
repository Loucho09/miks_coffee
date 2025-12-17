<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download($id)
    {
        // 1. Find the order safely
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 2. Generate PDF
        $pdf = Pdf::loadView('pdf.receipt', compact('order'));

        // 3. Download
        return $pdf->download('Miks-Receipt-#' . $order->id . '.pdf');
    }
}