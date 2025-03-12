<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $lowStockProducts;

    public function __construct($lowStockProducts)
    {
        $this->lowStockProducts = $lowStockProducts;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Alert: Low Stock Products')
            ->line('The following products are running low on stock:');

        foreach ($this->lowStockProducts as $product) {
            $message->line("- {$product['name']} (Stock: {$product['stock']})");
        }

        return $message
            ->line('Please review and restock these products as needed.')
            ->action('View Dashboard', url('/admin/dashboard'));
    }
} 