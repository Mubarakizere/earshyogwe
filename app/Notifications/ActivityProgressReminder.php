<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityProgressReminder extends Notification
{
    use Queueable;

    public $activity;
    public $daysSinceLastLog;

    public function __construct(Activity $activity, int $daysSinceLastLog)
    {
        $this->activity = $activity;
        $this->daysSinceLastLog = $daysSinceLastLog;
    }

    public function via($notifiable)
    {
        return ['database']; // Add 'mail' here later
    }

    public function toArray($notifiable)
    {
        $frequency = ucfirst($this->activity->tracking_frequency);
        $message = "Progress report due for '{$this->activity->name}'. No updates in {$this->daysSinceLastLog} days ({$frequency} tracking).";

        return [
            'activity_id' => $this->activity->id,
            'message' => $message,
            'type' => 'progress_reminder',
            'action_url' => route('activities.show', $this->activity),
        ];
    }
}
