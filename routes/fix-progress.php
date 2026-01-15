<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

// Quick fix route to recalculate activity progress
Route::get('/fix-activity-progress/{activity}', function(\App\Models\Activity $activity) {
    $totalProgress = \App\Models\ActivityProgressLog::where('activity_id', $activity->id)
        ->sum('progress_value');
    
    $oldProgress = $activity->current_progress;
    $activity->update(['current_progress' => $totalProgress]);
    
    return redirect()->route('activities.show', $activity)
        ->with('success', "Progress recalculated: {$oldProgress} → {$totalProgress}");
})->name('activities.fix-progress')->middleware('auth');
