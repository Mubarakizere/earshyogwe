<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChurchGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the members in this church group.
     */
    public function members()
    {
        return $this->belongsToMany(Member::class, 'church_group_member')
                    ->withTimestamps()
                    ->withPivot('joined_date');
    }
}
