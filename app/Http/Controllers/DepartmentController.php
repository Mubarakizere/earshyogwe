<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Church;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Use permissions to decide what to show
        if ($user->can('view all departments')) {
             $departments = Department::with('church')->get();
        } elseif ($user->hasRole('archid')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $departments = Department::whereIn('church_id', $churchIds)->with('church')->get();
        } elseif ($user->church_id) {
             $departments = Department::where('church_id', $user->church_id)->with('church')->get();
        } else {
             $departments = collect();
        }

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $this->authorize('create departments');
        $churches = $this->getChurchesForUser(auth()->user());
        return view('departments.create', compact('churches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create departments');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'church_id' => 'required|exists:churches,id',
            'description' => 'nullable|string',
        ]);

        Department::create([
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $this->authorize('edit departments');
        $churches = $this->getChurchesForUser(auth()->user());
        return view('departments.edit', compact('department', 'churches'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('edit departments');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'church_id' => 'required|exists:churches,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        // Add permission check if 'delete departments' existed, but strictly it was just 'edit' in previous list?
        // Let's assume 'edit departments' covers status deactivation, but if we delete..
        // Seeder didn't list 'delete departments' explicitly? Checking seeder...
        // Seeder has: 'create departments', 'edit departments', 'view all departments', 'assign users'.
        // So maybe no delete? Or just soft delete?
        // Let's stick to update status for now, or just allow destroy if they are boss.
        
        if (auth()->user()->hasRole('boss')) {
            $department->delete();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        }

        abort(403, 'Unauthorized action.');
    }
    
    private function getChurchesForUser($user)
    {
        if ($user->can('view all churches')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } elseif ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        return collect();
    }
}
