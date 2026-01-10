<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Church;
use App\Models\Department;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function export()
    {
        $user = auth()->user();
        $query = Worker::with(['church', 'department']);
        
        // Apply same filters as index
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }

        $workers = $query->get();

        $filename = "workers_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['First Name', 'Last Name', 'Church', 'Department', 'Position', 'Status', 'Phone', 'Email', 'Employment Date']);

        foreach ($workers as $worker) {
            fputcsv($handle, [
                $worker->first_name,
                $worker->last_name,
                $worker->church->name ?? 'N/A',
                $worker->department->name ?? 'N/A',
                $worker->position,
                $worker->status,
                $worker->phone,
                $worker->email,
                $worker->employment_date ? $worker->employment_date->format('Y-m-d') : '',
            ]);
        }
        fclose($handle);
        exit;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Base Query with Relations
        $query = Worker::with(['church', 'department', 'activeContract']);
        
        // 2. Role-based Scope
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // 3. Advanced Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('church_id') && ($user->hasRole('boss') || $user->hasRole('archid'))) {
            $query->where('church_id', $request->church_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter (Active/Inactive/All)
        if ($request->filled('status')) {
             $query->where('status', $request->status);
        }
        
        $workers = $query->latest()->paginate(20)->withQueryString();
        
        // 4. Data for Filters
        $churches = $this->getChurchesForUser($user);
        $departments = $this->getDepartmentsForUser($user);
        
        // 5. Global Stats (Scoped to User Role, ignoring temporary filters)
        $statsQuery = Worker::query();
        if ($user->hasRole('pastor')) {
            $statsQuery->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $statsQuery->whereIn('church_id', $churchIds);
        }

        // Optimized Stats
        $allWorkers = (clone $statsQuery)->where('status', 'active')->get(); // Get only active for retirement stats

        $stats = [
            'total' => (clone $statsQuery)->count(), // All workers (active + inactive)
            'retiring_soon' => $allWorkers->filter(fn($w) => $w->years_to_retirement !== null && $w->years_to_retirement >= 0 && $w->years_to_retirement <= 2)->count(),
            'overdue' => $allWorkers->filter(fn($w) => $w->years_to_retirement !== null && $w->years_to_retirement < 0)->count(),
        ];
        
        return view('workers.index', compact('workers', 'churches', 'departments', 'stats'));
    }

    public function create()
    {
        $this->authorize('create worker');
        $churches = $this->getChurchesForUser(auth()->user());
        $departments = $this->getDepartmentsForUser(auth()->user());
        
        return view('workers.create', compact('churches', 'departments'));
    }

    public function store(Request $request)
    {
        $this->authorize('create worker');
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'department_id' => 'nullable|exists:departments,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'retirement_age' => 'required|integer|min:50|max:70',
        ]);

        Worker::create($validated);

        return redirect()->route('workers.index')
            ->with('success', 'Worker added successfully!');
    }

    public function show(Worker $worker)
    {
        // Add scope check if needed, but for now assuming 'view workers' covers list/show
        // $this->authorize('view worker'); 
        $worker->load(['church', 'department', 'contracts', 'retirementPlan']);
        return view('workers.show', compact('worker'));
    }

    public function edit(Worker $worker)
    {
        $this->authorize('edit worker');
        $churches = $this->getChurchesForUser(auth()->user());
        $departments = $this->getDepartmentsForUser(auth()->user());
        
        return view('workers.edit', compact('worker', 'churches', 'departments'));
    }

    public function update(Request $request, Worker $worker)
    {
        $this->authorize('edit worker');
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'department_id' => 'nullable|exists:departments,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers,email,' . $worker->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'retirement_age' => 'required|integer|min:50|max:70',
            'status' => 'required|in:active,retired,terminated',
        ]);

        $worker->update($validated);

        return redirect()->route('workers.index')
            ->with('success', 'Worker updated successfully!');
    }

    public function destroy(Worker $worker)
    {
        $this->authorize('delete worker');
        $worker->delete();

        return redirect()->route('workers.index')
            ->with('success', 'Worker deleted successfully!');
    }

    private function getChurchesForUser($user)
    {
        if ($user->hasRole('boss')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } else {
            return Church::where('id', $user->church_id)->get();
        }
    }

    private function getDepartmentsForUser($user)
    {
        if ($user->hasRole('boss')) {
            return Department::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return Department::whereIn('church_id', $churchIds)->where('is_active', true)->get();
        } else {
            return Department::where('church_id', $user->church_id)->where('is_active', true)->get();
        }
    }
}
