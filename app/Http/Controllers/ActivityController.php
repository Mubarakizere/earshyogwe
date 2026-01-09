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
        
        $query = $this->getBaseQueryForUser($user)->with(['department', 'church', 'creator']);

        // 1. Filter by Name (Search)
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // 2. Filter by Church
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }

        // 3. Filter by Status (Main Status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Filter by Approval Status (if applicable to filter usually logic handles tabs, but we can add filter)
        // Tabs logic:
        $tab = $request->input('tab', 'overview');
        if ($tab === 'approvals' && $user->can('approve activities')) {
            $query->where('approval_status', 'pending');
        } elseif ($tab === 'my_activities') {
             // If I am a creator or responsible person?
             // Let's rely on "BaseQuery" for visibility scope, but "My Activities" tab usually emphasizes "Created by Me".
             $query->where('created_by', $user->id);
        } 

        // 5. Date Range Filter
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // 6. Department Filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $activities = $query->latest()->paginate(10)->withQueryString();

        $departments = $this->getDepartmentsForUser($user);
        $churches = $this->getChurchesForUser($user);

        // Stats
        $baseStatsQuery = $this->getBaseQueryForUser($user);
        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'pending_approval' => (clone $baseStatsQuery)->where('approval_status', 'pending')->count(), // Fixed Key
            'approved' => (clone $baseStatsQuery)->where('approval_status', 'approved')->count(),
            'completed' => (clone $baseStatsQuery)->where('status', 'completed')->count(),
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
            'budget_estimate' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Determine Approval Status
        // Users with 'approve activities' permission are auto-approved.
        $user = auth()->user();
        $approvalStatus = $user->can('approve activities') ? 'approved' : 'pending';

        $activity = Activity::create([
            'department_id' => $validated['department_id'],
            'church_id' => $validated['church_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'responsible_person' => $validated['responsible_person'] ?? null,
            'target' => $validated['target'],
            'budget_estimate' => $validated['budget_estimate'] ?? 0,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'approval_status' => $approvalStatus,
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
        
        if ($approvalStatus === 'pending') {
            $message = "New activity '{$activity->name}' submitted by {$user->name} pending approval.";
            
            // Notify Bosses/Those who can approve
            // Ideally notify filtering by permission, but Role 'boss' is safe fallback for now + Archids
            $bosses = \App\Models\User::role('boss')->get();
            \Illuminate\Support\Facades\Notification::send($bosses, new \App\Notifications\ActivityStatusChanged($activity, $message));

            // Notify Assigned Archdeacon
            $church = \App\Models\Church::find($validated['church_id']);
            if ($church && $church->archid_id) {
                $archidUser = \App\Models\User::find($church->archid_id);
                if ($archidUser) {
                    $archidUser->notify(new \App\Notifications\ActivityStatusChanged($activity, $message));
                }
            }
        }
        
        $msg = $approvalStatus === 'pending' ? 'Activity submitted for approval.' : 'Activity created and approved.';
        return redirect()->route('activities.index')->with('success', $msg);
    }

    public function show(Activity $activity)
    {
        $this->checkActivityScope($activity);
        $activity->load(['department', 'church', 'indicators', 'documents']);
        return view('activities.show', compact('activity'));
    }
    
    // START: Approval Workflow Methods
    public function approve(Activity $activity)
    {
        $this->authorize('approve activities');
        $this->checkActivityScope($activity);

        $activity->update([
            'approval_status' => 'approved',
            'status' => 'in_progress'
        ]);

        if ($activity->creator) {
            $activity->creator->notify(new \App\Notifications\ActivityStatusChanged($activity, "Your activity '{$activity->name}' has been approved and started."));
        }

        return back()->with('success', 'Activity approved and started.');
    }

    public function reject(Activity $activity)
    {
        $this->authorize('approve activities');
        $this->checkActivityScope($activity);

        $activity->update(['approval_status' => 'rejected']);

        if ($activity->creator) {
            $activity->creator->notify(new \App\Notifications\ActivityStatusChanged($activity, "Your activity '{$activity->name}' has been rejected."));
        }

        return back()->with('success', 'Activity rejected.');
    }
    
    public function markComplete(Request $request, Activity $activity)
    {
        $this->authorize('edit activities');
        $this->checkActivityScope($activity);

        $validated = $request->validate([
            'completion_summary' => 'required|string',
            'attendance_count' => 'nullable|integer',
            'salvation_count' => 'nullable|integer',
            'financial_spent' => 'nullable|numeric|min:0',
        ]);

        $activity->update([
            'status' => 'completed',
            'current_progress' => $activity->target,
            'completion_summary' => $validated['completion_summary'],
            'attendance_count' => $validated['attendance_count'],
            'salvation_count' => $validated['salvation_count'],
            'financial_spent' => $validated['financial_spent'],
        ]);

        return back()->with('success', 'Activity marked as completed.');
    }
    // END: Approval Workflow values

    public function edit(Activity $activity)
    {
        $this->authorize('edit activities');
        $this->checkActivityScope($activity);
        
        $departments = $this->getDepartmentsForUser(auth()->user());
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('activities.edit', compact('activity', 'departments', 'churches'));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('edit activities');
        $this->checkActivityScope($activity);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'target' => 'required|integer|min:0',
            'current_progress' => 'required|integer|min:0',
            'budget_estimate' => 'nullable|numeric|min:0',
            'financial_spent' => 'nullable|numeric|min:0',
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
        $this->authorize('delete activities');
        $this->checkActivityScope($activity);

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully!');
    }

    private function getDepartmentsForUser($user)
    {
        if ($user->can('view all departments')) {
            return Department::where('is_active', true)->get();
        } elseif ($user->can('view assigned departments') || $user->can('view assigned activities')) {
             // Assuming permissions align, usually archids see departments in their churches
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return Department::whereIn('church_id', $churchIds)->where('is_active', true)->get();
        } else {
            // Default to own church
            return Department::where('church_id', $user->church_id)->where('is_active', true)->get();
        }
    }

    private function getChurchesForUser($user)
    {
        if ($user->can('view all churches')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->can('view assigned churches') || $user->can('view assigned activities')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } elseif ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        return collect();
    }
    
    private function getBaseQueryForUser($user)
    {
        $baseQuery = Activity::query();
        if ($user->can('view all activities')) {
             // all
        } elseif ($user->can('view assigned activities')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $baseQuery->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own activities') && $user->church_id) {
            $baseQuery->where('church_id', $user->church_id);
        } else {
            $baseQuery->where('id', 0);
        }
        return $baseQuery;
    }

    private function checkActivityScope(Activity $activity)
    {
        $user = auth()->user();

        // 1. Can view everything?
        if ($user->can('view all activities')) {
            return;
        }

        // 2. Can view assigned (Archdeacon)?
        if ($user->can('view assigned activities')) {
            // Must belong to a church assigned to this user
            if ($activity->church && $activity->church->archid_id === $user->id) {
                return;
            }
        }

        // 3. Can view own (Pastor)?
        if ($user->can('view own activities')) {
            // Must belong to the user's church
            if ($activity->church_id === $user->church_id) {
                return;
            }
        }

        // 4. Fallback: If I created it, maybe I can see it? 
        // Usually good practice, but permissions "view own activities" usually covers "My Church".
        // Let's allow creator access safe-guard.
        if ($activity->created_by === $user->id) {
             return;
        }

        abort(403, 'Unauthorized access to this activity.');
    }
}
