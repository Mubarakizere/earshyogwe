<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Worker extends Model
{
    use SoftDeletes;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'church_id',
        'institution_id',
        'first_name',
        'last_name',
        'gender',
        'national_id',
        'education_qualification',
        'email',
        'phone',
        'district',
        'sector',
        'job_title',
        'employment_date',
        'birth_date',
        'status',
    ];

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Created Worker: ' . $model->full_name);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $description = 'Updated Worker: ' . $model->full_name;
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Deleted Worker: ' . $model->full_name);
        });
    }

    protected $casts = [
        'employment_date' => 'date',
        'birth_date' => 'date',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function documents()
    {
        return $this->hasMany(WorkerDocument::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function activeContract()
    {
        return $this->hasOne(Contract::class)->where('status', 'active');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }
}

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'worker_id',
        'contract_type',
        'start_date',
        'end_date',
        'salary',
        'contract_document_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function renewals()
    {
        return $this->hasMany(ContractRenewal::class);
    }

    // Accessor for days until expiry
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->end_date) return null;
        return now()->diffInDays($this->end_date, false);
    }

    // Scope for expiring soon
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }
}

class ContractRenewal extends Model
{
    protected $fillable = ['contract_id', 'renewed_by', 'renewal_date', 'new_end_date', 'notes'];

    protected $casts = [
        'renewal_date' => 'date',
        'new_end_date' => 'date',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function renewer()
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}

class RetirementPlan extends Model
{
    protected $fillable = ['worker_id', 'planned_retirement_date', 'succession_plan', 'notes'];

    protected $casts = [
        'planned_retirement_date' => 'date',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
