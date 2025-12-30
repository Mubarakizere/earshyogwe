<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department_id',
        'church_id',
        'name',
        'description',
        'responsible_person',
        'target',
        'current_progress',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

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
