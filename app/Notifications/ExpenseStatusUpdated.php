<?php

namespace App\Notifications;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseStatusUpdated extends Notification
{

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
                    ->line('Your expense request has been ' . $this->status . '.')
                    ->line('Description: ' . $this->expense->description)
                    ->action('View Expense', route('expenses.show', $this->expense->id))
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'expense_id' => $this->expense->id,
            'status' => $this->status,
            'message' => 'Your expense "' . $this->expense->description . '" has been ' . $this->status . '.',
            'action_url' => route('expenses.show', $this->expense->id),
        ];
    }
}
