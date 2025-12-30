<?php

namespace App\Http\Controllers;

use App\Models\Giving;
use App\Models\GivingType;
use App\Models\Church;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GivingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $this->getFilteredQuery($request, $user);
        
        $givings = $query->latest('date')->paginate(20);
        
        // Get filter options
        $givingTypes = GivingType::where('is_active', true)->get();
        $churches = $this->getChurchesForUser($user);
        
        // Calculate totals
        $totalAmount = $query->sum('amount');
        $sentToDiocese = $query->where('sent_to_diocese', true)->sum('diocese_amount');
        
        return view('givings.index', compact('givings', 'givingTypes', 'churches', 'totalAmount', 'sentToDiocese'));
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = $this->getFilteredQuery($request, $user);
        $givings = $query->latest('date')->get();

        $filename = "givings_export_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($givings) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, ['Date', 'Church', 'Type', 'Sub-Type', 'Amount (RWF)', 'Sent to Diocese', 'Diocese Amount', 'Entered By']);

            foreach ($givings as $giving) {
                fputcsv($file, [
                    $giving->date->format('Y-m-d'),
                    $giving->church->name,
                    $giving->givingType->name,
                    $giving->givingSubType ? $giving->givingSubType->name : '-',
                    $giving->amount,
                    $giving->sent_to_diocese ? 'Yes' : 'No',
                    $giving->diocese_amount ?? 0,
                    $giving->enteredBy ? $giving->enteredBy->name : 'N/A'
                ]);
            }

            fclose($file);
        }, $filename);
    }

    private function getFilteredQuery(Request $request, $user)
    {
        // Build query based on role
        $query = Giving::with(['church', 'givingType', 'givingSubType', 'enteredBy']);
        
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        // Boss sees all
        
        // Apply filters
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        
        if ($request->filled('giving_type_id')) {
            $query->where('giving_type_id', $request->giving_type_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if (!$request->filled('start_date') && !$request->filled('end_date') && $request->filled('year')) {
             $query->where('year', $request->year);
        } elseif (!$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('month')) {
             $query->where('year', now()->year);
        }

        return $query;
    }

    public function create()
    {
        $user = auth()->user();
        $givingTypes = GivingType::where('is_active', true)->with('subTypes')->get();
        $churches = $this->getChurchesForUser($user);
        
        return view('givings.create', compact('givingTypes', 'churches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'giving_type_id' => 'required|exists:giving_types,id',
            'giving_sub_type_id' => 'nullable|exists:giving_sub_types,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Giving::create([
            ...$validated,
            'entered_by' => auth()->id(),
        ]);

        return redirect()->route('givings.index')
            ->with('success', 'Giving recorded successfully!');
    }

    public function edit(Giving $giving)
    {
        $givingTypes = GivingType::where('is_active', true)->with('subTypes')->get();
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('givings.edit', compact('giving', 'givingTypes', 'churches'));
    }

    public function update(Request $request, Giving $giving)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'giving_type_id' => 'required|exists:giving_types,id',
            'giving_sub_type_id' => 'nullable|exists:giving_sub_types,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $giving->update($validated);

        return redirect()->route('givings.index')
            ->with('success', 'Giving updated successfully!');
    }

    public function destroy(Giving $giving)
    {
        $giving->delete();

        return redirect()->route('givings.index')
            ->with('success', 'Giving deleted successfully!');
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
}
