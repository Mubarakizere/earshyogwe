<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\Member;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base Query - load church relationship and church groups
        $query = Member::with(['church', 'churchGroups', 'recordedBy']);

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
             // Pastor: Sees only members they recorded AND their church
             $query->where('church_id', $user->church_id)
                   ->where('recorded_by', $user->id);
        } else {
            abort(403, 'Unauthorized access to members.');
        }

        // 2. Search & Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
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

        // Filter by chapel
        if ($request->filled('chapel')) {
            $query->where('chapel', 'like', '%' . $request->chapel . '%');
        }

        // Filter by disability (yes/no)
        if ($request->filled('has_disability')) {
            if ($request->has_disability === 'yes') {
                $query->whereNotNull('disability')->where('disability', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('disability')->orWhere('disability', '');
                });
            }
        }

        // Clone query for REAL-TIME stats (based on current filters)
        $stats = [
            'total' => (clone $query)->count(),
            'male' => (clone $query)->where('sex', 'Male')->count(),
            'female' => (clone $query)->where('sex', 'Female')->count(),
            'baptized' => (clone $query)->where('baptism_status', 'Baptized')->count(),
        ];
        
        // Date of Birth Filter
        if ($request->filled('dob_from')) {
            $query->whereDate('dob', '>=', $request->dob_from);
        }

        if ($request->filled('dob_to')) {
            $query->whereDate('dob', '<=', $request->dob_to);
        }

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
        $user = auth()->user();
        
        // Check access permissions
        if ($user->can('view all members')) {
            // Admin can view all
        } elseif ($user->can('view assigned members')) {
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            if (!in_array($member->church_id, $assignedChurchIds)) {
                abort(403);
            }
        } elseif ($user->can('view own members') && $user->church_id) {
            if ($member->church_id != $user->church_id || $member->recorded_by != $user->id) {
                abort(403);
            }
        } else {
            abort(403);
        }
        
        // Load relationships
        $member->load(['churchGroups', 'recordedBy', 'transfers.fromChurch', 'transfers.toChurch']);
        
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
             $query = Member::with('church')
                           ->where('church_id', $user->church_id)
                           ->where('recorded_by', $user->id);
        } else {
            abort(403);
        }
        
        $members = $query->latest()->get();
        $filename = "members_export_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($members) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($file, ['Member ID', 'Name', 'Sex', 'DOB', 'Age', 'Marital Status', 'Church', 'Chapel', 'Group', 'Education', 'Baptism', 'Disability', 'Parent Names']);

            foreach ($members as $member) {
                fputcsv($file, [
                    $member->member_id ?? '',
                    $member->name,
                    $member->sex,
                    $member->dob ? $member->dob->format('Y-m-d') : '',
                    $member->age,
                    $member->marital_status,
                    $member->church->name,
                    $member->chapel,
                    $member->church_group,
                    $member->education_level,
                    $member->baptism_status,
                    $member->disability,
                    $member->parent_names,
                ]);
            }
            fclose($file);
        }, $filename);
    }

    public function exportPdf()
    {
        $user = auth()->user();
        
        // Scope Logic (Same as Index)
        if ($user->can('view all members')) {
             $query = Member::with('church');
        } elseif ($user->can('view assigned members')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query = Member::with('church')->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own members') && $user->church_id) {
             $query = Member::with('church')
                           ->where('church_id', $user->church_id)
                           ->where('recorded_by', $user->id);
        } else {
            abort(403);
        }
        
        $members = $query->latest()->get();
        
        // Calculate stats
        $stats = [
            'total' => $members->count(),
            'male' => $members->where('sex', 'Male')->count(),
            'female' => $members->where('sex', 'Female')->count(),
            'baptized' => $members->where('baptism_status', 'Baptized')->count(),
        ];
        
        $pdf = Pdf::loadView('exports.members-pdf', [
            'members' => $members,
            'stats' => $stats,
            'title' => 'Member Registry Export',
            'subtitle' => 'Total: ' . number_format($stats['total']) . ' members'
        ])->setPaper('a4', 'landscape');
        
        return $pdf->download('members_export_' . date('Y-m-d_H-i') . '.pdf');
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
            'chapel' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'dob' => 'nullable|date',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'parental_status' => 'nullable|in:Orphan,Living with both parents,Living with one parent,Under guardian/Caregiver,Not Applicable',
            'parent_names' => 'nullable|string|max:255',
            'baptism_status' => 'required|in:Baptized,Confirmed,None',
            'church_group' => 'nullable|string',
            'education_level' => 'nullable|string',
            'disability' => 'nullable|string|max:255',
            'extra_attributes' => 'nullable|array',
            'church_groups' => 'nullable|array',
            'church_groups.*' => 'exists:church_groups,id',
        ]);

        $churchGroupIds = $validated['church_groups'] ?? [];
        unset($validated['church_groups']);

        // Set recorded_by to current user
        $validated['recorded_by'] = auth()->id();

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
        
        // Check scope for edit
        $user = auth()->user();
        if ($user->can('view all members')) {
            // Admin can edit all
        } elseif ($user->can('view assigned members')) {
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            if (!in_array($member->church_id, $assignedChurchIds)) {
                abort(403);
            }
        } elseif ($user->can('edit members') && $user->church_id) {
            if ($member->church_id != $user->church_id || $member->recorded_by != $user->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $churches = Church::all();
        $churchGroups = \App\Models\ChurchGroup::orderBy('name')->get();
        $member->load(['churchGroups', 'recordedBy']);
        
        return view('members.edit', compact('member', 'churches', 'churchGroups'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorize('edit members');

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'chapel' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'dob' => 'nullable|date',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'parental_status' => 'nullable|in:Orphan,Living with both parents,Living with one parent,Under guardian/Caregiver,Not Applicable',
            'parent_names' => 'nullable|string|max:255',
            'baptism_status' => 'required|in:Baptized,Confirmed,None',
            'church_group' => 'nullable|string',
            'education_level' => 'nullable|string',
            'disability' => 'nullable|string|max:255',
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

        // Don't allow changing recorded_by
        unset($validated['recorded_by']);

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
