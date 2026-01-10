<?php

namespace App\Http\Controllers;

use App\Models\PopulationCensus;
use App\Models\Church;
use Illuminate\Http\Request;

class PopulationCensusController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = PopulationCensus::with('church');
        
        // Role-based filtering
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        $censuses = $query->latest('year')->paginate(20);
        $churches = $this->getChurchesForUser($user);
        
        return view('population-censuses.index', compact('censuses', 'churches'));
    }

    public function create()
    {
        $this->authorize('create census');
        
        $churches = $this->getChurchesForUser(auth()->user());
        return view('population-censuses.create', compact('churches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create census');
        
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'period' => 'required|string',
            'men_count' => 'required|integer|min:0',
            'women_count' => 'required|integer|min:0',
            'youth_count' => 'required|integer|min:0',
            'children_count' => 'required|integer|min:0',
            'infants_count' => 'required|integer|min:0',
        ]);

        // Check for duplicate
        $exists = PopulationCensus::where('church_id', $validated['church_id'])
            ->where('year', $validated['year'])
            ->where('period', $validated['period'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['year' => 'A census for this period already exists.']);
        }

        PopulationCensus::create($validated);

        return redirect()->route('population-censuses.index')
            ->with('success', 'Census data recorded successfully!');
    }

    public function show(PopulationCensus $populationCensus)
    {
        return view('population-censuses.show', compact('populationCensus'));
    }

    public function edit(PopulationCensus $populationCensus)
    {
        $this->authorize('edit census');
        
        // Pastors can only edit if pending
        if (auth()->user()->hasRole('pastor') && $populationCensus->status === 'approved') {
             return redirect()->route('population-censuses.index')
                ->with('error', 'Cannot edit statistics after they have been approved.');
        }

        $churches = $this->getChurchesForUser(auth()->user());
        return view('population-censuses.edit', compact('populationCensus', 'churches'));
    }

    public function update(Request $request, PopulationCensus $populationCensus)
    {
        $this->authorize('edit census');
        
        $validated = $request->validate([
            'men_count' => 'required|integer|min:0',
            'women_count' => 'required|integer|min:0',
            'youth_count' => 'required|integer|min:0',
            'children_count' => 'required|integer|min:0',
            'infants_count' => 'required|integer|min:0',
        ]);

        $populationCensus->update($validated);

        return redirect()->route('population-censuses.index')
            ->with('success', 'Census data updated successfully!');
    }

    public function destroy(PopulationCensus $populationCensus)
    {
        $this->authorize('delete census');
        
        if (auth()->user()->hasRole('pastor') && $populationCensus->status === 'approved') {
             return redirect()->route('population-censuses.index')
                ->with('error', 'Cannot delete statistics after they have been approved.');
        }
        
        $populationCensus->delete();

        return redirect()->route('population-censuses.index')
            ->with('success', 'Census data deleted successfully!');
    }
    
    // Helper
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
}
