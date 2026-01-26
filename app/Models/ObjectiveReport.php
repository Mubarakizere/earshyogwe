<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveReport extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'objective_id',
        'user_id',
        'report_date',
        'activities_description',
        'results_outcome',
        'quantity',
        'location',
        'budget_spent',
        'responsible_person',
        'status',
    ];

    protected $casts = [
        'report_date' => 'date',
        'quantity' => 'decimal:2',
        'budget_spent' => 'decimal:2',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function documents()
    {
        return $this->hasMany(ObjectiveReportDocument::class, 'objective_report_id');
    }
}
