<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, SoftDeletes;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'church_id',
        'department_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'responsible_person',
        'status', // planned, in_progress, completed, cancelled
        'target',
        'current_progress',
        'approval_status', // pending, approved, rejected
        'budget_estimate',
        'financial_spent',
        'completion_summary',
        'attendance_count',
        'salvation_count',
        'created_by',
    ];

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Created Activity: ' . $model->name);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                // Get original for better context if needed, but keeping it short for view
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $description = 'Updated Activity: ' . $model->name;
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Deleted Activity: ' . $model->name);
        });
    }
    

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indicators()
    {
        return $this->hasMany(ActivityIndicator::class);
    }

    public function documents()
    {
        return $this->hasMany(ActivityDocument::class);
    }

    // Accessor for progress percentage
    public function getProgressPercentageAttribute()
    {
        if ($this->target == 0) return 0;
        return min(100, round(($this->current_progress / $this->target) * 100));
    }

    // Scopes
    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }
}

class ActivityIndicator extends Model
{
    protected $fillable = ['activity_id', 'indicator_name', 'target_value', 'current_value', 'unit'];
    
    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

class ActivityDocument extends Model
{
    protected $fillable = ['activity_id', 'file_path', 'file_type', 'description', 'uploaded_by', 'uploaded_at'];
    
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
