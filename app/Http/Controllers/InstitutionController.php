<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index()
    {
        $this->authorize('manage institutions');

        $institutions = Institution::with('creator')
            ->orderBy('name')
            ->paginate(20);

        return view('institutions.index', compact('institutions'));
    }

    public function create()
    {
        $this->authorize('manage institutions');

        $types = [
            'diocese' => 'Diocese',
            'health_center' => 'Health Center',
            'health_post' => 'Health Post',
            'primary_school' => 'Primary School',
            'secondary_school' => 'Secondary School',
            'university' => 'University',
            'rw_project' => 'RW Project',
        ];

        return view('institutions.create', compact('types'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage institutions');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:diocese,health_center,health_post,primary_school,secondary_school,university,rw_project',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        Institution::create($validated);

        return redirect()->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }

    public function edit(Institution $institution)
    {
        $this->authorize('manage institutions');

        $types = [
            'diocese' => 'Diocese',
            'health_center' => 'Health Center',
            'health_post' => 'Health Post',
            'primary_school' => 'Primary School',
            'secondary_school' => 'Secondary School',
            'university' => 'University',
            'rw_project' => 'RW Project',
        ];

        return view('institutions.edit', compact('institution', 'types'));
    }

    public function update(Request $request, Institution $institution)
    {
        $this->authorize('manage institutions');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:diocese,health_center,health_post,primary_school,secondary_school,university,rw_project',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $institution->update($validated);

        return redirect()->route('institutions.index')
            ->with('success', 'Institution updated successfully.');
    }

    public function destroy(Institution $institution)
    {
        $this->authorize('manage institutions');

        // Check if institution has workers
        if ($institution->workers()->count() > 0) {
            return redirect()->route('institutions.index')
                ->with('error', 'Cannot delete institution with assigned workers.');
        }

        $institution->delete();

        return redirect()->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }
}
