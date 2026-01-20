<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', "Created Member: {$model->name} ({$model->sex}) at {$model->church->name}");
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $description = "Updated Member: {$model->name}";
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', "Deleted Member: {$model->name} from {$model->church->name}");
        });
    }

    protected $fillable = [
        'church_id',
        'name',
        'sex',
        'dob',
        'marital_status',
        'parental_status',
        'baptism_status',
        'church_group',
        'education_level',
        'extra_attributes',
        'status',
        'inactive_reason',
        'inactive_date',
        'deceased_date',
        'deceased_cause',
    ];

    protected $casts = [
        'dob' => 'date',
        'extra_attributes' => 'array',
        'inactive_date' => 'date',
        'deceased_date' => 'date',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    // Accessor for Age
    public function getAgeAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->age : null;
    }

    /**
     * Get the church groups this member belongs to.
     */
    public function churchGroups()
    {
        return $this->belongsToMany(ChurchGroup::class, 'church_group_member')
                    ->withTimestamps()
                    ->withPivot('joined_date');
    }

    /**
     * Scope a query to only include active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive members.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include deceased members.
     */
    public function scopeDeceased($query)
    {
        return $query->where('status', 'deceased');
    }
}
