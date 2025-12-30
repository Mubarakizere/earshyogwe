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
    public function logActivity($action, $description, $module = null)
    {
        // Try to determine module from class name if not provided
        if (!$module) {
            $className = class_basename($this);
            $module = strtolower(str_replace('Controller', '', $className));
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'subject_type' => method_exists($this, 'getSubject') ? get_class($this->getSubject()) : null,
            'subject_id' => method_exists($this, 'getSubject') ? $this->getSubject()->id : null,
        ]);
    }
    
    /**
     * Static helper to log activity anywhere
     */
    public static function log($action, $description, $module = 'system', $subject = null)
    {
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
