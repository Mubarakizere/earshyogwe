<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectiveReportDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_report_id',
        'file_path',
        'file_type',
        'file_name',
        'uploaded_by',
    ];

    public function report()
    {
        return $this->belongsTo(ObjectiveReport::class, 'objective_report_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
