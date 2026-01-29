<?php

namespace App\Notifications\Traits;

/**
 * Trait for multi-channel notification delivery.
 * Dynamically selects channels based on user preferences.
 */
trait MultiChannelNotification
{
    /**
     * Get the notification category for preference checking.
     * Override this in your notification class.
     */
    protected function getNotificationCategory(): string
    {
        return 'general';
    }

    /**
     * Determine which channels to deliver notification through.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database']; // Always store in database

        // Check if notifiable has notification preferences
        if (method_exists($notifiable, 'wantsEmailNotification')) {
            if ($notifiable->wantsEmailNotification($this->getNotificationCategory())) {
                $channels[] = 'mail';
            }
        }

        // Push notifications via OneSignal custom channel
        if (method_exists($notifiable, 'wantsPushNotification')) {
            if ($notifiable->wantsPushNotification($this->getNotificationCategory())) {
                $channels[] = \App\NotificationChannels\OneSignalChannel::class;
            }
        }

        return $channels;
    }

    /**
     * Get default mail message structure.
     */
    protected function getBaseMailMessage(): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->mailer('smtp')
            ->subject(config('app.name') . ' - Notification');
    }
}
