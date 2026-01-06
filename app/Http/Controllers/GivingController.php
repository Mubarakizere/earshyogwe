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
        // Build query based on permissions
        $query = Giving::with(['church', 'givingType', 'givingSubType', 'enteredBy']);
        
        if ($user->can('view all givings')) {
             // See all
        } elseif ($user->can('view assigned givings') && $user->hasRole('archid')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own givings') && $user->church_id) {
             $query->where('church_id', $user->church_id);
        } else {
             $query->where('id', 0); // No view access
        }
        
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
        $this->authorize('enter givings');

        $user = auth()->user();
        $givingTypes = GivingType::where('is_active', true)->with('subTypes')->get();
        $churches = $this->getChurchesForUser($user);
        
        return view('givings.create', compact('givingTypes', 'churches'));
    }

    // ... store ...

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
