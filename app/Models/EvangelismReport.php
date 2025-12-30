<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EvangelismReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'church_id',
        'report_date',
        'month',
        'year',
        'bible_study_count',
        'mentorship_count',
        'leadership_count',
        'converts',
        'baptized',
        'confirmed',
        'new_members',
        'notes',
        'submitted_by',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if ($report->report_date) {
                $date = Carbon::parse($report->report_date);
                $report->month = $date->month;
                $report->year = $date->year;
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
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
}
