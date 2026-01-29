<?php

namespace App\Notifications;

use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityStatusChanged extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $activity;
    public $message;

    public function __construct($activity, $message)
    {
        $this->activity = $activity;
        $this->message = $message;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'activities';
    }

    /**
     * Get status emoji based on message content.
     */
    protected function getStatusEmoji(): string
    {
        $message = strtolower($this->message);
        if (str_contains($message, 'complete')) return '✅';
        if (str_contains($message, 'progress') || str_contains($message, 'started')) return '🚀';
        if (str_contains($message, 'paused') || str_contains($message, 'hold')) return '⏸️';
        if (str_contains($message, 'cancel')) return '❌';
        return '📋';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $emoji = $this->getStatusEmoji();
        
        return (new MailMessage)
            ->subject($emoji . ' Activity Status Update - ' . $this->activity->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('An activity status has been updated.')
            ->line('')
            ->line('**📋 Activity Details:**')
            ->line('• **Name:** ' . $this->activity->name)
            ->line('• **Update:** ' . $this->message)
            ->action('View Activity', route('activities.show', $this->activity->id))
            ->line('')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        $emoji = $this->getStatusEmoji();
        
        return [
            'title' => $emoji . ' Activity Update',
            'body' => $this->message,
            'url' => route('activities.show', $this->activity->id),
            'data' => [
                'type' => 'activity_status_changed',
                'activity_id' => $this->activity->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $emoji = $this->getStatusEmoji();
        
        return [
            'message' => $emoji . ' ' . $this->message,
            'activity_id' => $this->activity->id,
            'activity_name' => $this->activity->name,
            'action_url' => route('activities.show', $this->activity->id),
            'icon' => 'activity',
            'category' => 'activities',
        ];
    }
}
