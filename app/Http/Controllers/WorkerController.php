<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Church;
use App\Models\Department;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Worker::with(['church', 'department', 'activeContract']);
        
        // Role-based filtering
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $workers = $query->latest()->paginate(20);
        
        $churches = $this->getChurchesForUser($user);
        $departments = $this->getDepartmentsForUser($user);
        
        // Stats
        $stats = [
            'total' => Worker::active()->count(),
            'retiring_soon' => Worker::active()->whereNotNull('birth_date')
                ->get()->filter(fn($w) => $w->years_to_retirement !== null && $w->years_to_retirement <= 2)->count(),
        ];
        
        return view('workers.index', compact('workers', 'churches', 'departments', 'stats'));
    }

    public function create()
    {
        $churches = $this->getChurchesForUser(auth()->user());
        $departments = $this->getDepartmentsForUser(auth()->user());
        
        return view('workers.create', compact('churches', 'departments'));
    }

    public function store(Request $request)
    {
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
        $worker->load(['church', 'department', 'contracts', 'retirementPlan']);
        return view('workers.show', compact('worker'));
    }

    public function edit(Worker $worker)
    {
        $churches = $this->getChurchesForUser(auth()->user());
        $departments = $this->getDepartmentsForUser(auth()->user());
        
        return view('workers.edit', compact('worker', 'churches', 'departments'));
    }

    public function update(Request $request, Worker $worker)
    {
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
