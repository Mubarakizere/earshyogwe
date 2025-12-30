<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GivingSubType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'giving_type_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function givingType()
    {
        return $this->belongsTo(GivingType::class);
    }

    public function givings()
    {
        return $this->hasMany(Giving::class);
    }
}
