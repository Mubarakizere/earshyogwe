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
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('boss')) {
            $churches = Church::with(['pastor', 'archid'])->get();
        } elseif ($user->hasRole('archid')) {
            $churches = Church::where('archid_id', $user->id)->with('pastor')->get();
        } else {
            abort(403);
        }

        return view('churches.index', compact('churches'));
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
