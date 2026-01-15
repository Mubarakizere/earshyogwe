<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityCustomValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'custom_field_definition_id',
        'field_value',
    ];

    /**
     * Relationships
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function fieldDefinition()
    {
        return $this->belongsTo(CustomFieldDefinition::class, 'custom_field_definition_id');
    }

    /**
     * Get the value cast to the appropriate type
     */
    public function getTypedValueAttribute()
    {
        $fieldType = $this->fieldDefinition->field_type;

        switch ($fieldType) {
            case 'number':
                return (float) $this->field_value;
            case 'date':
                return $this->field_value ? \Carbon\Carbon::parse($this->field_value) : null;
            case 'checkbox':
                return (bool) $this->field_value;
            default:
                return $this->field_value;
        }
    }
}
