<?php

namespace App\Notifications;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Bill $bill)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Bill: {$this->bill->bill_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new bill has been generated for your waste collection service.")
            ->line("Bill Number: {$this->bill->bill_number}")
            ->line("Total Amount: ₱" . number_format($this->bill->total_amount, 2))
            ->line("Due Date: {$this->bill->due_date->format('M d, Y')}")
            ->action('View Bill', route('customer.bills.show', $this->bill))
            ->line('Thank you for using our service!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'bill_generated',
            'bill_id' => $this->bill->id,
            'bill_number' => $this->bill->bill_number,
            'total_amount' => $this->bill->total_amount,
            'message' => "New bill {$this->bill->bill_number} for ₱" . number_format($this->bill->total_amount, 2),
        ];
    }
}
