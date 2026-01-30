<?php

namespace App\Http\Controllers;

use App\Models\ParishTransfer;
use App\Models\Church;
use Illuminate\Http\Request;

class ParishTransferController extends Controller
{
    /**
     * Display a listing of transfers.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query for permissions
        $query = ParishTransfer::query();

        if ($user->can('view all transfers')) {
            // See all transfers
        } elseif ($user->can('view assigned transfers')) {
            // Archid: see transfers from assigned churches
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own transfers') && $user->church_id) {
            // Pastor: see only own church transfers
            $query->where('church_id', $user->church_id);
        } elseif ($user->can('create parish transfers') && $user->church_id) {
            // Can create but limited view - show own transfers
            $query->where('church_id', $user->church_id);
        } else {
            $query->where('id', 0); // No view access
        }
        
        // Apply filters
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('transfer_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transfer_date', '<=', $request->end_date);
        }

        // Calculate totals
        $totalAmount = (clone $query)->sum('amount');
        $pendingAmount = (clone $query)->pending()->sum('amount');
        $verifiedAmount = (clone $query)->verified()->sum('amount');
        $pendingCount = (clone $query)->pending()->count();

        // Get transfers with relationships
        $transfers = $query->with(['church', 'enteredBy', 'verifiedBy'])
            ->latest('transfer_date')
            ->paginate(20);

        // Get churches for filter dropdown
        $churches = $this->getChurchesForUser($user);
            
        return view('parish-transfers.index', compact(
            'transfers', 
            'churches', 
            'totalAmount', 
            'pendingAmount', 
            'verifiedAmount', 
            'pendingCount'
        ));
    }

    /**
     * Show the form for creating a new transfer.
     */
    public function create()
    {
        $this->authorize('create parish transfers');

        $user = auth()->user();
        $churches = $this->getChurchesForUser($user);
        
        return view('parish-transfers.create', compact('churches'));
    }

    /**
     * Store a newly created transfer.
     */
    public function store(Request $request)
    {
        $this->authorize('create parish transfers');

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'amount' => 'required|numeric|min:1',
            'transfer_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // Verify user has access to this church
        if (!$this->canAccessChurch($user, $validated['church_id'])) {
            abort(403, 'You cannot create transfers for this parish.');
        }

        $transfer = ParishTransfer::create([
            'church_id' => $validated['church_id'],
            'amount' => $validated['amount'],
            'transfer_date' => $validated['transfer_date'],
            'reference' => $validated['reference'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'entered_by' => auth()->id(),
        ]);

        // Notify users who can verify transfers
        $verifiers = \App\Models\User::permission('verify parish transfers')->get();
        if ($verifiers->count() > 0) {
            $transfer->load(['church', 'enteredBy']);
            \Illuminate\Support\Facades\Notification::send($verifiers, new \App\Notifications\ParishTransferCreated($transfer));
        }

        return redirect()->route('parish-transfers.index')
            ->with('success', 'Transfer recorded successfully. Pending verification.');
    }

    /**
     * Verify a transfer.
     */
    public function verify(Request $request, ParishTransfer $transfer)
    {
        $this->authorize('verify parish transfers');
        
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be verified.');
        }

        $transfer->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Notify the person who entered it
        if ($transfer->enteredBy) {
            $transfer->load(['church', 'enteredBy', 'verifiedBy']);
            $transfer->enteredBy->notify(new \App\Notifications\ParishTransferVerified($transfer));
        }

        return back()->with('success', 'Transfer verified successfully.');
    }

    /**
     * Reject a transfer.
     */
    public function reject(Request $request, ParishTransfer $transfer)
    {
        $this->authorize('verify parish transfers');
        
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be rejected.');
        }

        $transfer->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Notify the person who entered it
        if ($transfer->enteredBy) {
            $transfer->load(['church', 'enteredBy', 'verifiedBy']);
            $transfer->enteredBy->notify(new \App\Notifications\ParishTransferRejected($transfer));
        }

        return back()->with('success', 'Transfer rejected.');
    }

    /**
     * Display a single transfer.
     */
    public function show(ParishTransfer $transfer)
    {
        $user = auth()->user();
        
        // Check permission to view this transfer
        if (!$this->canAccessTransfer($user, $transfer)) {
            abort(403);
        }

        $transfer->load(['church', 'enteredBy', 'verifiedBy']);
        
        return view('parish-transfers.show', compact('transfer'));
    }

    /**
     * Delete a transfer (only pending transfers and by creator or admin).
     */
    public function destroy(ParishTransfer $transfer)
    {
        $user = auth()->user();
        
        // Only allow deletion of pending transfers
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be deleted.');
        }
        
        // Allow if user created it, or has admin rights
        $canDelete = ($transfer->entered_by == $user->id) || $user->can('view all transfers');
        
        if (!$canDelete) {
            abort(403, 'You cannot delete this transfer.');
        }

        $transfer->delete();

        return redirect()->route('parish-transfers.index')
            ->with('success', 'Transfer deleted successfully.');
    }

    /**
     * Helper to check if user can view a specific transfer.
     */
    private function canAccessTransfer($user, $transfer)
    {
        if ($user->can('view all transfers')) return true;
        if ($user->can('view assigned transfers')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            return in_array($transfer->church_id, $churchIds);
        }
        if ($user->church_id == $transfer->church_id) return true;
        return false;
    }

    /**
     * Helper to check if user can access a specific church.
     */
    private function canAccessChurch($user, $churchId)
    {
        if ($user->can('view all transfers')) return true;
        if ($user->can('view assigned transfers')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            return in_array($churchId, $churchIds);
        }
        if ($user->church_id == $churchId) return true;
        return false;
    }

    /**
     * Get churches available to user for dropdowns.
     */
    private function getChurchesForUser($user)
    {
        if ($user->can('view all transfers') || $user->can('view all churches')) {
            return Church::where('is_active', true)->orderBy('name')->get();
        } elseif ($user->can('view assigned transfers') || $user->can('view assigned churches')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->orderBy('name')->get();
        } elseif ($user->church_id) {
            return Church::where('id', $user->church_id)->get();
        }
        return collect();
    }
}
