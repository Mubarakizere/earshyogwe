<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Scope Check based on permissions
        if ($user->can('view all members')) {
             $query = Member::with('church');
        } elseif ($user->can('view assigned members') && $user->hasRole('archid')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query = Member::with('church')->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own members') && $user->church_id) {
             $query = Member::with('church')->where('church_id', $user->church_id);
        } else {
            abort(403, 'Unauthorized access to members.');
        }

        $members = $query->latest()->paginate(15);
        
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $this->authorize('create members');
        
        $user = auth()->user();
        $churches = collect();

        if ($user->can('view all churches')) {
            $churches = Church::all();
        } elseif ($user->hasRole('archid')) {
            $churches = Church::where('archid_id', $user->id)->get();
        } elseif ($user->church_id) {
             $churches = Church::where('id', $user->church_id)->get();
        }

        return view('members.create', compact('churches'));
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
            'extra_attributes' => 'nullable|array', // JSON field
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    public function edit(Member $member)
    {
        $this->authorize('edit members');
        
        // Check scope for edit (e.g. Pastor can only edit own members)
        $user = auth()->user();
        if ($user->hasRole('pastor') && $user->church_id != $member->church_id) {
             abort(403);
        }

        $churches = Church::all(); // Simplified for edit context, can be restricted if needed
        return view('members.edit', compact('member', 'churches'));
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
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $this->authorize('delete members');
        
        $member->delete();
        return redirect()->back()->with('success', 'Member deleted successfully.');
    }
}
