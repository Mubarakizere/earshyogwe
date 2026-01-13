<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WorkerDocument extends Model
{
    protected $fillable = [
        'worker_id',
        'document_name',
        'document_type',
        'file_path',
    ];

    /**
     * Relationships
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            if (Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }
        });
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute()
    {
        return route('worker-documents.download', $this->id);
    }
}
