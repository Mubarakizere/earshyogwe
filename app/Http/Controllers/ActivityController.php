<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Department;
use App\Models\Church;
use Illuminate\Http\Request;

class ActivityController extends Controller
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

        $activities = $query->get();

        $filename = "activities_export_" . date('Y-m-d') . ".csv";
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
            'End Date', 
            'Budget Estimate', 
            'Financial Spent'
        ]);

        foreach ($activities as $activity) {
            fputcsv($handle, [
                $activity->name,
                $activity->church->name ?? 'N/A',
                $activity->department->name ?? 'N/A',
                ucfirst(str_replace('_', ' ', $activity->status)),
                ucfirst($activity->approval_status),
                $activity->target,
                $activity->current_progress,
                $activity->start_date->format('Y-m-d'),
                $activity->end_date ? $activity->end_date->format('Y-m-d') : '-',
                $activity->budget_estimate,
                $activity->financial_spent
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
        
        // Get stats for the current user's visible activities
        $stats = [
            'total' => (clone $query)->count(),
            'pending_approval' => (clone $query)->where('approval_status', 'pending')->count(),
            'approved' => (clone $query)->where('approval_status', 'approved')->where('status', 'in_progress')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];
        
        // Apply tab filtering
        $tab = $request->get('tab', 'my_activities');
        
        if ($tab === 'my_activities') {
            // Show only activities from user's church
            if ($user->church_id) {
                $query->where('church_id', $user->church_id);
            }
        } elseif ($tab === 'approvals') {
            // Only for users who can approve
            if ($user->can('approve activities')) {
                $query->where('approval_status', 'pending');
            }
        }
        // 'overview' tab shows all based on permission (no additional filter)
        
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
        
        // Get activities with relationships
        $activities = $query->with(['church', 'department', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());
        
        // Get churches visible to this user for filter dropdown
        $churches = $this->getVisibleChurches($user);
        
        return view('activities.index', compact('activities', 'stats', 'churches'));
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
            // Phase 1 Enhanced Fields
            'activity_category' => 'nullable|string|max:50',
            'priority_level' => 'required|in:low,medium,high,critical',
            'objectives' => 'nullable|string',
            'target_beneficiaries' => 'nullable|string',
            'expected_outcomes' => 'nullable|string',
            'target_unit' => 'nullable|string|max:50',
            'funding_source' => 'nullable|string|max:100',
            'tracking_frequency' => 'required|in:daily,weekly,biweekly,monthly',
            'risk_assessment' => 'nullable|string',
            'mitigation_plan' => 'nullable|string',
            'location_name' => 'nullable|string|max:255',
            'location_address' => 'nullable|string',
            'location_latitude' => 'nullable|numeric|between:-90,90',
            'location_longitude' => 'nullable|numeric|between:-180,180',
            'location_region' => 'nullable|string|max:100',
        ]);

        // Determine Approval Status
        // Users with 'approve activities' permission are auto-approved.
        $user = auth()->user();
        $approvalStatus = $user->can('approve activities') ? 'approved' : 'pending';
        
        // Calculate duration in days
        $duration_days = null;
        if ($validated['start_date'] && $validated['end_date']) {
            $start = \Carbon\Carbon::parse($validated['start_date']);
            $end = \Carbon\Carbon::parse($validated['end_date']);
            $duration_days = $start->diffInDays($end);
        }

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
            // Phase 1 Enhanced Fields
            'activity_category' => $validated['activity_category'] ?? null,
            'priority_level' => $validated['priority_level'],
            'objectives' => $validated['objectives'] ?? null,
            'target_beneficiaries' => $validated['target_beneficiaries'] ?? null,
            'expected_outcomes' => $validated['expected_outcomes'] ?? null,
            'target_unit' => $validated['target_unit'] ?? null,
            'funding_source' => $validated['funding_source'] ?? null,
            'tracking_frequency' => $validated['tracking_frequency'],
            'risk_assessment' => $validated['risk_assessment'] ?? null,
            'mitigation_plan' => $validated['mitigation_plan'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'location_address' => $validated['location_address'] ?? null,
            'location_latitude' => $validated['location_latitude'] ?? null,
            'location_longitude' => $validated['location_longitude'] ?? null,
            'location_region' => $validated['location_region'] ?? null,
            'duration_days' => $duration_days,
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('activity-documents', 'public');
                $activity->documents()->create([
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        // Handle custom field values
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $fieldId => $value) {
                if ($value !== null && $value !== '') {
                    \App\Models\ActivityCustomValue::create([
                        'activity_id' => $activity->id,
                        'custom_field_definition_id' => $fieldId,
                        'field_value' => $value,
                    ]);
                }
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
        
        // Notify department head
        $department = Department::find($validated['department_id']);
        if ($department && $department->head_id) {
            $departmentHead = \App\Models\User::find($department->head_id);
            if ($departmentHead) {
                $headMessage = "New activity '{$activity->name}' created for {$department->name} department.";
                $departmentHead->notify(new \App\Notifications\ActivityStatusChanged($activity, $headMessage));
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
    
    // Progress Logging
    public function addProgressLog(Request $request, Activity $activity)
    {
        \Log::info('=== Progress Log Submission Started ===');
        \Log::info('Activity ID: ' . $activity->id);
        \Log::info('User ID: ' . auth()->id());
        \Log::info('Request Data: ', $request->all());
        
        try {
            \Log::info('Checking authorization...');
            $this->authorize('log activity progress');
            \Log::info('Authorization passed!');
            
            \Log::info('Checking activity scope...');
            $this->checkActivityScope($activity);
            \Log::info('Activity scope check passed!');
        } catch (\Exception $e) {
            \Log::error('Authorization or scope check failed: ' . $e->getMessage());
            return back()->with('error', 'Authorization failed: ' . $e->getMessage());
        }

        \Log::info('Starting validation...');
        
        try {
            $validated = $request->validate([
                'log_date' => 'required|date',
                'progress_value' => 'required|integer|min:0',
                'notes' => 'nullable|string',
                'photos' => 'nullable|array',
                'photos.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240', // 10MB max
            ]);
            \Log::info('Validation passed!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed!');
            \Log::error('Validation errors: ', $e->errors());
            throw $e; // Re-throw to show errors to user
        }

        \Log::info('Validated Data: ', $validated);

        // Calculate cumulative progress (sum all previous logs + this new value)
        $previousTotal = \App\Models\ActivityProgressLog::where('activity_id', $activity->id)
            ->where('log_date', '<', $validated['log_date'])
            ->sum('progress_value');
        
        $cumulativeProgress = $previousTotal + $validated['progress_value'];
        
        \Log::info('Previous total: ' . $previousTotal);
        \Log::info('This period amount: ' . $validated['progress_value']);
        \Log::info('New cumulative total: ' . $cumulativeProgress);

        // Calculate progress percentage based on cumulative total
        $progress_percentage = $activity->target > 0 
            ? min(100, round(($cumulativeProgress / $activity->target) * 100, 2))
            : 0;

        \Log::info('Calculated Percentage: ' . $progress_percentage);

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            \Log::info('Processing ' . count($request->file('photos')) . ' photos');
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('activity-progress', 'public');
                $photoPaths[] = $path;
                \Log::info('Photo uploaded: ' . $path);
            }
        }

        \Log::info('Photo Paths: ', $photoPaths);

        // Prepare data for insertion
        $logData = [
            'activity_id' => $activity->id,
            'logged_by' => auth()->id(),
            'log_date' => $validated['log_date'],
            'progress_value' => $validated['progress_value'], // Amount added this period
            'progress_percentage' => $progress_percentage, // Percentage based on cumulative
            'notes' => $validated['notes'] ?? null,
            'photos' => $photoPaths,
        ];

        \Log::info('Data to insert: ', $logData);

        try {
            // Create progress log
            $progressLog = \App\Models\ActivityProgressLog::create($logData);
            \Log::info('Progress log created successfully. ID: ' . $progressLog->id);

            // Update activity's current progress to cumulative total
            $updateData = ['current_progress' => $cumulativeProgress];
            
            // Auto-complete when target is reached
            if ($cumulativeProgress >= $activity->target && $activity->status !== 'completed') {
                $updateData['status'] = 'completed';
                \Log::info('Target reached! Auto-completing activity.');
            }
            
            $activity->update($updateData);
            \Log::info('Activity current_progress updated to: ' . $cumulativeProgress . ' (cumulative total)');

            \Log::info('=== Progress Log Submission Completed Successfully ===');
            
            $message = 'Progress log added successfully. Total progress: ' . number_format($cumulativeProgress) . ' ' . $activity->target_unit;
            if (isset($updateData['status'])) {
                $message .= ' 🎉 Activity completed!';
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error creating progress log: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to add progress log: ' . $e->getMessage());
        }
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
    
    /**
     * Get base query filtered by user's permissions
     */
    private function getBaseQueryForUser($user)
    {
        $baseQuery = Activity::query();
        
        // Boss sees everything
        if ($user->can('view all activities')) {
            return $baseQuery;
        }
        
        // Check for department-specific permissions
        $allowedDepartmentIds = [];
        $departments = Department::all();
        
        foreach ($departments as $dept) {
            $permissionName = "view {$dept->slug} activities";
            if ($user->can($permissionName)) {
                $allowedDepartmentIds[] = $dept->id;
            }
        }
        
        // If user has department permissions, show those activities
        if (!empty($allowedDepartmentIds)) {
            return $baseQuery->whereIn('department_id', $allowedDepartmentIds);
        }
        
        // Archid sees activities from their assigned churches
        if ($user->can('view assigned activities')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return $baseQuery->whereIn('church_id', $churchIds);
        }
        
        // Pastor sees only their own church's activities
        if ($user->can('view own activities') && $user->church_id) {
            return $baseQuery->where('church_id', $user->church_id);
        }
        
        // No permission - return empty query
        return $baseQuery->where('id', 0);
    }
    
    /**
     * Get churches visible to the user
     */
    private function getVisibleChurches($user)
    {
        if ($user->can('view all activities')) {
            return Church::orderBy('name')->get();
        }
        
        if ($user->can('view assigned activities')) {
            return Church::where('archid_id', $user->id)->orderBy('name')->get();
        }
        
        if ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        
        return collect();
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
