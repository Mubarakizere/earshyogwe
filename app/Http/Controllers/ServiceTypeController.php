<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceType;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage service types');
        
        $serviceTypes = ServiceType::latest()->get();
        return view('service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage service types');
        
        return view('service-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage service types');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ServiceType::create($validated);

        return redirect()->route('service-types.index')
            ->with('success', 'Service type created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType)
    {
        $this->authorize('manage service types');
        
        return view('service-types.edit', compact('serviceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $this->authorize('manage service types');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $serviceType->update($validated);

        return redirect()->route('service-types.index')
            ->with('success', 'Service type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $serviceType)
    {
        $this->authorize('manage service types');
        
        $serviceType->delete();

        return redirect()->route('service-types.index')
            ->with('success', 'Service type deleted successfully!');
    }
}
