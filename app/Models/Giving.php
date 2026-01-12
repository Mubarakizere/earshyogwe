<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Giving extends Model
{
    use SoftDeletes, \App\Traits\LogsActivity;

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $churchName = $model->church ? $model->church->name : 'Unknown Church';
            $typeName = $model->givingType ? $model->givingType->name : 'Unknown Type';
            $model->logActivity('create', "Recorded Giving: {$model->amount} RWF ({$typeName}) for {$churchName}");
        });

        static::updated(function ($model) {
            // Check for specific status changes
            if ($model->wasChanged('sent_to_diocese') && $model->sent_to_diocese) {
                $churchName = $model->church ? $model->church->name : 'Unknown Church';
                $model->logActivity('update', "Marked Giving as Sent to Diocese: {$model->amount} RWF from {$churchName}");
                return;
            }
            if ($model->wasChanged('receipt_status') && $model->receipt_status === 'verified') {
                $churchName = $model->church ? $model->church->name : 'Unknown Church';
                $model->logActivity('update', "Verified Diocese Receipt: {$model->amount} RWF from {$churchName}");
                return;
            }

            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }

            $description = "Updated Giving Entry";
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $churchName = $model->church ? $model->church->name : 'Unknown Church';
            $typeName = $model->givingType ? $model->givingType->name : 'Unknown Type';
            $model->logActivity('delete', "Deleted Giving: {$model->amount} RWF ({$typeName}) from {$churchName}");
        });
    }

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
        'receipt_status', // New
        'transfer_reference', // New
        'verified_by', // New
        'verified_at', // New
        'notes',
        'entered_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'diocese_amount' => 'decimal:2',
        'date' => 'date',
        'diocese_sent_date' => 'date',
        'verified_at' => 'datetime', // New
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

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('sent_to_diocese', true)->where('receipt_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('receipt_status', 'verified');
    }
}
