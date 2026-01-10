<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Church extends Model
{
    use SoftDeletes;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'name',
        'location',
        'address',
        'phone',
        'email',
        'description',
        'diocese',
        'region',
        'archid_id',
        'pastor_id',
        'is_active',
    ];

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Created Church: ' . $model->name);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $description = 'Updated Church: ' . $model->name;
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Deleted Church: ' . $model->name);
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function pastor()
    {
        return $this->belongsTo(User::class, 'pastor_id');
    }

    public function archid()
    {
        return $this->belongsTo(User::class, 'archid_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function givings()
    {
        return $this->hasMany(Giving::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function evangelismReports()
    {
        return $this->hasMany(EvangelismReport::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
