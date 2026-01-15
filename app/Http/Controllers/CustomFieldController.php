<?php

namespace App\Http\Controllers;

use App\Models\CustomFieldDefinition;
use App\Models\Department;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        $this->authorize('manage custom fields');
        
        // Get user's departments (if they're a department head)
        $user = auth()->user();
        $departments = Department::query()
            ->where('head_id', $user->id)
            ->orWhereHas('permissions', function($query) use ($user) {
                $query->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })
            ->get();

        // Get custom fields for user's departments
        $customFields = CustomFieldDefinition::query()
            ->whereIn('department_id', $departments->pluck('id'))
            ->with('department')
            ->ordered()
            ->paginate(20);

        return view('custom-fields.index', compact('customFields', 'departments'));
    }

    public function create()
    {
        $this->authorize('manage custom fields');
        
        $user = auth()->user();
        $departments = Department::query()
            ->where('head_id', $user->id)
            ->get();

        if ($departments->isEmpty()) {
            return redirect()->route('custom-fields.index')
                ->with('error', 'You must be a department head to create custom fields.');
        }

        return view('custom-fields.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage custom fields');

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,select,checkbox',
            'field_options' => 'nullable|array',
            'field_options.*' => 'string|max:255',
            'is_required' => 'boolean',
            'help_text' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Verify user has permission for this department
        $department = Department::findOrFail($validated['department_id']);
        if ($department->head_id !== auth()->id()) {
            abort(403, 'You can only create custom fields for your own department.');
        }

        // Generate unique field key
        $fieldKey = CustomFieldDefinition::generateFieldKey($validated['field_name']);
        
        // Check if key already exists for this department
        $counter = 1;
        $originalKey = $fieldKey;
        while (CustomFieldDefinition::where('department_id', $validated['department_id'])
            ->where('field_key', $fieldKey)
            ->exists()) {
            $fieldKey = $originalKey . '_' . $counter++;
        }

        CustomFieldDefinition::create([
            'department_id' => $validated['department_id'],
            'field_name' => $validated['field_name'],
            'field_key' => $fieldKey,
            'field_type' => $validated['field_type'],
            'field_options' => $validated['field_type'] === 'select' ? $validated['field_options'] : null,
            'is_required' => $validated['is_required'] ?? false,
            'help_text' => $validated['help_text'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field created successfully.');
    }

    public function edit(CustomFieldDefinition $customField)
    {
        $this->authorize('manage custom fields');

        // Verify user owns this field
        if ($customField->department->head_id !== auth()->id()) {
            abort(403, 'You can only edit custom fields for your own department.');
        }

        $departments = Department::where('head_id', auth()->id())->get();

        return view('custom-fields.edit', compact('customField', 'departments'));
    }

    public function update(Request $request, CustomFieldDefinition $customField)
    {
        $this->authorize('manage custom fields');

        // Verify user owns this field
        if ($customField->department->head_id !== auth()->id()) {
            abort(403, 'You can only edit custom fields for your own department.');
        }

        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,select,checkbox',
            'field_options' => 'nullable|array',
            'field_options.*' => 'string|max:255',
            'is_required' => 'boolean',
            'help_text' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $customField->update([
            'field_name' => $validated['field_name'],
            'field_type' => $validated['field_type'],
            'field_options' => $validated['field_type'] === 'select' ? $validated['field_options'] : null,
            'is_required' => $validated['is_required'] ?? false,
            'help_text' => $validated['help_text'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field updated successfully.');
    }

    public function destroy(CustomFieldDefinition $customField)
    {
        $this->authorize('manage custom fields');

        // Verify user owns this field
        if ($customField->department->head_id !== auth()->id()) {
            abort(403, 'You can only delete custom fields for your own department.');
        }

        $customField->delete();

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field deleted successfully.');
    }
}
