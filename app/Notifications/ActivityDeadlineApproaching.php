<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityDeadlineApproaching extends Notification
{
    use Queueable;

    public $activity;
    public $type; // 'start' or 'end'
    public $daysLeft;

    public function __construct(Activity $activity, string $type, int $daysLeft)
    {
        $this->activity = $activity;
        $this->type = $type;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database']; // Add 'mail' here if email is configured
    }

    public function toArray($notifiable)
    {
        $action = $this->type === 'start' ? 'starts' : 'ends';
        $message = "Activity '{$this->activity->name}' {$action} in {$this->daysLeft} days.";

        return [
            'activity_id' => $this->activity->id,
            'message' => $message,
            'type' => 'deadline_' . $this->type,
            'action_url' => route('activities.show', $this->activity),
        ];
    }
}
