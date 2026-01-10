<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $user = auth()->user();

        if ($user->hasRole('boss')) {
            $churches = Church::with(['pastor', 'archid'])->get();
        } elseif ($user->hasRole('archid')) {
            $churches = Church::where('archid_id', $user->id)->with('pastor')->get();
        } else {
            abort(403);
        }

        $filename = "churches_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['Name', 'Location', 'Pastor', 'Archdeacon', 'Active', 'Created At']);

        foreach ($churches as $church) {
            fputcsv($handle, [
                $church->name,
                $church->location,
                $church->pastor->name ?? 'N/A',
                $church->archid->name ?? 'N/A',
                $church->is_active ? 'Yes' : 'No',
                $church->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        fclose($handle);
        exit;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Base Query
        $query = Church::query()->with(['pastor', 'archid']);

        if ($user->hasRole('boss')) {
            // Can see all
        } elseif ($user->hasRole('archid')) {
            $query->where('archid_id', $user->id);
        } else {
            abort(403, 'Unauthorized access to view churches.');
        }

        // 2. Filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
             if ($request->status === 'active') {
                 $query->where('is_active', true);
             } elseif ($request->status === 'inactive') {
                 $query->where('is_active', false);
             }
        }

        // 3. Stats (Scoped to user permissions)
        $statsQuery = Church::query();
        if ($user->hasRole('boss')) {
            // All
        } elseif ($user->hasRole('archid')) {
            $statsQuery->where('archid_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('is_active', true)->count(),
            'inactive' => (clone $statsQuery)->where('is_active', false)->count(),
        ];

        // 4. Pagination
        $churches = $query->latest()->paginate(10)->withQueryString();

        return view('churches.index', compact('churches', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only Boss or maybe Archid (if permitted) can create churches
        $this->authorize('create church'); 
        
        $archdeacons = \App\Models\User::role('archid')->get();
        $pastors = \App\Models\User::role('pastor')->get();

        return view('churches.create', compact('archdeacons', 'pastors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create church');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'diocese' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'archid_id' => 'nullable|exists:users,id',
            'pastor_id' => 'nullable|exists:users,id',
        ]);

        Church::create($validated);

        return redirect()->route('churches.index')->with('success', 'Church created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function show(Church $church)
    {
        return view('churches.show', compact('church'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function edit(Church $church)
    {
        $this->authorize('edit church'); 
        $archdeacons = \App\Models\User::role('archid')->get();
        $pastors = \App\Models\User::role('pastor')->get();
        return view('churches.edit', compact('church', 'archdeacons', 'pastors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Church $church)
    {
        $this->authorize('edit church');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'archid_id' => 'nullable|exists:users,id',
            'pastor_id' => 'nullable|exists:users,id',
        ]);

        $church->update($validated);

        return redirect()->route('churches.index')->with('success', 'Church updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function destroy(Church $church)
    {
        $this->authorize('delete church');
        $church->delete();

        return redirect()->route('churches.index')->with('success', 'Church deleted successfully.');
    }
}
