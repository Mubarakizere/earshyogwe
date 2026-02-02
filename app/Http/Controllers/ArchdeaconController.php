<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\User;
use Illuminate\Http\Request;

class ArchdeaconController extends Controller
{
    /**
     * Display a listing of archdeacons and their assignments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user has permission to manage archdeacons
        if (!auth()->user()->can('manage users') && !auth()->user()->can('view all churches')) {
            abort(403, 'Unauthorized access to manage archdeacons.');
        }

        // Get all users with archid role
        $query = User::role('archid')->with(['supervisedChurches']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $archdeacons = $query->get()->map(function($archdeacon) {
            return [
                'id' => $archdeacon->id,
                'name' => $archdeacon->name,
                'email' => $archdeacon->email,
                'profile_photo_url' => $archdeacon->profile_photo_url,
                'churches_count' => $archdeacon->supervisedChurches->count(),
                'churches' => $archdeacon->supervisedChurches,
            ];
        });

        // Stats
        $stats = [
            'total_archdeacons' => $archdeacons->count(),
            'total_assignments' => $archdeacons->sum('churches_count'),
            'unassigned_churches' => Church::whereNull('archid_id')->count(),
        ];

        return view('archdeacons.index', compact('archdeacons', 'stats'));
    }

    /**
     * Show the form for editing archdeacon's church assignments.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function edit($userId)
    {
        // Check permission
        if (!auth()->user()->can('manage users') && !auth()->user()->can('view all churches')) {
            abort(403, 'Unauthorized access to manage archdeacons.');
        }

        $archdeacon = User::findOrFail($userId);
        
        // Verify user has archid role
        if (!$archdeacon->hasRole('archid')) {
            abort(404, 'User is not an archdeacon.');
        }

        // Get all churches and the archdeacon's current assignments
        $allChurches = Church::orderBy('name')->get();
        $assignedChurchIds = $archdeacon->supervisedChurches->pluck('id')->toArray();

        return view('archdeacons.edit', compact('archdeacon', 'allChurches', 'assignedChurchIds'));
    }

    /**
     * Update archdeacon's church assignments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        // Check permission
        if (!auth()->user()->can('manage users') && !auth()->user()->can('view all churches')) {
            abort(403, 'Unauthorized access to manage archdeacons.');
        }

        $archdeacon = User::findOrFail($userId);
        
        // Verify user has archid role
        if (!$archdeacon->hasRole('archid')) {
            abort(404, 'User is not an archdeacon.');
        }

        $validated = $request->validate([
            'church_ids' => 'nullable|array',
            'church_ids.*' => 'exists:churches,id',
        ]);

        // Get current assignments
        $currentAssignments = $archdeacon->supervisedChurches->pluck('id')->toArray();
        $newAssignments = $validated['church_ids'] ?? [];

        // Remove archdeacon from churches that are no longer assigned
        $toRemove = array_diff($currentAssignments, $newAssignments);
        if (!empty($toRemove)) {
            Church::whereIn('id', $toRemove)
                ->where('archid_id', $archdeacon->id)
                ->update(['archid_id' => null]);
        }

        // Assign archdeacon to new churches
        $toAdd = array_diff($newAssignments, $currentAssignments);
        if (!empty($toAdd)) {
            Church::whereIn('id', $toAdd)
                ->update(['archid_id' => $archdeacon->id]);
        }

        return redirect()
            ->route('archdeacons.index')
            ->with('success', 'Archdeacon assignments updated successfully.');
    }
}
