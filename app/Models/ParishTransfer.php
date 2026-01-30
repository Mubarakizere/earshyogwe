<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParishTransfer extends Model
{
    use SoftDeletes, \App\Traits\LogsActivity;

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $churchName = $model->church ? $model->church->name : 'Unknown Parish';
            $model->logActivity('create', "Recorded Transfer: {$model->amount} RWF from {$churchName}");
        });

        static::updated(function ($model) {
            if ($model->wasChanged('status') && $model->status === 'verified') {
                $churchName = $model->church ? $model->church->name : 'Unknown Parish';
                $model->logActivity('update', "Verified Transfer: {$model->amount} RWF from {$churchName}");
                return;
            }
            if ($model->wasChanged('status') && $model->status === 'rejected') {
                $churchName = $model->church ? $model->church->name : 'Unknown Parish';
                $model->logActivity('update', "Rejected Transfer: {$model->amount} RWF from {$churchName}");
                return;
            }
        });

        static::deleted(function ($model) {
            $churchName = $model->church ? $model->church->name : 'Unknown Parish';
            $model->logActivity('delete', "Deleted Transfer: {$model->amount} RWF from {$churchName}");
        });
    }

    protected $fillable = [
        'church_id',
        'amount',
        'transfer_date',
        'reference',
        'notes',
        'status',
        'entered_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transfer_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scopes
     */
    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
