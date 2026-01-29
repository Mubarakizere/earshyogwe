<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityDeadlineApproaching extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $activity;
    public $type; // 'start' or 'end'
    public $daysLeft;

    public function __construct(Activity $activity, string $type, int $daysLeft)
    {
        $this->activity = $activity;
        $this->type = $type;
        $this->daysLeft = $daysLeft;
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
        $action = $this->type === 'start' ? 'starts' : 'ends';
        $emoji = $this->daysLeft <= 1 ? '🚨' : ($this->daysLeft <= 3 ? '⚠️' : '⏰');
        
        return (new MailMessage)
            ->subject($emoji . ' Activity ' . ucfirst($action) . ' in ' . $this->daysLeft . ' Days')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('An activity deadline is approaching.')
            ->line('')
            ->line('**📅 Activity Details:**')
            ->line('• **Name:** ' . $this->activity->name)
            ->line('• **' . ucfirst($action) . ' in:** ' . $this->daysLeft . ' day(s)')
            ->action('View Activity', route('activities.show', $this->activity))
            ->line('')
            ->line('Please take necessary action before the deadline.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $action = $this->type === 'start' ? 'starts' : 'ends';
        $emoji = $this->daysLeft <= 1 ? '🚨' : ($this->daysLeft <= 3 ? '⚠️' : '⏰');
        
        return [
            'title' => $emoji . ' Activity Deadline',
            'body' => "'{$this->activity->name}' {$action} in {$this->daysLeft} day(s)",
            'url' => route('activities.show', $this->activity),
            'data' => [
                'type' => 'activity_deadline',
                'activity_id' => $this->activity->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $action = $this->type === 'start' ? 'starts' : 'ends';
        $emoji = $this->daysLeft <= 1 ? '🚨' : ($this->daysLeft <= 3 ? '⚠️' : '⏰');

        return [
            'activity_id' => $this->activity->id,
            'message' => $emoji . " Activity '{$this->activity->name}' {$action} in {$this->daysLeft} days.",
            'type' => 'deadline_' . $this->type,
            'action_url' => route('activities.show', $this->activity),
            'icon' => 'calendar',
            'category' => 'activities',
        ];
    }
}
