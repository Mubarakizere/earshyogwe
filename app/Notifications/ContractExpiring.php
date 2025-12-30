<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiring extends Notification implements ShouldQueue
{
    use Queueable;

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
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Contract expiring soon!')
                    ->line('Worker: ' . $this->contract->worker->full_name)
                    ->line('Expires in: ' . $this->daysLeft . ' days (' . $this->contract->end_date->format('M d, Y') . ')')
                    ->action('View Contract', route('workers.show', $this->contract->worker_id))
                    ->line('Please review for renewal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'worker_id' => $this->contract->worker_id,
            'days_left' => $this->daysLeft,
            'message' => 'Contract for ' . $this->contract->worker->full_name . ' expires in ' . $this->daysLeft . ' days.',
            'action_url' => route('workers.show', $this->contract->worker_id),
        ];
    }
}
