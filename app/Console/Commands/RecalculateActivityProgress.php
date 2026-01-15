<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Models\ActivityProgressLog;

class RecalculateActivityProgress extends Command
{
    protected $signature = 'activities:recalculate-progress';
    protected $description = 'Recalculate current_progress for all activities based on their progress logs';

    public function handle()
    {
        $this->info('Recalculating progress for all activities...');
        
        $activities = Activity::all();
        $updated = 0;
        
        foreach ($activities as $activity) {
            // Sum all progress logs for this activity
            $totalProgress = ActivityProgressLog::where('activity_id', $activity->id)
                ->sum('progress_value');
            
            if ($totalProgress != $activity->current_progress) {
                $oldProgress = $activity->current_progress;
                $activity->update(['current_progress' => $totalProgress]);
                
                $this->line("Activity #{$activity->id} ({$activity->name}): {$oldProgress} → {$totalProgress}");
                $updated++;
            }
        }
        
        $this->info("✅ Done! Updated {$updated} activities.");
        
        return 0;
    }
}
