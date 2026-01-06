<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Department;
use App\Models\Church;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Activity::with(['department', 'church', 'creator']);
        
        // Permission-based filtering
        if ($user->can('view all activities')) {
             // See all
        } elseif ($user->can('view assigned activities') && $user->hasRole('archid')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own activities') && $user->church_id) {
             $query->where('church_id', $user->church_id);
        } else {
             $query->where('id', 0);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $activities = $query->latest()->paginate(20);
        
        $departments = $this->getDepartmentsForUser($user);
        $churches = $this->getChurchesForUser($user);
        
        // Stats
        $baseQuery = Activity::query();
        if ($user->can('view all activities')) {
             // all
        } elseif ($user->can('view assigned activities') && $user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $baseQuery->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own activities') && $user->church_id) {
            $baseQuery->where('church_id', $user->church_id);
        } else {
            $baseQuery->where('id', 0);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        ];
        
        return view('activities.index', compact('activities', 'departments', 'churches', 'stats'));
    }

    public function create()
    {
        $this->authorize('create activities');
        
        $departments = $this->getDepartmentsForUser(auth()->user());
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('activities.create', compact('departments', 'churches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create activities');
        
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'church_id' => 'required|exists:churches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'target' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $activity = Activity::create([
            'department_id' => $validated['department_id'],
            'church_id' => $validated['church_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'responsible_person' => $validated['responsible_person'] ?? null,
            'target' => $validated['target'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('activity-documents', 'public');
                $fileType = $file->getClientOriginalExtension();
                
                \App\Models\ActivityDocument::create([
                    'activity_id' => $activity->id,
                    'file_path' => $path,
                    'file_type' => in_array($fileType, ['jpg', 'jpeg', 'png']) ? 'image' : ($fileType === 'pdf' ? 'pdf' : 'document'),
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully!');
    }

    public function show(Activity $activity)
    {
        // View permission check implicated? For now allow if can view index.
        $activity->load(['department', 'church', 'indicators', 'documents']);
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $this->authorize('edit activities');
        
        $departments = $this->getDepartmentsForUser(auth()->user());
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('activities.edit', compact('activity', 'departments', 'churches'));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('edit activities');

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'church_id' => 'required|exists:churches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'target' => 'required|integer|min:0',
            'current_progress' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully!');
    }

    private function getDepartmentsForUser($user)
    {
        if ($user->can('view all departments')) {
            return Department::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return Department::whereIn('church_id', $churchIds)->where('is_active', true)->get();
        } else {
            return Department::where('church_id', $user->church_id)->where('is_active', true)->get();
        }
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
