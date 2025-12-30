<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Giving extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'church_id',
        'giving_type_id',
        'giving_sub_type_id',
        'amount',
        'date',
        'week',
        'month',
        'year',
        'sent_to_diocese',
        'diocese_sent_date',
        'diocese_amount',
        'notes',
        'entered_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'diocese_amount' => 'decimal:2',
        'date' => 'date',
        'diocese_sent_date' => 'date',
        'sent_to_diocese' => 'boolean',
    ];

    /**
     * Boot method to automatically set week, month, year from date
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($giving) {
            if ($giving->date) {
                $date = Carbon::parse($giving->date);
                $giving->week = $date->week;
                $giving->month = $date->month;
                $giving->year = $date->year;
            }
        });

        static::updating(function ($giving) {
            if ($giving->isDirty('date')) {
                $date = Carbon::parse($giving->date);
                $giving->week = $date->week;
                $giving->month = $date->month;
                $giving->year = $date->year;
            }
        });
    }

    /**
     * Relationships
     */
    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function givingType()
    {
        return $this->belongsTo(GivingType::class);
    }

    public function givingSubType()
    {
        return $this->belongsTo(GivingSubType::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    /**
     * Scopes
     */
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

    public function scopeForWeek($query, $year, $week)
    {
        return $query->where('year', $year)->where('week', $week);
    }

    public function scopeSentToDiocese($query)
    {
        return $query->where('sent_to_diocese', true);
    }

    public function scopeNotSentToDiocese($query)
    {
        return $query->where('sent_to_diocese', false);
    }
}
