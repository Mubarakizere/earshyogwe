<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Giving;
use App\Notifications\Traits\MultiChannelNotification;

class DioceseReceiptVerified extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $giving;

    /**
     * Create a new notification instance.
     */
    public function __construct(Giving $giving)
    {
        $this->giving = $giving;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'diocese';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Diocese Receipt Verified')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! The Diocese has verified receipt of your transfer.')
            ->line('')
            ->line('**💰 Transfer Details:**')
            ->line('• **Amount:** ' . number_format($this->giving->amount) . ' RWF')
            ->line('• **Type:** ' . $this->giving->givingType->name)
            ->line('• **Church:** ' . $this->giving->church->name)
            ->line('• **Verified at:** ' . now()->format('M d, Y H:i'))
            ->action('View Details', route('givings.show', $this->giving))
            ->line('')
            ->line('Thank you for your faithful contribution!')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => '✅ Diocese Receipt Verified',
            'body' => 'Transfer of ' . number_format($this->giving->amount) . ' RWF for ' . $this->giving->givingType->name . ' has been verified',
            'url' => route('givings.show', $this->giving),
            'data' => [
                'type' => 'diocese_receipt_verified',
                'giving_id' => $this->giving->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'giving_id' => $this->giving->id,
            'amount' => $this->giving->amount,
            'church_name' => $this->giving->church->name,
            'message' => '✅ Diocese has verified receipt of ' . number_format($this->giving->amount) . ' RWF for ' . $this->giving->givingType->name,
            'action_url' => route('givings.show', $this->giving),
            'verified_at' => now()->toISOString(),
            'icon' => 'check-circle',
            'category' => 'diocese',
        ];
    }
}
