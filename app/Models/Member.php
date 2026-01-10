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
    ];

    protected $casts = [
        'dob' => 'date',
        'extra_attributes' => 'array',
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
}
