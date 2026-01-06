<?php

namespace App\Notifications;

use App\Models\Expense; // Assuming Expense model exists
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseSubmitted extends Notification
{

    public $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Keeping it to database for now to avoid mail config issues, add 'mail' later if needed
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('A new expense has been submitted for approval.')
                    ->line('Description: ' . $this->expense->description)
                    ->line('Amount: ' . number_format($this->expense->amount))
                    ->action('Review Expense', route('expenses.show', $this->expense->id))
                    ->line('Thank you for using our application!');
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
            'amount' => $this->expense->amount,
            'submitter_name' => $this->expense->enteredBy->name ?? 'Unknown',
            'message' => 'New expense submitted by ' . ($this->expense->enteredBy->name ?? 'Unknown') . ': ' . $this->expense->description,
            'action_url' => route('expenses.show', $this->expense->id),
        ];
    }
}
