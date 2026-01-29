<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityProgressReminder extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $activity;
    public $daysSinceLastLog;

    public function __construct(Activity $activity, int $daysSinceLastLog)
    {
        $this->activity = $activity;
        $this->daysSinceLastLog = $daysSinceLastLog;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'activities';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frequency = ucfirst($this->activity->tracking_frequency);
        
        return (new MailMessage)
            ->subject('📊 Progress Report Reminder - ' . $this->activity->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A progress report is due for one of your activities.')
            ->line('')
            ->line('**📋 Activity Details:**')
            ->line('• **Name:** ' . $this->activity->name)
            ->line('• **Tracking Frequency:** ' . $frequency)
            ->line('• **Days Since Last Update:** ' . $this->daysSinceLastLog)
            ->action('Log Progress', route('activities.show', $this->activity))
            ->line('')
            ->line('Please provide an update on this activity\'s progress.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $frequency = ucfirst($this->activity->tracking_frequency);
        
        return [
            'title' => '📊 Progress Report Due',
            'body' => "No updates for '{$this->activity->name}' in {$this->daysSinceLastLog} days ({$frequency} tracking)",
            'url' => route('activities.show', $this->activity),
            'data' => [
                'type' => 'progress_reminder',
                'activity_id' => $this->activity->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $frequency = ucfirst($this->activity->tracking_frequency);
        
        return [
            'activity_id' => $this->activity->id,
            'message' => "📊 Progress report due for '{$this->activity->name}'. No updates in {$this->daysSinceLastLog} days ({$frequency} tracking).",
            'type' => 'progress_reminder',
            'action_url' => route('activities.show', $this->activity),
            'icon' => 'chart',
            'category' => 'activities',
        ];
    }
}
