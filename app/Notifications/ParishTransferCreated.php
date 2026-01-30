<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ParishTransfer;
use App\Notifications\Traits\MultiChannelNotification;

class ParishTransferCreated extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $transfer;

    /**
     * Create a new notification instance.
     */
    public function __construct(ParishTransfer $transfer)
    {
        $this->transfer = $transfer;
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
            ->subject('💸 New Parish Transfer Pending Verification')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new parish transfer has been submitted and requires your verification.')
            ->line('')
            ->line('**💰 Transfer Details:**')
            ->line('• **Amount:** ' . number_format($this->transfer->amount) . ' RWF')
            ->line('• **From Parish:** ' . ($this->transfer->church->name ?? 'Unknown'))
            ->line('• **Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line('• **Submitted by:** ' . ($this->transfer->enteredBy->name ?? 'Unknown'))
            ->line('• **Reference:** ' . ($this->transfer->reference ?? 'N/A'))
            ->action('Review Transfer', route('parish-transfers.show', $this->transfer))
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
            'title' => '💸 New Parish Transfer',
            'body' => number_format($this->transfer->amount) . ' RWF from ' . ($this->transfer->church->name ?? 'Unknown') . ' needs verification',
            'url' => route('parish-transfers.show', $this->transfer),
            'data' => [
                'type' => 'parish_transfer_created',
                'transfer_id' => $this->transfer->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'amount' => $this->transfer->amount,
            'church_name' => $this->transfer->church->name ?? 'Unknown',
            'message' => '💸 New transfer of ' . number_format($this->transfer->amount) . ' RWF from ' . ($this->transfer->church->name ?? 'Unknown') . ' is pending verification.',
            'action_url' => route('parish-transfers.show', $this->transfer),
            'created_at' => now()->toISOString(),
            'icon' => 'banknotes',
            'category' => 'diocese',
        ];
    }
}
