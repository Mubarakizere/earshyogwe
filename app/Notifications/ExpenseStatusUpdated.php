<?php

namespace App\Notifications;

use App\Models\Expense;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $expense;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense, string $status)
    {
        $this->expense = $expense;
        $this->status = $status;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'expenses';
    }

    /**
     * Get status emoji and color.
     */
    protected function getStatusInfo(): array
    {
        return match ($this->status) {
            'approved' => ['emoji' => '✅', 'action' => 'approved', 'color' => 'green'],
            'rejected' => ['emoji' => '❌', 'action' => 'rejected', 'color' => 'red'],
            'pending' => ['emoji' => '⏳', 'action' => 'set to pending', 'color' => 'yellow'],
            default => ['emoji' => '📋', 'action' => $this->status, 'color' => 'gray'],
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $info = $this->getStatusInfo();
        
        return (new MailMessage)
            ->subject($info['emoji'] . ' Expense ' . ucfirst($this->status))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your expense request status has been updated.')
            ->line('')
            ->line('**📋 Expense Details:**')
            ->line('• **Description:** ' . $this->expense->description)
            ->line('• **Amount:** ' . number_format($this->expense->amount) . ' RWF')
            ->line('• **Status:** ' . $info['emoji'] . ' ' . ucfirst($this->status))
            ->action('View Expense', route('expenses.show', $this->expense->id))
            ->line('')
            ->line('Thank you for using ' . config('app.name') . '!')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $info = $this->getStatusInfo();
        
        return [
            'title' => $info['emoji'] . ' Expense ' . ucfirst($this->status),
            'body' => 'Your expense "' . $this->expense->description . '" has been ' . $this->status,
            'url' => route('expenses.show', $this->expense->id),
            'data' => [
                'type' => 'expense_status_updated',
                'expense_id' => $this->expense->id,
                'status' => $this->status,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $info = $this->getStatusInfo();
        
        return [
            'expense_id' => $this->expense->id,
            'status' => $this->status,
            'message' => $info['emoji'] . ' Your expense "' . $this->expense->description . '" has been ' . $this->status . '.',
            'action_url' => route('expenses.show', $this->expense->id),
            'icon' => 'receipt',
            'category' => 'expenses',
        ];
    }
}
