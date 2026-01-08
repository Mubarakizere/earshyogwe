<?php

namespace App\Notifications;

use App\Models\EvangelismReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvangelismReportSubmitted extends Notification
{
    // Removing Queueable to ensure instant delivery for now, or keep it if queue is set up.
    // Given previous context (Expenses), user preferred instant notifications.
    // But standard practice is queue. Let's stick to synchronous for simplicity unless specified.
    
    public $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(EvangelismReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Start with database notifications for in-app alerts
    }

    /**
     * Get the mail representation of the notification.
     */
    /*
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'evangelism_report_submitted',
            'message' => 'New Evangelism Report from ' . $this->report->church->name,
            'url' => route('evangelism-reports.show', $this->report),
            'report_id' => $this->report->id,
            'entered_by' => $this->report->submitter->name ?? 'Unknown',
        ];
    }
}
