<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ParishTransfer;
use App\Notifications\Traits\MultiChannelNotification;

class ParishTransferRejected extends Notification implements ShouldQueue
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
            ->subject('❌ Parish Transfer Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your parish transfer has been rejected.')
            ->line('')
            ->line('**💰 Transfer Details:**')
            ->line('• **Amount:** ' . number_format($this->transfer->amount) . ' RWF')
            ->line('• **From Parish:** ' . ($this->transfer->church->name ?? 'Unknown'))
            ->line('• **Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line('• **Rejected by:** ' . ($this->transfer->verifiedBy->name ?? 'Unknown'))
            ->line('• **Rejected at:** ' . $this->transfer->verified_at->format('M d, Y H:i'))
            ->action('View Transfer', route('parish-transfers.show', $this->transfer))
            ->line('')
            ->line('Please contact the finance office for more information.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => '❌ Transfer Rejected',
            'body' => 'Your transfer of ' . number_format($this->transfer->amount) . ' RWF has been rejected',
            'url' => route('parish-transfers.show', $this->transfer),
            'data' => [
                'type' => 'parish_transfer_rejected',
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
            'message' => '❌ Transfer of ' . number_format($this->transfer->amount) . ' RWF from ' . ($this->transfer->church->name ?? 'Unknown') . ' has been rejected.',
            'action_url' => route('parish-transfers.show', $this->transfer),
            'rejected_at' => $this->transfer->verified_at->toISOString(),
            'icon' => 'x-circle',
            'category' => 'diocese',
        ];
    }
}
