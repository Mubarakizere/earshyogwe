<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MemberTransfer;
use App\Notifications\Traits\MultiChannelNotification;

class MemberTransferApproved extends Notification implements ShouldQueue
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
        $approver = $this->transfer->approvedBy->name ?? 'Unknown';

        return (new MailMessage)
            ->subject('✅ Member Transfer Approved')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your member transfer request has been approved!')
            ->line('')
            ->line('**📋 Transfer Details:**')
            ->line('• **Member:** ' . $memberName)
            ->line('• **From Parish:** ' . $fromChurch)
            ->line('• **To Parish:** ' . $toChurch)
            ->line('• **Approved by:** ' . $approver)
            ->line('• **Approved on:** ' . $this->transfer->approved_at->format('M d, Y H:i'))
            ->action('View Transfer', route('member-transfers.show', $this->transfer))
            ->line('')
            ->line('The member has been officially transferred to the new parish.')
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
            'title' => '✅ Member Transfer Approved',
            'body' => $memberName . ' has been transferred to ' . $toChurch,
            'url' => route('member-transfers.show', $this->transfer),
            'data' => [
                'type' => 'member_transfer_approved',
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
            'message' => '✅ Member transfer approved: ' . $memberName . ' has been transferred to ' . $toChurch . '.',
            'action_url' => route('member-transfers.show', $this->transfer),
            'created_at' => now()->toISOString(),
            'icon' => 'check-circle',
            'category' => 'members',
        ];
    }
}
