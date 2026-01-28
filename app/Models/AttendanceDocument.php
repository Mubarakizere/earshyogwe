<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDocument extends Model
{
    protected $fillable = [
        'attendance_id',
        'file_path',
        'file_type',
        'original_name',
        'description',
        'uploaded_by',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the URL for this document
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Check if this is an image
     */
    public function getIsImageAttribute()
    {
        return $this->file_type === 'image';
    }

    /**
     * Check if this is a PDF
     */
    public function getIsPdfAttribute()
    {
        return $this->file_type === 'pdf';
    }
}
