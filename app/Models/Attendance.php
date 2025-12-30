<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'church_id',
        'attendance_date',
        'service_type',
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
            $attendance->total_count = $attendance->men_count + $attendance->women_count + $attendance->children_count;
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
                $attendance->total_count = $attendance->men_count + $attendance->women_count + $attendance->children_count;
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
        return $query->where('service_type', 'sunday_service');
    }
}
