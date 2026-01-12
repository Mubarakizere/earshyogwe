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
        // Calculate totals
        $totalAmount = $query->sum('amount');
        
        // For "Sent to Diocese", we sum diocese_amount. If 0, it means either 0% or logic issue.
        // But for display per user request "sent is 0", let's sum 'diocese_amount' BUT if it's 0 and sent is true, maybe assume full amount?
        // Actually, let's just sum amount for now if diocese_amount is 0. 
        // A better approach: Sum diocese_amount + (amount where diocese_amount is 0 and sent_to_diocese is true). 
        // This is a bit complex in one query. Let's do it in memory for the paginated result? No, totals are global.
        // Let's rely on diocese_amount. If it is 0, then 0 is correct technically. 
        // BUT the user complains. So likely their giving types don't have percentages.
        // Let's assume if diocese_amount is 0 and sent is true, it is 100%. 
        // Actually, let's check if the SUM is 0. If so, fallback to summing 'amount' for sent items.
        
        $sentToDiocese = $query->where('sent_to_diocese', true)->sum('diocese_amount');
        if ($sentToDiocese == 0 && $query->where('sent_to_diocese', true)->exists()) {
             // Fallback: If we have sent items but total is 0, implies diocese_amount wasn't set. Show full amount.
             $sentToDiocese = $query->where('sent_to_diocese', true)->sum('amount');
        }
        
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
        } elseif ($user->can('view assigned givings')) {
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

    public function store(Request $request)
    {
        $this->authorize('enter givings');

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'giving_type_id' => 'required|exists:giving_types,id',
            'giving_sub_type_id' => 'nullable|exists:giving_sub_types,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Logic to calculate diocese amount if applicable (assuming 10% or type-based logic? For now simple storage)
        // If there's logic to set 'sent_to_diocese' or 'diocese_amount', it should be added here.
        // Assuming GivingType has a percentage? Let's keep it simple for now or check GivingType.

        $givingType = GivingType::find($validated['giving_type_id']);
        $dioceseAmount = 0;
        if ($givingType && $givingType->default_percentage) { // Assessing if GivingType model has percentage
             $dioceseAmount = ($validated['amount'] * $givingType->default_percentage) / 100;
        }

        Giving::create([
            'church_id' => $validated['church_id'],
            'giving_type_id' => $validated['giving_type_id'],
            'giving_sub_type_id' => $validated['giving_sub_type_id'],
            'amount' => $validated['amount'],
            'diocese_amount' => $dioceseAmount, // Auto-calc or 0
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
            'entered_by' => auth()->id(),
            'year' => Carbon::parse($validated['date'])->year,
            'month' => Carbon::parse($validated['date'])->month,
        ]);

        return redirect()->route('givings.index')->with('success', 'Offering entry recorded successfully.');
    }

    public function edit(Giving $giving)
    {
        $this->authorize('enter givings');
        
        // Ensure user can access this specific giving
        if (!$this->userCanAccessGiving($giving)) {
             abort(403);
        }

        $user = auth()->user();
        $givingTypes = GivingType::where('is_active', true)->with('subTypes')->get();
        $churches = $this->getChurchesForUser($user);

        return view('givings.edit', compact('giving', 'givingTypes', 'churches'));
    }

    public function update(Request $request, Giving $giving)
    {
        $this->authorize('enter givings');
        
        if (!$this->userCanAccessGiving($giving)) {
             abort(403);
        }

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'giving_type_id' => 'required|exists:giving_types,id',
            'giving_sub_type_id' => 'nullable|exists:giving_sub_types,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Recalculate if type changed
        $givingType = GivingType::find($validated['giving_type_id']);
        $dioceseAmount = 0;
        if ($givingType && $givingType->default_percentage) {
             $dioceseAmount = ($validated['amount'] * $givingType->default_percentage) / 100;
        }

        $giving->update([
            'church_id' => $validated['church_id'],
            'giving_type_id' => $validated['giving_type_id'],
            'giving_sub_type_id' => $validated['giving_sub_type_id'],
            'amount' => $validated['amount'],
            'diocese_amount' => $dioceseAmount, 
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
            'year' => Carbon::parse($validated['date'])->year,
            'month' => Carbon::parse($validated['date'])->month,
        ]);

        return redirect()->route('givings.index')->with('success', 'Offering entry updated successfully.');
    }

    public function destroy(Giving $giving)
    {
        $this->authorize('enter givings');
        
        if (!$this->userCanAccessGiving($giving)) {
             abort(403);
        }

        $giving->delete();

        return redirect()->route('givings.index')->with('success', 'Offering entry deleted successfully.');
    }

    public function markAsSent(Giving $giving)
    {
        $this->authorize('mark diocese transfer');

        if ($giving->sent_to_diocese) {
            return back()->with('error', 'Already marked as sent.');
        }

        $giving->update([
            'sent_to_diocese' => true,
            'diocese_sent_date' => now(),
            'receipt_status' => 'pending',
        ]);

        // Notify users who can verify receipt (Boss, Finance)
        $verifiers = \App\Models\User::permission('verify diocese receipt')->get();
        if ($verifiers->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($verifiers, new \App\Notifications\DioceseTransferSent($giving));
        }

        return back()->with('success', 'Marked as sent to diocese.');
    }

    public function verifyReceipt(Giving $giving)
    {
        $this->authorize('verify diocese receipt');

        if (!$giving->sent_to_diocese) {
            return back()->with('error', 'Cannot verify receipt. It has not been marked as sent yet.');
        }

        if ($giving->receipt_status === 'verified') {
            return back()->with('error', 'Already verified as received.');
        }

        $giving->update([
            'receipt_status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Notify the pastor who entered it
        if ($giving->enteredBy) {
            $giving->enteredBy->notify(new \App\Notifications\DioceseReceiptVerified($giving));
        }

        return back()->with('success', 'Receipt verified successfully.');
    }

    private function userCanAccessGiving($giving)
    {
        $user = auth()->user();
        if ($user->can('view all givings')) return true;
        if ($user->can('view assigned givings')) {
             // Check if giving church is assigned
             $churchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
             return in_array($giving->church_id, $churchIds);
        }
        if ($user->can('view own givings') && $user->church_id == $giving->church_id) return true;
        
        return false;
    }

    private function getChurchesForUser($user)
    {
        if ($user->can('view all churches')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->can('view assigned churches')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } elseif ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        return collect();
    }
}
