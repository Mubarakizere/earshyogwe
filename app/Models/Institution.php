<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted type name
     */
    public function getTypeNameAttribute()
    {
        return str_replace('_', ' ', ucwords($this->type, '_'));
    }
}
