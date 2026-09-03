<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Payment Received — {$this->payment->payment_reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("We've received your payment. Thank you!")
            ->line("Reference: {$this->payment->payment_reference}")
            ->line("Amount: ₱" . number_format($this->payment->amount, 2))
            ->line("Method: " . ucfirst($this->payment->method))
            ->action('View Receipt', route('customer.bills.show', $this->payment->bill_id))
            ->line('This is your official payment confirmation.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_received',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'message' => "Payment of ₱" . number_format($this->payment->amount, 2) . " received",
        ];
    }
}
