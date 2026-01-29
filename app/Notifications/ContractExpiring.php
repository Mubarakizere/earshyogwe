<?php

namespace App\Notifications;

use App\Models\Contract;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiring extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $contract;
    public $daysLeft;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract, int $daysLeft)
    {
        $this->contract = $contract;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'contracts';
    }

    /**
     * Get urgency emoji based on days left.
     */
    protected function getUrgencyEmoji(): string
    {
        return match (true) {
            $this->daysLeft <= 3 => '🚨',
            $this->daysLeft <= 7 => '⚠️',
            $this->daysLeft <= 14 => '📋',
            default => '📝',
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $emoji = $this->getUrgencyEmoji();
        
        return (new MailMessage)
            ->subject($emoji . ' Contract Expiring in ' . $this->daysLeft . ' Days')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A contract is expiring soon and may require renewal.')
            ->line('')
            ->line('**📋 Contract Details:**')
            ->line('• **Worker:** ' . $this->contract->worker->full_name)
            ->line('• **Expires in:** ' . $this->daysLeft . ' day(s)')
            ->line('• **End Date:** ' . $this->contract->end_date->format('M d, Y'))
            ->action('View Contract', route('workers.show', $this->contract->worker_id))
            ->line('')
            ->line('Please review the contract for renewal or other action.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $emoji = $this->getUrgencyEmoji();
        
        return [
            'title' => $emoji . ' Contract Expiring Soon',
            'body' => 'Contract for ' . $this->contract->worker->full_name . ' expires in ' . $this->daysLeft . ' days',
            'url' => route('workers.show', $this->contract->worker_id),
            'data' => [
                'type' => 'contract_expiring',
                'contract_id' => $this->contract->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $emoji = $this->getUrgencyEmoji();
        
        return [
            'contract_id' => $this->contract->id,
            'worker_id' => $this->contract->worker_id,
            'days_left' => $this->daysLeft,
            'message' => $emoji . ' Contract for ' . $this->contract->worker->full_name . ' expires in ' . $this->daysLeft . ' days.',
            'action_url' => route('workers.show', $this->contract->worker_id),
            'icon' => 'document',
            'category' => 'contracts',
        ];
    }
}
