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
        
        if ($user->can('view all evangelism')) {
             // See all
        } else {
            $query->where(function($q) use ($user) {
                if ($user->can('view assigned evangelism') && $user->hasRole('archid')) {
                    $churchIds = Church::where('archid_id', $user->id)->pluck('id');
                    $q->orWhereIn('church_id', $churchIds);
                }
                if ($user->can('view own evangelism') && $user->church_id) {
                    $q->orWhere('church_id', $user->church_id);
                }
            });

            if (!$user->can('view assigned evangelism') && !$user->can('view own evangelism')) {
                $query->where('id', 0); // Failsafe
            }
        }
        
        // Date Range Filtering
        if ($request->filled('start_date')) {
            $query->whereDate('report_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('report_date', '<=', $request->end_date);
        }
        // Default to current year only if NO date filters provided? 
        // Or just let it show all? Previous behavior was default to current year.
        // Let's default to current year if no specific date range is set, to avoid loading everything.
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
             $query->whereYear('report_date', now()->year);
        }

        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        
        $reports = $query->latest('report_date')->paginate(20);
        $churches = $this->getChurchesForUser($user);
        
        // Calculate totals - create a fresh query for aggregation
        $totalsQuery = EvangelismReport::query();
        
        // Apply same filters as main query
        if ($user->can('view all evangelism')) {
             // See all
        } else {
            $totalsQuery->where(function($q) use ($user) {
                if ($user->can('view assigned evangelism') && $user->hasRole('archid')) {
                    $churchIds = Church::where('archid_id', $user->id)->pluck('id');
                    $q->orWhereIn('church_id', $churchIds);
                }
                if ($user->can('view own evangelism') && $user->church_id) {
                    $q->orWhere('church_id', $user->church_id);
                }
            });

            if (!$user->can('view assigned evangelism') && !$user->can('view own evangelism')) {
                $totalsQuery->where('id', 0);
            }
        }
        
        // Date Range Filtering for Totals
        if ($request->filled('start_date')) {
            $totalsQuery->whereDate('report_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $totalsQuery->whereDate('report_date', '<=', $request->end_date);
        }
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
             $totalsQuery->whereYear('report_date', now()->year);
        }

        if ($request->filled('church_id')) {
            $totalsQuery->where('church_id', $request->church_id);
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

        // Check for duplicate report (Same Church + Same Month + Same Year)
        $date = \Carbon\Carbon::parse($validated['report_date']);
        $exists = EvangelismReport::where('church_id', $validated['church_id'])
            ->where('month', $date->month)
            ->where('year', $date->year)
            ->exists();

        if ($exists) {
            return back()->withErrors(['report_date' => 'A report for this church and month already exists. Please edit the existing report instead.'])->withInput();
        }

        $report = EvangelismReport::create([
            ...$validated,
            'submitted_by' => auth()->id(),
        ]);

        // Notify Admins/Archids (anyone who can view ALL reports or ASSIGNED reports?)
        // Let's notify anyone with 'view all evangelism' (Boss, Evangelism Head)
        $recipients = \App\Models\User::permission('view all evangelism')->get();
        
        // Also notify Archid assigned to this church?
        if ($report->church && $report->church->archid_id) {
             $archid = \App\Models\User::find($report->church->archid_id);
             if ($archid && !$recipients->contains($archid)) {
                 $recipients->push($archid);
             }
        }
        
        // Filter out self so you don't notify yourself
        $recipients = $recipients->reject(function ($user) {
            return $user->id === auth()->id();
        });

        if ($recipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\EvangelismReportSubmitted($report));
        }

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
        $this->authorize('submit evangelism reports');
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
        $this->authorize('submit evangelism reports');
        $evangelismReport->delete();

        return redirect()->route('evangelism-reports.index')
            ->with('success', 'Evangelism report deleted successfully!');
    }

    private function getChurchesForUser($user)
    {
        if ($user->can('view all churches') || $user->can('view all evangelism')) {
            return Church::where('is_active', true)->orderBy('name')->get();
        }

        $query = Church::where('is_active', true);
        
        $query->where(function($q) use ($user) {
            if ($user->hasRole('archid') || $user->can('view assigned evangelism')) {
                $q->orWhere('archid_id', $user->id);
            }
            if ($user->church_id) {
                // Return their own church
                $q->orWhere('id', $user->church_id);
            }
        });

        // If the user has neither role/church, return empty collection to avoid returning all churches
        if (!$user->hasRole('archid') && !$user->can('view assigned evangelism') && !$user->church_id) {
            return collect();
        }

        return $query->orderBy('name')->get();
    }
}
