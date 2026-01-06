<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Log an activity.
     *
     * @param string $action
     * @param string $description
     * @param string|null $module
     * @return void
     */
    /**
     * Boot the trait.
     */
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Created ' . class_basename($model) . ' #' . $model->id);
        });

        static::updated(function ($model) {
            $model->logActivity('update', 'Updated ' . class_basename($model) . ' #' . $model->id);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Deleted ' . class_basename($model) . ' #' . $model->id);
        });
    }

    /**
     * Log an activity.
     *
     * @param string $action
     * @param string $description
     * @param string|null $module
     * @return void
     */
    public function logActivity($action, $description, $module = null)
    {
        // Try to determine module from class name if not provided
        if (!$module) {
            $module = strtolower(class_basename($this));
        }

        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip(),
                'subject_type' => get_class($this),
                'subject_id' => $this->id,
            ]);
        }
    }
    
    /**
     * Static helper to log activity anywhere
     */
    public static function log($action, $description, $module = 'system', $subject = null)
    {
        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip(),
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->id : null,
            ]);
        }
    }
}
