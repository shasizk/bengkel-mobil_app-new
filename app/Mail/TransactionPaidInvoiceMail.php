<?php

namespace App\Mail;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionPaidInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
        $this->transaction->loadMissing([
            'booking.user',
            'booking.vehicle',
            'booking.services.service',
            'mekanik',
            'kasir',
            'details.sparepart',
            'payment',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembayaran Lunas #' . $this->transaction->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction-paid-invoice',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn (): string => Pdf::loadView('transactions.pdf', ['transaction' => $this->transaction])->output(),
                'invoice-' . $this->transaction->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
