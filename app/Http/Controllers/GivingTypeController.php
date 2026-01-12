<?php

namespace App\Http\Controllers;

use App\Models\GivingType;
use App\Models\GivingSubType;
use Illuminate\Http\Request;

class GivingTypeController extends Controller
{
    public function index()
    {
        $this->authorize('manage giving types');
        
        $givingTypes = GivingType::with('subTypes')->latest()->get();
        return view('giving-types.index', compact('givingTypes'));
    }

    public function create()
    {
        $this->authorize('manage giving types');
        
        return view('giving-types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage giving types');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_sub_types' => 'boolean',
        ]);

        $givingType = GivingType::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('giving-types.index')
            ->with('success', 'Offering type created successfully!');
    }

    public function edit(GivingType $givingType)
    {
        $this->authorize('manage giving types');
        
        $givingType->load('subTypes');
        return view('giving-types.edit', compact('givingType'));
    }

    public function update(Request $request, GivingType $givingType)
    {
        $this->authorize('manage giving types');
        
        $request->merge([
            'has_sub_types' => $request->boolean('has_sub_types'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_sub_types' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $givingType->update($validated);

        return redirect()->route('giving-types.index')
            ->with('success', 'Offering type updated successfully!');
    }

    public function destroy(GivingType $givingType)
    {
        $this->authorize('manage giving types');
        
        $givingType->delete();

        return redirect()->route('giving-types.index')
            ->with('success', 'Offering type deleted successfully!');
    }

    public function storeSubType(Request $request, GivingType $givingType)
    {
        $this->authorize('manage giving types');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $givingType->subTypes()->create($validated);

        return back()->with('success', 'Sub-type added successfully!');
    }

    public function destroySubType(GivingSubType $givingSubType)
    {
        $this->authorize('manage giving types');
        
        $givingSubType->delete();

        return back()->with('success', 'Sub-type deleted successfully!');
    }
}
