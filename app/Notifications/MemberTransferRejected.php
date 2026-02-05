<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MemberTransfer;
use App\Notifications\Traits\MultiChannelNotification;

class MemberTransferRejected extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $transfer;

    /**
     * Create a new notification instance.
     */
    public function __construct(MemberTransfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'members';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $memberName = $this->transfer->member->name ?? 'Unknown Member';
        $fromChurch = $this->transfer->fromChurch->name ?? 'Unknown Parish';
        $toChurch = $this->transfer->toChurch->name ?? 'Unknown Parish';

        return (new MailMessage)
            ->subject('❌ Member Transfer Rejected')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your member transfer request has been rejected.')
            ->line('')
            ->line('**📋 Transfer Details:**')
            ->line('• **Member:** ' . $memberName)
            ->line('• **From Parish:** ' . $fromChurch)
            ->line('• **To Parish:** ' . $toChurch)
            ->line('• **Rejection Reason:** ' . ($this->transfer->rejection_reason ?? 'No reason provided'))
            ->action('View Transfer', route('member-transfers.show', $this->transfer))
            ->line('')
            ->line('Please contact the destination parish for more information.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $memberName = $this->transfer->member->name ?? 'Unknown Member';
        $toChurch = $this->transfer->toChurch->name ?? 'Unknown Parish';

        return [
            'title' => '❌ Member Transfer Rejected',
            'body' => $memberName . ' transfer to ' . $toChurch . ' was rejected',
            'url' => route('member-transfers.show', $this->transfer),
            'data' => [
                'type' => 'member_transfer_rejected',
                'transfer_id' => $this->transfer->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $memberName = $this->transfer->member->name ?? 'Unknown Member';
        $toChurch = $this->transfer->toChurch->name ?? 'Unknown Parish';

        return [
            'transfer_id' => $this->transfer->id,
            'member_name' => $memberName,
            'to_church' => $toChurch,
            'message' => '❌ Member transfer rejected: ' . $memberName . ' transfer to ' . $toChurch . ' was not approved.',
            'action_url' => route('member-transfers.show', $this->transfer),
            'created_at' => now()->toISOString(),
            'icon' => 'x-circle',
            'category' => 'members',
        ];
    }
}
