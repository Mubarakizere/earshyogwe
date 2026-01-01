<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopulationCensus extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'year',
        'period',
        'men_count',
        'women_count',
        'youth_count',
        'children_count',
        'infants_count',
        'status',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }
    
    // Helper to get total
    public function getTotalAttribute()
    {
        return $this->men_count + $this->women_count + $this->youth_count + $this->children_count + $this->infants_count;
    }
}
