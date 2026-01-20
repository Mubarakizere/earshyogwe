<?php

namespace App\Http\Controllers;

use App\Models\ChurchGroup;
use Illuminate\Http\Request;

class ChurchGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage church groups');
        
        $churchGroups = ChurchGroup::withCount('members')->orderBy('name')->get();
        
        return view('church-groups.index', compact('churchGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage church groups');
        
        return view('church-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage church groups');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:church_groups,name',
            'description' => 'nullable|string',
        ]);

        ChurchGroup::create($validated);

        return redirect()->route('church-groups.index')
            ->with('success', 'Church group created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChurchGroup $churchGroup)
    {
        $this->authorize('manage church groups');
        
        return view('church-groups.edit', compact('churchGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChurchGroup $churchGroup)
    {
        $this->authorize('manage church groups');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:church_groups,name,' . $churchGroup->id,
            'description' => 'nullable|string',
        ]);

        $churchGroup->update($validated);

        return redirect()->route('church-groups.index')
            ->with('success', 'Church group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchGroup $churchGroup)
    {
        $this->authorize('manage church groups');
        
        $churchGroup->delete();

        return redirect()->route('church-groups.index')
            ->with('success', 'Church group deleted successfully.');
    }
}
