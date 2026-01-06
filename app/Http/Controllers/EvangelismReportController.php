<?php

namespace App\Http\Controllers;

use App\Models\EvangelismReport;
use App\Models\Church;
use Illuminate\Http\Request;

class EvangelismReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = EvangelismReport::with(['church', 'submitter']);
        
        // Permission-based filtering
        if ($user->can('view all evangelism')) {
             // See all
        } elseif ($user->can('view assigned evangelism') && $user->hasRole('archid')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own evangelism') && $user->church_id) {
             $query->where('church_id', $user->church_id);
        } else {
             $query->where('id', 0);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', now()->year);
        }
        
        $reports = $query->latest('report_date')->paginate(20);
        $churches = $this->getChurchesForUser($user);
        
        // Calculate totals - create a fresh query for aggregation
        $totalsQuery = EvangelismReport::query();
        
        // Apply same filters as main query
        if ($user->can('view all evangelism')) {
             // See all
        } elseif ($user->can('view assigned evangelism') && $user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $totalsQuery->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own evangelism') && $user->church_id) {
            $totalsQuery->where('church_id', $user->church_id);
        } else {
            $totalsQuery->where('id', 0);
        }
        
        if ($request->filled('year')) {
            $totalsQuery->where('year', $request->year);
        } else {
            $totalsQuery->where('year', now()->year);
        }
        
        $totals = $totalsQuery->selectRaw('
            SUM(converts) as total_converts,
            SUM(baptized) as total_baptized,
            SUM(confirmed) as total_confirmed,
            SUM(new_members) as total_new_members
        ')->first();
        
        return view('evangelism-reports.index', compact('reports', 'churches', 'totals'));
    }

    public function create()
    {
        $this->authorize('submit evangelism reports');
        $churches = $this->getChurchesForUser(auth()->user());
        return view('evangelism-reports.create', compact('churches'));
    }

    public function store(Request $request)
    {
        $this->authorize('submit evangelism reports');
        
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'report_date' => 'required|date',
            'bible_study_count' => 'required|integer|min:0',
            'mentorship_count' => 'required|integer|min:0',
            'leadership_count' => 'required|integer|min:0',
            'converts' => 'required|integer|min:0',
            'baptized' => 'required|integer|min:0',
            'confirmed' => 'required|integer|min:0',
            'new_members' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        EvangelismReport::create([
            ...$validated,
            'submitted_by' => auth()->id(),
        ]);

        return redirect()->route('evangelism-reports.index')
            ->with('success', 'Evangelism report submitted successfully!');
    }

    public function show(EvangelismReport $evangelismReport)
    {
        $evangelismReport->load(['church', 'submitter']);
        return view('evangelism-reports.show', compact('evangelismReport'));
    }

    public function edit(EvangelismReport $evangelismReport)
    {
        $churches = $this->getChurchesForUser(auth()->user());
        return view('evangelism-reports.edit', compact('evangelismReport', 'churches'));
    }

    public function update(Request $request, EvangelismReport $evangelismReport)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'report_date' => 'required|date',
            'bible_study_count' => 'required|integer|min:0',
            'mentorship_count' => 'required|integer|min:0',
            'leadership_count' => 'required|integer|min:0',
            'converts' => 'required|integer|min:0',
            'baptized' => 'required|integer|min:0',
            'confirmed' => 'required|integer|min:0',
            'new_members' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $evangelismReport->update($validated);

        return redirect()->route('evangelism-reports.index')
            ->with('success', 'Evangelism report updated successfully!');
    }

    public function destroy(EvangelismReport $evangelismReport)
    {
        $evangelismReport->delete();

        return redirect()->route('evangelism-reports.index')
            ->with('success', 'Evangelism report deleted successfully!');
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
