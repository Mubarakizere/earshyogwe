<?php

namespace App\Http\Controllers;

use App\Models\GivingType;
use App\Models\GivingSubType;
use Illuminate\Http\Request;

class GivingTypeController extends Controller
{
    public function index()
    {
        $givingTypes = GivingType::with('subTypes')->latest()->get();
        return view('giving-types.index', compact('givingTypes'));
    }

    public function create()
    {
        return view('giving-types.create');
    }

    public function store(Request $request)
    {
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
            ->with('success', 'Giving type created successfully!');
    }

    public function edit(GivingType $givingType)
    {
        $givingType->load('subTypes');
        return view('giving-types.edit', compact('givingType'));
    }

    public function update(Request $request, GivingType $givingType)
    {
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
            ->with('success', 'Giving type updated successfully!');
    }

    public function destroy(GivingType $givingType)
    {
        $givingType->delete();

        return redirect()->route('giving-types.index')
            ->with('success', 'Giving type deleted successfully!');
    }

    public function storeSubType(Request $request, GivingType $givingType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $givingType->subTypes()->create($validated);

        return back()->with('success', 'Sub-type added successfully!');
    }

    public function destroySubType(GivingSubType $givingSubType)
    {
        $givingSubType->delete();

        return back()->with('success', 'Sub-type deleted successfully!');
    }
}
