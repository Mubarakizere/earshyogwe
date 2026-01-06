<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

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
