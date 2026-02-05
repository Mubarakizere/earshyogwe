<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberTransfer extends Model
{
    use SoftDeletes, \App\Traits\LogsActivity;

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $memberName = $model->member ? $model->member->name : 'Unknown Member';
            $fromChurch = $model->fromChurch ? $model->fromChurch->name : 'Unknown Parish';
            $toChurch = $model->toChurch ? $model->toChurch->name : 'Unknown Parish';
            $model->logActivity('create', "Initiated Member Transfer: {$memberName} from {$fromChurch} to {$toChurch}");
        });

        static::updated(function ($model) {
            if ($model->wasChanged('status') && $model->status === 'approved') {
                $memberName = $model->member ? $model->member->name : 'Unknown Member';
                $model->logActivity('update', "Approved Member Transfer: {$memberName}");
                return;
            }
            if ($model->wasChanged('status') && $model->status === 'rejected') {
                $memberName = $model->member ? $model->member->name : 'Unknown Member';
                $model->logActivity('update', "Rejected Member Transfer: {$memberName}");
                return;
            }
        });

        static::deleted(function ($model) {
            $memberName = $model->member ? $model->member->name : 'Unknown Member';
            $model->logActivity('delete', "Deleted Member Transfer: {$memberName}");
        });
    }

    protected $fillable = [
        'member_id',
        'from_church_id',
        'to_church_id',
        'transfer_date',
        'reason',
        'status',
        'initiated_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function fromChurch()
    {
        return $this->belongsTo(Church::class, 'from_church_id');
    }

    public function toChurch()
    {
        return $this->belongsTo(Church::class, 'to_church_id');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeFromChurch($query, $churchId)
    {
        return $query->where('from_church_id', $churchId);
    }

    public function scopeToChurch($query, $churchId)
    {
        return $query->where('to_church_id', $churchId);
    }
}
