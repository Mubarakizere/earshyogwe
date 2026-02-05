<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MemberTransfer;
use App\Notifications\Traits\MultiChannelNotification;

class MemberTransferCreated extends Notification implements ShouldQueue
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
        $initiator = $this->transfer->initiatedBy->name ?? 'Unknown';

        return (new MailMessage)
            ->subject('👥 New Member Transfer Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new member transfer request has been submitted and requires your approval.')
            ->line('')
            ->line('**📋 Transfer Details:**')
            ->line('• **Member:** ' . $memberName)
            ->line('• **From Parish:** ' . $fromChurch)
            ->line('• **To Parish:** ' . $toChurch)
            ->line('• **Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line('• **Requested by:** ' . $initiator)
            ->line('• **Reason:** ' . ($this->transfer->reason ?? 'N/A'))
            ->action('Review Transfer', route('member-transfers.show', $this->transfer))
            ->line('')
            ->line('Please review and approve or reject this transfer request.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $memberName = $this->transfer->member->name ?? 'Unknown Member';
        $fromChurch = $this->transfer->fromChurch->name ?? 'Unknown Parish';

        return [
            'title' => '👥 New Member Transfer Request',
            'body' => $memberName . ' transfer from ' . $fromChurch . ' needs your approval',
            'url' => route('member-transfers.show', $this->transfer),
            'data' => [
                'type' => 'member_transfer_created',
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
        $fromChurch = $this->transfer->fromChurch->name ?? 'Unknown Parish';

        return [
            'transfer_id' => $this->transfer->id,
            'member_name' => $memberName,
            'from_church' => $fromChurch,
            'message' => '👥 New member transfer request: ' . $memberName . ' from ' . $fromChurch . ' is pending approval.',
            'action_url' => route('member-transfers.show', $this->transfer),
            'created_at' => now()->toISOString(),
            'icon' => 'users',
            'category' => 'members',
        ];
    }
}
