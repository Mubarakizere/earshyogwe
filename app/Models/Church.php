<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Church extends Model
{
    use SoftDeletes;

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
}
