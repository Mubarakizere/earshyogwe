<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Giving;

class DioceseTransferSent extends Notification
{
    use Queueable;

    public $giving;

    /**
     * Create a new notification instance.
     */
    public function __construct(Giving $giving)
    {
        $this->giving = $giving;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'giving_id' => $this->giving->id,
            'amount' => $this->giving->amount,
            'church_name' => $this->giving->church->name,
            'message' => 'New transfer of ' . number_format($this->giving->amount) . ' RWF from ' . $this->giving->church->name . ' is ready for verification.',
            'action_url' => route('givings.index'),
            'sent_at' => now(),
        ];
    }
}
