<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Attendance extends Model
{
    use SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'church_id',
        'attendance_date',
        'service_type_id',
        'service_name',
        'men_count',
        'women_count',
        'children_count',
        'total_count',
        'week',
        'month',
        'year',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $serviceName = $model->service_name ? "({$model->service_name})" : '';
            $description = "Recorded Attendance: {$model->serviceType->name} {$serviceName} at {$model->church->name} - {$model->total_count} people";
            $model->logActivity('create', $description);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $serviceName = $model->service_name ? "({$model->service_name})" : '';
            $description = "Updated Attendance: {$model->serviceType->name} {$serviceName}";
            
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $serviceName = $model->service_name ? "({$model->service_name})" : '';
            $description = "Deleted Attendance: {$model->serviceType->name} {$serviceName} at {$model->church->name}";
            $model->logActivity('delete', $description);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendance) {
            if ($attendance->attendance_date) {
                $date = Carbon::parse($attendance->attendance_date);
                $attendance->week = $date->week;
                $attendance->month = $date->month;
                $attendance->year = $date->year;
            }
            
            // Auto-calculate total
            $attendance->total_count = (int)$attendance->men_count + (int)$attendance->women_count + (int)$attendance->children_count;
        });

        static::updating(function ($attendance) {
            if ($attendance->isDirty('attendance_date')) {
                $date = Carbon::parse($attendance->attendance_date);
                $attendance->week = $date->week;
                $attendance->month = $date->month;
                $attendance->year = $date->year;
            }
            
            // Auto-calculate total
            if ($attendance->isDirty(['men_count', 'women_count', 'children_count'])) {
                $attendance->total_count = (int)$attendance->men_count + (int)$attendance->women_count + (int)$attendance->children_count;
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function documents()
    {
        return $this->hasMany(AttendanceDocument::class);
    }

    // Scopes
    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeSundayServices($query)
    {
        return $query->whereHas('serviceType', function($q) {
            $q->where('name', 'Sunday Service');
        });
    }
}
