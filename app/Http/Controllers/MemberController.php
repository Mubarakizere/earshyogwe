<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base Query - load church relationship and church groups
        $query = Member::with(['church', 'churchGroups']);

        // 1. Role Scoping
        if ($user->can('view all members')) {
             // Admin/Boss: Sees all, but can filter by church
             if ($request->has('church_id') && $request->church_id) {
                 $query->where('church_id', $request->church_id);
             }
        } elseif ($user->can('view assigned members')) {
             // Archid: Sees their region, can filter by assigned churches
             $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
             
             if ($request->has('church_id') && $request->church_id && in_array($request->church_id, $assignedChurchIds)) {
                 $query->where('church_id', $request->church_id);
             } else {
                 $query->whereIn('church_id', $assignedChurchIds);
             }
        } elseif ($user->can('view own members') && $user->church_id) {
             // Pastor: Sees only own church
             $query->where('church_id', $user->church_id);
        } else {
            abort(403, 'Unauthorized access to members.');
        }

        // 2. Search & Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        if ($request->filled('status')) {
            $query->where('marital_status', $request->status);
        }

        // Filter by member status (active/inactive/deceased)
        if ($request->filled('member_status')) {
            $query->where('status', $request->member_status);
        }

        // Clone query for REAL-TIME stats (based on current filters)
        $stats = [
            'total' => (clone $query)->count(),
            'male' => (clone $query)->where('sex', 'Male')->count(),
            'female' => (clone $query)->where('sex', 'Female')->count(),
            'baptized' => (clone $query)->where('baptism_status', 'Baptized')->count(),
        ];

        $members = $query->latest()->paginate(15)->withQueryString();

        // Data for Filters (Role Dependent)
        $churches = collect();
        if ($user->can('view all churches')) {
            $churches = Church::orderBy('name')->get();
        } elseif ($user->can('view assigned churches')) {
            $churches = Church::where('archid_id', $user->id)->orderBy('name')->get();
        }
        
        return view('members.index', compact('members', 'stats', 'churches'));
    }

    public function show(Member $member)
    {
        $this->authorize('view all members'); // Or specific view permission
        
        $user = auth()->user();
        // Additional scope check for show
        if ($user->can('view own members') && !$user->can('view all members') && !$user->can('view assigned members') && $user->church_id != $member->church_id) {
             abort(403);
        }
        
        // Load church groups relationship
        $member->load('churchGroups');
        
        return view('members.show', compact('member'));
    }

    public function export()
    {
        $user = auth()->user();
        
        // Scope Logic (Same as Index)
        if ($user->can('view all members')) {
             $query = Member::with('church');
        } elseif ($user->can('view assigned members')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query = Member::with('church')->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own members') && $user->church_id) {
             $query = Member::with('church')->where('church_id', $user->church_id);
        } else {
            abort(403);
        }
        
        $members = $query->latest()->get();
        $filename = "members_export_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($members) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($file, ['Name', 'Sex', 'DOB', 'Age', 'Marital Status', 'Church', 'Group', 'Education', 'Baptism']);

            foreach ($members as $member) {
                fputcsv($file, [
                    $member->name,
                    $member->sex,
                    $member->dob ? $member->dob->format('Y-m-d') : '',
                    $member->age,
                    $member->marital_status,
                    $member->church->name,
                    $member->church_group,
                    $member->education_level,
                    $member->baptism_status,
                ]);
            }
            fclose($file);
        }, $filename);
    }

    public function create()
    {
        $this->authorize('create members');
        
        $user = auth()->user();
        $churches = collect();

        if ($user->can('view all churches')) {
            $churches = Church::all();
        } elseif ($user->can('view assigned churches')) {
            $churches = Church::where('archid_id', $user->id)->get();
        } elseif ($user->church_id) {
             $churches = Church::where('id', $user->church_id)->get();
        }

        $churchGroups = \App\Models\ChurchGroup::orderBy('name')->get();

        return view('members.create', compact('churches', 'churchGroups'));
    }

    public function store(Request $request)
    {
        $this->authorize('create members');

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'dob' => 'nullable|date',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'parental_status' => 'required|in:Orphan,Living with both parents,Living with one parent,Under guardian/Caregiver',
            'baptism_status' => 'required|in:Baptized,Confirmed,None',
            'church_group' => 'nullable|string',
            'education_level' => 'nullable|string',
            'extra_attributes' => 'nullable|array',
            'church_groups' => 'nullable|array',
            'church_groups.*' => 'exists:church_groups,id',
        ]);

        $churchGroupIds = $validated['church_groups'] ?? [];
        unset($validated['church_groups']);

        $member = Member::create($validated);
        
        // Attach church groups
        if (!empty($churchGroupIds)) {
            $member->churchGroups()->attach($churchGroupIds);
        }

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    public function edit(Member $member)
    {
        $this->authorize('edit members');
        
        // Check scope for edit (e.g. Pastor can only edit own members)
        $user = auth()->user();
        if ($user->can('edit members') && !$user->can('view all members') && !$user->can('view assigned members') && $user->church_id != $member->church_id) {
             abort(403);
        }

        $churches = Church::all();
        $churchGroups = \App\Models\ChurchGroup::orderBy('name')->get();
        $member->load('churchGroups');
        
        return view('members.edit', compact('member', 'churches', 'churchGroups'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorize('edit members');

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'dob' => 'nullable|date',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'parental_status' => 'required|in:Orphan,Living with both parents,Living with one parent,Under guardian/Caregiver',
            'baptism_status' => 'required|in:Baptized,Confirmed,None',
            'church_group' => 'nullable|string',
            'education_level' => 'nullable|string',
            'extra_attributes' => 'nullable|array',
            'status' => 'required|in:active,inactive,deceased',
            'inactive_reason' => 'required_if:status,inactive|nullable|string',
            'inactive_date' => 'nullable|date',
            'deceased_date' => 'required_if:status,deceased|nullable|date',
            'deceased_cause' => 'nullable|string',
            'church_groups' => 'nullable|array',
            'church_groups.*' => 'exists:church_groups,id',
        ]);

        $churchGroupIds = $validated['church_groups'] ?? [];
        unset($validated['church_groups']);

        $member->update($validated);
        
        // Sync church groups
        $member->churchGroups()->sync($churchGroupIds);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $this->authorize('delete members');
        
        $member->delete();
        return redirect()->back()->with('success', 'Member deleted successfully.');
    }
}
