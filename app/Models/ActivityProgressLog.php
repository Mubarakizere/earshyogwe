<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'logged_by',
        'log_date',
        'progress_value',
        'progress_percentage',
        'notes',
        'photos',
    ];

    protected $casts = [
        'log_date' => 'date',
        'photos' => 'array',
    ];

    /**
     * Relationships
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function logger()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    /**
     * Scopes
     */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('log_date', 'desc');
    }
}
