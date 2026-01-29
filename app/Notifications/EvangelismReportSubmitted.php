<?php

namespace App\Notifications;

use App\Models\EvangelismReport;
use App\Notifications\Traits\MultiChannelNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvangelismReportSubmitted extends Notification implements ShouldQueue
{
    use Queueable, MultiChannelNotification;

    public $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(EvangelismReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification category for preference checking.
     */
    protected function getNotificationCategory(): string
    {
        return 'evangelism';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✝️ New Evangelism Report Submitted')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new evangelism report has been submitted for your review.')
            ->line('')
            ->line('**📋 Report Details:**')
            ->line('• **Church:** ' . $this->report->church->name)
            ->line('• **Submitted by:** ' . ($this->report->submitter->name ?? 'Unknown'))
            ->line('• **Submitted at:** ' . $this->report->created_at->format('M d, Y H:i'))
            ->action('View Report', route('evangelism-reports.show', $this->report))
            ->line('')
            ->line('Please review this evangelism report.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the OneSignal push notification representation.
     */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => '✝️ New Evangelism Report',
            'body' => 'Evangelism report from ' . $this->report->church->name,
            'url' => route('evangelism-reports.show', $this->report),
            'data' => [
                'type' => 'evangelism_report_submitted',
                'report_id' => $this->report->id,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'evangelism_report_submitted',
            'message' => '✝️ New Evangelism Report from ' . $this->report->church->name,
            'action_url' => route('evangelism-reports.show', $this->report),
            'report_id' => $this->report->id,
            'entered_by' => $this->report->submitter->name ?? 'Unknown',
            'icon' => 'users',
            'category' => 'evangelism',
        ];
    }
}
