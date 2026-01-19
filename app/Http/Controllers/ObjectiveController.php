<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Department;
use App\Models\Church;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = $this->getBaseQueryForUser($user)->with(['department', 'church', 'creator']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $objectives = $query->get();

        $filename = "objectives_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        // Output headers so the browser downloads it
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, [
            'Name', 
            'Church', 
            'Department', 
            'Status', 
            'Approval Status', 
            'Target', 
            'Progress', 
            'Start Date', 
            'End Date'
        ]);

        foreach ($objectives as $objective) {
            fputcsv($handle, [
                $objective->name,
                $objective->church->name ?? 'N/A',
                $objective->department->name ?? 'N/A',
                ucfirst(str_replace('_', ' ', $objective->status)),
                ucfirst($objective->approval_status),
                $objective->target,
                $objective->current_progress, // Calculated attribute
                $objective->start_date->format('Y-m-d'),
                $objective->end_date ? $objective->end_date->format('Y-m-d') : '-'
            ]);
        }

        fclose($handle);
        exit;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get base query based on user permissions
        $query = $this->getBaseQueryForUser($user);
        
        // Get stats for the current user's visible objectives
        $stats = [
            'total' => (clone $query)->count(),
            'pending_approval' => (clone $query)->where('approval_status', 'pending')->count(),
            'approved' => (clone $query)->where('approval_status', 'approved')->where('status', 'in_progress')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];
        
        // Apply tab filtering
        $tab = $request->get('tab', 'my_objectives');
        
        if ($tab === 'my_objectives') {
            // Show only objectives from user's church
            if ($user->church_id) {
                $query->where('church_id', $user->church_id);
            }
        } elseif ($tab === 'approvals') {
            // Only for users who can approve
            if ($user->can('approve objectives')) { // Keeping permission name for now
                $query->where('approval_status', 'pending');
            }
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('objectives', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply priority filter
        if ($request->filled('priority')) {
            $query->where('priority_level', $request->priority);
        }
        
        // Apply church filter
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        
        // Apply department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Get objectives with relationships
        $objectives = $query->with(['church', 'department', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());
        
        // Get churches visible to this user for filter dropdown
        $churches = $this->getVisibleChurches($user);
        $departments = $this->getDepartmentsForUser($user);
        
        return view('objectives.index', compact('objectives', 'stats', 'churches', 'departments'));
    }

    public function create()
    {
        $this->authorize('create objectives');
        
        $departments = $this->getDepartmentsForUser(auth()->user());
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('objectives.create', compact('departments', 'churches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create objectives');
        
        $allowedDepartments = $this->getDepartmentsForUser(auth()->user());
        
        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($allowedDepartments) {
                    if (!$allowedDepartments->contains('id', $value)) {
                        $fail('The selected department is invalid or you do not have permission for it.');
                    }
                },
            ],
            'church_id' => 'required|exists:churches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target' => 'required|integer|min:0',
            'target_unit' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            
            // Enhanced Fields
            'activity_category' => 'nullable|string|max:50',
            'priority_level' => 'required|in:low,medium,high,critical',
            'objectives' => 'nullable|string', // The detailed text objectives
            'target_beneficiaries' => 'nullable|string',
            'expected_outcomes' => 'nullable|string',
            'funding_source' => 'nullable|string|max:100',
            'tracking_frequency' => 'required|in:daily,weekly,biweekly,monthly',
            'risk_assessment' => 'nullable|string',
            'mitigation_plan' => 'nullable|string',
        ]);

        $user = auth()->user();
        $approvalStatus = $user->can('approve objectives') ? 'approved' : 'pending';
        
        $duration_days = null;
        if ($validated['start_date'] && $validated['end_date']) {
            $start = \Carbon\Carbon::parse($validated['start_date']);
            $end = \Carbon\Carbon::parse($validated['end_date']);
            $duration_days = $start->diffInDays($end);
        }

        $objective = \App\Models\Objective::create([
            'department_id' => $validated['department_id'],
            'church_id' => $validated['church_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'target' => $validated['target'],
            'target_unit' => $validated['target_unit'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'approval_status' => $approvalStatus,
            'created_by' => auth()->id(),
            // Enhanced Fields
            'activity_category' => $validated['activity_category'] ?? null,
            'priority_level' => $validated['priority_level'],
            'objectives' => $validated['objectives'] ?? null,
            'target_beneficiaries' => $validated['target_beneficiaries'] ?? null,
            'expected_outcomes' => $validated['expected_outcomes'] ?? null,
            'funding_source' => $validated['funding_source'] ?? null,
            'tracking_frequency' => $validated['tracking_frequency'],
            'risk_assessment' => $validated['risk_assessment'] ?? null,
            'mitigation_plan' => $validated['mitigation_plan'] ?? null,
            'duration_days' => $duration_days,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('objective-documents', 'public');
                $objective->documents()->create([
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        // Notifications (kept simpler for brevity, logic same as before but referring to Objective)
        
        $msg = $approvalStatus === 'pending' ? 'Objective submitted for approval.' : 'Objective created and approved.';
        return redirect()->route('objectives.index')->with('success', $msg);
    }

    public function show(\App\Models\Objective $objective)
    {
        $this->checkObjectiveScope($objective);
        $objective->load(['department', 'church', 'indicators', 'documents', 'reports']);
        return view('objectives.show', compact('objective'));
    }
    
    public function approve(\App\Models\Objective $objective)
    {
        $this->authorize('approve objectives');
        $this->checkObjectiveScope($objective);

        $objective->update([
            'approval_status' => 'approved',
            'status' => 'in_progress'
        ]);

        return back()->with('success', 'Objective approved and started.');
    }

    public function reject(\App\Models\Objective $objective)
    {
        $this->authorize('approve objectives');
        $this->checkObjectiveScope($objective);

        $objective->update(['approval_status' => 'rejected']);

        return back()->with('success', 'Objective rejected.');
    }
    
    public function edit(\App\Models\Objective $objective)
    {
        $this->authorize('edit objectives');
        $this->checkObjectiveScope($objective);
        
        $departments = $this->getDepartmentsForUser(auth()->user());
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('objectives.edit', compact('objective', 'departments', 'churches'));
    }

    public function update(Request $request, \App\Models\Objective $objective)
    {
        $this->authorize('edit objectives');
        $this->checkObjectiveScope($objective);

        $allowedDepartments = $this->getDepartmentsForUser(auth()->user());

        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($allowedDepartments) {
                    if (!$allowedDepartments->contains('id', $value)) {
                        $fail('The selected department is invalid or you do not have permission for it.');
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target' => 'required|integer|min:0',
            'target_unit' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            // Enhanced Fields
            'activity_category' => 'nullable|string|max:50',
            'priority_level' => 'required|in:low,medium,high,critical',
            'objectives' => 'nullable|string',
            'target_beneficiaries' => 'nullable|string',
            'expected_outcomes' => 'nullable|string',
            'funding_source' => 'nullable|string|max:100',
            'tracking_frequency' => 'required|in:daily,weekly,biweekly,monthly',
            'risk_assessment' => 'nullable|string',
            'mitigation_plan' => 'nullable|string',
        ]);

        $objective->update($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('objective-documents', 'public');
                $objective->documents()->create([
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        return redirect()->route('objectives.index')
            ->with('success', 'Objective updated successfully!');
    }

    public function destroy(\App\Models\Objective $objective)
    {
        $this->authorize('delete objectives');
        $this->checkObjectiveScope($objective);

        $objective->delete();

        return redirect()->route('objectives.index')
            ->with('success', 'Objective deleted successfully!');
    }

    private function getDepartmentsForUser($user)
    {
        // 1. Super Admin get all active departments
        if ($user->can('view all objectives') || $user->can('view all departments')) {
            return Department::where('is_active', true)->orderBy('name')->get();
        }

        // 2. Head of department or specific department permissions
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $deptSlugs = [];
        foreach ($permissions as $perm) {
            if (str_starts_with($perm, 'view ') && (str_ends_with($perm, ' objectives') || str_ends_with($perm, ' activities'))) {
                $slug = str_replace(['view ', ' objectives', ' activities'], '', $perm);
                if (!in_array($slug, ['all', 'assigned', 'own'])) {
                    $deptSlugs[] = $slug;
                }
            }
        }

        if (!empty($deptSlugs)) {
            return Department::whereIn('slug', $deptSlugs)->where('is_active', true)->orderBy('name')->get();
        }

        // 3. ARCHID get departments in their region
        if ($user->can('view assigned objectives')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return Department::whereIn('church_id', $churchIds)->where('is_active', true)->orderBy('name')->get();
        }

        // 4. Default: User's own church departments
        return Department::where('church_id', $user->church_id)->where('is_active', true)->orderBy('name')->get();
    }

    private function getChurchesForUser($user)
    {
        // Reusing existing logic...
        if ($user->can('view all churches')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->can('view assigned churches') || $user->can('view assigned objectives')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } elseif ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        return collect();
    }
    
    private function getBaseQueryForUser($user)
    {
        $baseQuery = \App\Models\Objective::query();
        
        if ($user->can('view all objectives')) {
            return $baseQuery;
        }
        
        $allowedDepartmentIds = [];
        
        // 1. Check for department-specific permissions
        // Optimize by getting all relevant permissions at once
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $deptSlugs = [];
        foreach ($permissions as $perm) {
        if (str_starts_with($perm, 'view ') && (str_ends_with($perm, ' objectives') || str_ends_with($perm, ' activities'))) {
            // Extract slug from "view {slug} objectives" or "view {slug} activities"
            $slug = str_replace(['view ', ' objectives', ' activities'], '', $perm);
            if ($slug !== 'all' && $slug !== 'assigned' && $slug !== 'own') {
                $deptSlugs[] = $slug;
            }
        }
    }

        if (!empty($deptSlugs)) {
            $allowedDepartmentIds = Department::whereIn('slug', $deptSlugs)->pluck('id')->toArray();
        }
        
        // 2. Build the query based on permissions
        return $baseQuery->where(function ($q) use ($user, $allowedDepartmentIds) {
            // Department specific access
            if (!empty($allowedDepartmentIds)) {
                $q->orWhereIn('department_id', $allowedDepartmentIds);
            }

            // Assigned objectives (ARCHID)
            if ($user->can('view assigned objectives')) {
                $churchIds = Church::where('archid_id', $user->id)->pluck('id');
                $q->orWhereIn('church_id', $churchIds);
            }

            // Own objectives (Pastor/User in church)
            if ($user->can('view own objectives') && $user->church_id) {
                $q->orWhere('church_id', $user->church_id);
            }

            // Creator access
            $q->orWhere('created_by', $user->id);
        });
    }
    
    private function getVisibleChurches($user)
    {
        if ($user->can('view all objectives')) {
            return Church::orderBy('name')->get();
        }
        
        if ($user->can('view assigned objectives')) {
            return Church::where('archid_id', $user->id)->orderBy('name')->get();
        }
        
        if ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        
        return collect();
    }

    private function checkObjectiveScope(\App\Models\Objective $objective)
    {
        $user = auth()->user();

        if ($user->can('view all objectives')) {
            return;
        }

        // Check department specific permission
        if ($objective->department_id) {
            $dept = $objective->department()->first();
            if ($dept && ($user->can($dept->permission_name) || $user->can("view {$dept->slug} activities"))) {
                return;
            }
        }

        if ($user->can('view assigned objectives')) {
            if ($objective->church && $objective->church->archid_id === $user->id) {
                return;
            }
        }

        if ($user->can('view own objectives')) {
            if ($objective->church_id === $user->church_id) {
                return;
            }
        }

        if ($objective->created_by === $user->id) {
             return;
        }

        abort(403, 'Unauthorized access to this objective.');
    }
}
