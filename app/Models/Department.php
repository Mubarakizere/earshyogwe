<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'church_id',
        'name',
        'slug',
        'head_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-create permission when department is created
        static::created(function ($department) {
            $permissionName = $department->permission_name;
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
            
            // Auto-assign permission to head if one is set
            if ($department->head_id) {
                $head = \App\Models\User::find($department->head_id);
                if ($head && !$head->hasPermissionTo($permissionName)) {
                    $head->givePermissionTo($permissionName);
                }
            }
        });

        // Handle changes on update
        static::updated(function ($department) {
            $permissionName = $department->permission_name;

            // Handle head changes
            if ($department->isDirty('head_id')) {
                $oldHeadId = $department->getOriginal('head_id');
                $newHeadId = $department->head_id;

                // Remove permission from old head
                if ($oldHeadId) {
                    $oldHead = \App\Models\User::find($oldHeadId);
                    if ($oldHead && $oldHead->hasPermissionTo($permissionName)) {
                        $oldHead->revokePermissionTo($permissionName);
                    }
                }

                // Give permission to new head
                if ($newHeadId) {
                    $newHead = \App\Models\User::find($newHeadId);
                    if ($newHead && !$newHead->hasPermissionTo($permissionName)) {
                        $newHead->givePermissionTo($permissionName);
                    }
                }
            }

            // Handle slug changes
            if ($department->isDirty('slug')) {
                $oldSlug = $department->getOriginal('slug');
                $oldPermissionName = "view {$oldSlug} objectives";
                $newPermissionName = $department->permission_name;

                $permission = \Spatie\Permission\Models\Permission::where('name', $oldPermissionName)->first();
                if ($permission) {
                    $permission->update(['name' => $newPermissionName]);
                } else {
                    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $newPermissionName]);
                }
            }
        });

        // Auto-delete permission when department is deleted (if it's a force delete or we want to clean up)
        static::deleted(function ($department) {
            if ($department->isForceDeleting()) {
                $permissionName = $department->permission_name;
                $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $permission->delete();
                }
            }
        });
    }

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Created Department: ' . $model->name);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);
            
            $changes = [];
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);
                $changes[] = "$key: '$original' -> '$value'";
            }
            
            $description = 'Updated Department: ' . $model->name;
            if (count($changes) > 0) {
                 $description .= '. Changes: ' . implode(', ', $changes);
            }
            
            $model->logActivity('update', $description);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Deleted Department: ' . $model->name);
        });
    }

    /**
     * Relationships
     */
    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the permission name for this department
     */
    public function getPermissionNameAttribute()
    {
        return "view {$this->slug} objectives";
    }
}
