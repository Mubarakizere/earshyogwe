<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomFieldDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'field_name',
        'field_key',
        'field_type',
        'field_options',
        'is_required',
        'help_text',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function customValues()
    {
        return $this->hasMany(ActivityCustomValue::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('field_name');
    }

    /**
     * Helper method to generate field key from name
     */
    public static function generateFieldKey($fieldName)
    {
        return \Str::slug($fieldName, '_');
    }
}
