<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Giving;
use App\Notifications\Traits\MultiChannelNotification;

class DioceseTransferSent extends Notification implements ShouldQueue
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
            ->subject('💸 New Transfer Ready for Verification')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new transfer has been sent and is ready for your verification.')
            ->line('')
            ->line('**💰 Transfer Details:**')
            ->line('• **Amount:** ' . number_format($this->giving->amount) . ' RWF')
            ->line('• **From Church:** ' . $this->giving->church->name)
            ->line('• **Type:** ' . $this->giving->givingType->name)
            ->line('• **Sent at:** ' . now()->format('M d, Y H:i'))
            ->action('Verify Transfer', route('diocese.transfers.index'))
            ->line('')
            ->line('Please review and verify this transfer.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => '💸 New Transfer to Verify',
            'body' => 'Transfer of ' . number_format($this->giving->amount) . ' RWF from ' . $this->giving->church->name,
            'url' => route('diocese.transfers.index'),
            'data' => [
                'type' => 'diocese_transfer_sent',
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
            'message' => '💸 New transfer of ' . number_format($this->giving->amount) . ' RWF from ' . $this->giving->church->name . ' is ready for verification.',
            'action_url' => route('diocese.transfers.index'),
            'sent_at' => now()->toISOString(),
            'icon' => 'banknotes',
            'category' => 'diocese',
        ];
    }
}
