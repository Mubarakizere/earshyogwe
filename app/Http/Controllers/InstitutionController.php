<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage institutions');

        $query = Institution::with('creator');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $institutions = $query->orderBy('name')->paginate(20)->withQueryString();

        // Get statistics for the UI
        $stats = [
            'total' => Institution::count(),
            'active' => Institution::where('is_active', true)->count(),
            'inactive' => Institution::where('is_active', false)->count(),
            'by_type' => Institution::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];

        // Institution types for filter options
        $types = [
            'diocese' => 'Diocese',
            'health_center' => 'Health Center',
            'health_post' => 'Health Post',
            'primary_school' => 'Primary School',
            'secondary_school' => 'Secondary School',
            'university' => 'University',
            'rw_project' => 'RW Project',
        ];

        return view('institutions.index', compact('institutions', 'stats', 'types'));
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
