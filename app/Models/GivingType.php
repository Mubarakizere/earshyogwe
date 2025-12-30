<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GivingType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'has_sub_types',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'has_sub_types' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function subTypes()
    {
        return $this->hasMany(GivingSubType::class);
    }

    public function givings()
    {
        return $this->hasMany(Giving::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
