<?php

namespace App\Notifications;

use App\Models\Expense;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseSubmitted extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'expenses';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🧾 New Expense Submitted for Approval')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new expense has been submitted and requires your attention.')
            ->line('')
            ->line('**📋 Expense Details:**')
            ->line('• **Description:** ' . $this->expense->description)
            ->line('• **Amount:** ' . number_format($this->expense->amount) . ' RWF')
            ->line('• **Submitted by:** ' . ($this->expense->enteredBy->name ?? 'Unknown'))
            ->line('• **Date:** ' . $this->expense->expense_date?->format('M d, Y'))
            ->action('Review Expense', route('expenses.show', $this->expense->id))
            ->line('')
            ->line('Please review and approve or reject this expense request.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => '🧾 New Expense Request',
            'body' => 'New expense (' . number_format($this->expense->amount) . ' RWF) submitted by ' . ($this->expense->enteredBy->name ?? 'Unknown'),
            'url' => route('expenses.show', $this->expense->id),
            'data' => [
                'type' => 'expense_submitted',
                'expense_id' => $this->expense->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'expense_id' => $this->expense->id,
            'amount' => $this->expense->amount,
            'submitter_name' => $this->expense->enteredBy->name ?? 'Unknown',
            'message' => '🧾 New expense submitted by ' . ($this->expense->enteredBy->name ?? 'Unknown') . ': ' . $this->expense->description,
            'action_url' => route('expenses.show', $this->expense->id),
            'icon' => 'receipt',
            'category' => 'expenses',
        ];
    }
}
