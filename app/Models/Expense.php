<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'church_id',
        'expense_category_id',
        'amount',
        'date',
        'week',
        'month',
        'year',
        'description',
        'receipt_path',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'entered_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if ($expense->date) {
                $date = Carbon::parse($expense->date);
                $expense->week = $date->week;
                $expense->month = $date->month;
                $expense->year = $date->year;
            }
            
            // Auto-set status based on category
            if (!$expense->status && $expense->expenseCategory) {
                $expense->status = $expense->expenseCategory->requires_approval ? 'pending' : 'approved';
            }
        });

        static::updating(function ($expense) {
            if ($expense->isDirty('date')) {
                $date = Carbon::parse($expense->date);
                $expense->week = $date->week;
                $expense->month = $date->month;
                $expense->year = $date->year;
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    // Scopes
    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
