<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Models\ActivityProgressLog;
use App\Models\User;
use App\Notifications\ActivityDeadlineApproaching;
use App\Notifications\ActivityProgressReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class SendActivityReminders extends Command
{
    protected $signature = 'activities:send-reminders';
    protected $description = 'Send reminders for approaching deadlines and missing progress reports';

    public function handle()
    {
        $this->info('Starting activity reminders check...');

        $this->checkDeadlines();
        $this->checkProgressReporting();

        $this->info('Reminders check completed.');
    }

    private function checkDeadlines()
    {
        // Check activities starting soon (3 days)
        $startingSoon = Activity::where('status', 'planned')
            ->whereDate('start_date', Carbon::now()->addDays(3))
            ->get();

        foreach ($startingSoon as $activity) {
            $user = $this->getResponsibleUser($activity);
            if ($user) {
                $user->notify(new ActivityDeadlineApproaching($activity, 'start', 3));
                $this->info("Start reminder sent for: {$activity->name}");
            }
        }

        // Check activities ending soon (3 days)
        $endingSoon = Activity::where('status', 'in_progress')
            ->whereDate('end_date', Carbon::now()->addDays(3))
            ->get();

        foreach ($endingSoon as $activity) {
            $user = $this->getResponsibleUser($activity);
            if ($user) {
                $user->notify(new ActivityDeadlineApproaching($activity, 'end', 3));
                $this->info("End reminder sent for: {$activity->name}");
            }
        }
    }

    private function checkProgressReporting()
    {
        // Get all in_progress activities
        $activities = Activity::where('status', 'in_progress')->with('progressLogs')->get();

        foreach ($activities as $activity) {
            $frequency = $activity->tracking_frequency ?? 'weekly'; // default to weekly
            
            // Calculate limit date based on frequency
            $daysLimit = match($frequency) {
                'daily' => 1,
                'weekly' => 7,
                'biweekly' => 14,
                'monthly' => 30,
                default => 7,
            };

            // Add a small grace period (e.g., 1 day) to avoid spamming exactly on the due second
            // Or strictly enforce it. Let's strictly enforce but check if last log > limit
            
            $lastLog = $activity->progressLogs()->latest('log_date')->first();
            $lastLogDate = $lastLog ? $lastLog->log_date : $activity->start_date; // fallback to start date if no logs

            // Ensure lastLogDate is a Carbon instance
            $lastLogDate = Carbon::parse($lastLogDate);
            $daysSince = $lastLogDate->diffInDays(Carbon::now());

            // If we are strictly past the limit (e.g. 8 days since last log for weekly)
            if ($daysSince > $daysLimit) {
                // Check if we haven't already reminded them recently to avoid spam?
                // For MVP, we'll just send it if the condition matches.
                // Ideally we'd store "last_reminder_sent_at" on activity.
                // But let's assume this runs daily, so they get daily nags until they log!
                
                $user = $this->getResponsibleUser($activity);
                if ($user) {
                    $user->notify(new ActivityProgressReminder($activity, (int)$daysSince));
                    $this->info("Progress reminder sent for: {$activity->name} ({$daysSince} days silent)");
                }
            }
        }
    }

    private function getResponsibleUser(Activity $activity)
    {
        // 1. Try to find user by 'responsible_person' name if it matches a User name (fragile but common in this existing db)
        if ($activity->responsible_person) {
            $user = User::where('name', $activity->responsible_person)->first();
            if ($user) return $user;
        }

        // 2. Fallback to creator
        return $activity->creator;
    }
}
