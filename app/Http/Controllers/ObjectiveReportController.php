<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\ObjectiveReport;
use Illuminate\Http\Request;

class ObjectiveReportController extends Controller
{
    public function create(Objective $objective)
    {
        // Pastors should be able to create reports for objectives assigned to their church
        $this->authorize('submit objective reports');
        
        // Basic check if the objective belongs to their church
        if (auth()->user()->church_id !== $objective->church_id && !auth()->user()->can('view all objectives')) {
            abort(403, 'This objective belongs to another church.');
        }

        return view('objectives.report', compact('objective'));
    }

    public function store(Request $request, Objective $objective)
    {
        $this->authorize('submit objective reports');
        
        if (auth()->user()->church_id !== $objective->church_id && !auth()->user()->can('view all objectives')) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'report_date' => 'required|date',
            'activities_description' => 'required|string',
            'results_outcome' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'location' => 'nullable|string',
            'budget_spent' => 'nullable|numeric|min:0',
            'responsible_person' => 'nullable|string',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:30720', // Optional 30MB max
        ]);

        $report = ObjectiveReport::create([
            'objective_id' => $objective->id,
            'user_id' => auth()->id(),
            'report_date' => $validated['report_date'],
            'activities_description' => $validated['activities_description'],
            'results_outcome' => $validated['results_outcome'],
            'quantity' => $validated['quantity'],
            'location' => $validated['location'],
            'budget_spent' => $validated['budget_spent'] ?? 0,
            'responsible_person' => $validated['responsible_person'],
            'status' => 'submitted',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('report-documents', 'public');
                $extension = strtolower($document->getClientOriginalExtension());
                $type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';
                
                \App\Models\ObjectiveReportDocument::create([
                    'objective_report_id' => $report->id,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $type,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('objectives.show', $objective)
            ->with('success', 'Report submitted successfully.');
    }
}
