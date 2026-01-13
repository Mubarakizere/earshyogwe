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
        
        // Base query for permissions
        $query = Giving::query();

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
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if (!$request->filled('start_date') && !$request->filled('end_date') && $request->filled('year')) {
             $query->where('year', $request->year);
        }

        // Calculate Global Totals (before grouping)
        $totalAmount = (clone $query)->sum('amount');
        // Sum diocese_amount for sent items
        $sentToDiocese = (clone $query)->where('sent_to_diocese', true)->sum('diocese_amount');

        // Grouping - Get raw grouped data
        $givings = $query->selectRaw('date, church_id, SUM(amount) as total_amount, SUM(diocese_amount) as total_diocese_amount, MIN(sent_to_diocese) as is_sent, MAX(entered_by) as last_entered_by')
            ->groupBy('date', 'church_id')
            ->orderBy('date', 'desc')
            ->paginate(20);

        // Manually load churches and users for the grouped results
        $churchIds = $givings->pluck('church_id')->unique();
        $userIds = $givings->pluck('last_entered_by')->unique();
        
        $churchesMap = Church::whereIn('id', $churchIds)->get()->keyBy('id');
        $usersMap = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        
        // Attach the related models to each item
        foreach ($givings as $giving) {
            $giving->church = $churchesMap->get($giving->church_id);
            $giving->enteredBy = $usersMap->get($giving->last_entered_by);
        }

        // Get filter options
        $churches = $this->getChurchesForUser($user);
        
        return view('givings.index', compact('givings', 'churches', 'totalAmount', 'sentToDiocese'));
    }

    public function details($date, $churchId)
    {
        $user = auth()->user();

        if (!$date || !$churchId) {
            return redirect()->route('givings.index')->with('error', 'Invalid parameters for details view.');
        }

        $church = Church::findOrFail($churchId);

        // Check permission
        if (!$this->canAccessChurch($user, $churchId)) {
            abort(403);
        }

        // Fetch actual records
        $records = Giving::where('date', $date)
            ->where('church_id', $churchId)
            ->with(['givingType', 'givingSubType', 'enteredBy'])
            ->get();
            
        // Fetch all active GivingTypes to build the "Matrix" (show 0s)
        $allTypes = GivingType::where('is_active', true)->orderBy('name')->get();

        // Calculate session totals
        $sessionTotal = $records->sum('amount');
        $sessionDioceseTotal = $records->sum('diocese_amount');
        $isSent = $records->first()->sent_to_diocese ?? false;
        
        // Map records to types for easy display
        // structure: [type_id => ['amount' => X, 'record' => $givingObject]]
        $typeMap = [];
        foreach($records as $record) {
            $typeMap[$record->giving_type_id] = $record;
        }

        return view('givings.details', compact('church', 'date', 'records', 'allTypes', 'typeMap', 'sessionTotal', 'sessionDioceseTotal', 'isSent'));
    }

    // Helper to check access for a specific church
    private function canAccessChurch($user, $churchId) {
        if ($user->can('view all givings')) return true;
        if ($user->can('view assigned givings')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
             return in_array($churchId, $churchIds);
        }
        if ($user->can('view own givings') && $user->church_id == $churchId) return true;
        return false;
    }

    private function getFilteredQuery(Request $request, $user)
    {
        // Deprecated for direct use in index due to grouping requirements, 
        // but kept if needed for export or other simple lists.
        // Re-implementing basic filtering without eager loading for general usage.
        
        $query = Giving::query();
        
        if ($user->can('view all givings')) {
        } elseif ($user->can('view assigned givings')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own givings') && $user->church_id) {
             $query->where('church_id', $user->church_id);
        } else {
             $query->where('id', 0);
        }
        
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
        }

        return $query;
    }

    public function create()
    {
        $this->authorize('enter givings');

        $user = auth()->user();
        $givingTypes = GivingType::where('is_active', true)
            ->with(['subTypes' => function($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        
        $churches = $this->getChurchesForUser($user);
        
        return view('givings.create', compact('givingTypes', 'churches'));
    }

    public function store(Request $request)
{
    $this->authorize('enter givings');

    $validated = $request->validate([
        'church_id' => 'required|exists:churches,id',
        'date' => 'required|date',
        'amounts' => 'array',
        'amounts.*' => 'nullable|numeric|min:0',
        'subtypes' => 'array',
        'subtypes.*' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    $churchId = $validated['church_id'];
    $date = $validated['date'];
    $notes = $validated['notes'] ?? null;
    $enteredBy = auth()->id();
    
    $dateObj = Carbon::parse($date);
    $year = $dateObj->year;
    $month = $dateObj->month;

    $count = 0;

    // Handle regular giving types (without subtypes)
    if (isset($validated['amounts'])) {
        foreach ($validated['amounts'] as $typeId => $amount) {
            if ($amount > 0) {
                $givingType = GivingType::find($typeId);
                if (!$givingType) continue;

                $dioceseAmount = 0;
                if ($givingType->default_percentage) {
                     $dioceseAmount = ($amount * $givingType->default_percentage) / 100;
                }

                Giving::create([
                    'church_id' => $churchId,
                    'giving_type_id' => $typeId,
                    'giving_sub_type_id' => null,
                    'amount' => $amount,
                    'diocese_amount' => $dioceseAmount,
                    'date' => $date,
                    'notes' => $notes,
                    'entered_by' => $enteredBy,
                    'year' => $year,
                    'month' => $month,
                ]);
                $count++;
            }
        }
    }

    // Handle giving subtypes
    if (isset($validated['subtypes'])) {
        foreach ($validated['subtypes'] as $subTypeId => $amount) {
            if ($amount > 0) {
                $subType = \App\Models\GivingSubType::find($subTypeId);
                if (!$subType) continue;

                $givingType = $subType->givingType;
                if (!$givingType) continue;

                $dioceseAmount = 0;
                if ($givingType->default_percentage) {
                     $dioceseAmount = ($amount * $givingType->default_percentage) / 100;
                }

                Giving::create([
                    'church_id' => $churchId,
                    'giving_type_id' => $givingType->id,
                    'giving_sub_type_id' => $subTypeId,
                    'amount' => $amount,
                    'diocese_amount' => $dioceseAmount,
                    'date' => $date,
                    'notes' => $notes,
                    'entered_by' => $enteredBy,
                    'year' => $year,
                    'month' => $month,
                ]);
                $count++;
            }
        }
    }

    if ($count == 0) {
        return back()->with('error', 'No amounts entered. Please enter at least one amount.');
    }

    return redirect()->route('givings.index')->with('success', "Successfully recorded $count offering entries.");
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

            return redirect()->route('givings.details', ['date' => $giving->date, 'church_id' => $giving->church_id])
            ->with('success', 'Offering entry updated successfully.');
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

    public function destroyBulk($date, $churchId)
    {
        $this->authorize('enter givings');
        
        // Verify user has access to this church
        if (!$this->canAccessChurch(auth()->user(), $churchId)) {
             abort(403);
        }

        // Delete all givings for this date and church
        $count = Giving::where('date', $date)
            ->where('church_id', $churchId)
            ->delete();

        return redirect()->route('givings.index')
            ->with('success', "Successfully deleted all $count offering records for this date and church.");
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
