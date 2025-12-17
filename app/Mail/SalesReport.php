<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesReport extends Mailable
{
    use Queueable, SerializesModels;

    public $period;
    public $totalSales;
    public $totalOrders;

    /**
     * Create a new message instance.
     * This accepts the data: ('Daily', 5000.00, 12)
     */
    public function __construct($period, $totalSales, $totalOrders)
    {
        $this->period = $period;
        $this->totalSales = $totalSales;
        $this->totalOrders = $totalOrders;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Subject line (e.g., "Daily Sales Report - Dec 12, 2025")
            subject: $this->period . ' Sales Report - ' . date('M d, Y'),
        );
    }

   public function content(): Content
    {
        return new Content(
            // This tells Laravel to look in resources/views/emails/sales_report.blade.php
            view: 'emails.sales_report', 
        );
    }

    public function attachments(): array
    {
        return [];
    }
}