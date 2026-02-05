<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\Member;
use App\Models\MemberTransfer;
use App\Models\User;
use App\Notifications\MemberTransferCreated;
use App\Notifications\MemberTransferApproved;
use App\Notifications\MemberTransferRejected;
use Illuminate\Http\Request;

class MemberTransferController extends Controller
{
    /**
     * Display a listing of transfers.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $outgoingQuery = MemberTransfer::with(['member', 'fromChurch', 'toChurch', 'initiatedBy', 'approvedBy']);
        $incomingQuery = MemberTransfer::with(['member', 'fromChurch', 'toChurch', 'initiatedBy', 'approvedBy']);
        
        if ($user->can('view all members')) {
            // Admin can see all transfers, optionally filter by church
            if ($request->filled('church_id')) {
                $outgoingQuery->where('from_church_id', $request->church_id);
                $incomingQuery->where('to_church_id', $request->church_id);
            }
        } elseif ($user->can('view assigned members')) {
            // Archid sees transfers for their assigned churches
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            $outgoingQuery->whereIn('from_church_id', $assignedChurchIds);
            $incomingQuery->whereIn('to_church_id', $assignedChurchIds);
        } elseif ($user->church_id) {
            // Pastor sees their church transfers
            $outgoingQuery->where('from_church_id', $user->church_id);
            $incomingQuery->where('to_church_id', $user->church_id);
        } else {
            abort(403);
        }

        // Filter by status
        if ($request->filled('status')) {
            $outgoingQuery->where('status', $request->status);
            $incomingQuery->where('status', $request->status);
        }

        $outgoingTransfers = $outgoingQuery->latest()->paginate(10, ['*'], 'outgoing_page');
        $incomingTransfers = $incomingQuery->latest()->paginate(10, ['*'], 'incoming_page');

        // Get churches for filter dropdown
        $churches = collect();
        if ($user->can('view all churches')) {
            $churches = Church::orderBy('name')->get();
        } elseif ($user->can('view assigned churches')) {
            $churches = Church::where('archid_id', $user->id)->orderBy('name')->get();
        }

        return view('member-transfers.index', compact('outgoingTransfers', 'incomingTransfers', 'churches'));
    }

    /**
     * Show the form for creating a new transfer.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Get members from user's church that they can transfer
        if ($user->can('view all members')) {
            $members = Member::with('church')->active()->orderBy('name')->get();
        } elseif ($user->can('view assigned members')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $members = Member::with('church')->whereIn('church_id', $churchIds)->active()->orderBy('name')->get();
        } elseif ($user->church_id) {
            $members = Member::with('church')
                           ->where('church_id', $user->church_id)
                           ->where('recorded_by', $user->id)
                           ->active()
                           ->orderBy('name')
                           ->get();
        } else {
            $members = collect();
        }

        // Get all churches as potential destinations (except source church)
        $churches = Church::orderBy('name')->get();

        // Pre-select member if passed in query string
        $selectedMemberId = $request->query('member_id');

        return view('member-transfers.create', compact('members', 'churches', 'selectedMemberId'));
    }

    /**
     * Store a newly created transfer.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'to_church_id' => 'required|exists:churches,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        // Check permission to transfer this member
        if (!$this->canTransferMember($user, $member)) {
            abort(403, 'You do not have permission to transfer this member.');
        }

        // Can't transfer to the same church
        if ($member->church_id == $validated['to_church_id']) {
            return back()->withErrors(['to_church_id' => 'Cannot transfer member to the same parish.'])->withInput();
        }

        $transfer = MemberTransfer::create([
            'member_id' => $member->id,
            'from_church_id' => $member->church_id,
            'to_church_id' => $validated['to_church_id'],
            'transfer_date' => $validated['transfer_date'],
            'reason' => $validated['reason'],
            'initiated_by' => $user->id,
            'status' => 'pending',
        ]);

        // Notify destination parish users
        $this->notifyDestinationParish($transfer);

        return redirect()->route('member-transfers.index')
                        ->with('success', 'Member transfer request submitted successfully.');
    }

    /**
     * Display a single transfer.
     */
    public function show(MemberTransfer $memberTransfer)
    {
        $user = auth()->user();

        if (!$this->canAccessTransfer($user, $memberTransfer)) {
            abort(403);
        }

        $memberTransfer->load(['member.church', 'fromChurch', 'toChurch', 'initiatedBy', 'approvedBy']);

        return view('member-transfers.show', compact('memberTransfer'));
    }

    /**
     * Approve a pending transfer.
     */
    public function approve(Request $request, MemberTransfer $memberTransfer)
    {
        $user = auth()->user();

        // Only destination parish users can approve
        if (!$this->canApproveTransfer($user, $memberTransfer)) {
            abort(403, 'You do not have permission to approve this transfer.');
        }

        if ($memberTransfer->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending transfers can be approved.']);
        }

        // Update transfer status
        $memberTransfer->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        // Update member's church
        $memberTransfer->member->update([
            'church_id' => $memberTransfer->to_church_id,
        ]);

        // Notify initiator
        if ($memberTransfer->initiatedBy) {
            $memberTransfer->initiatedBy->notify(new MemberTransferApproved($memberTransfer));
        }

        return redirect()->route('member-transfers.show', $memberTransfer)
                        ->with('success', 'Member transfer approved successfully. The member is now part of your parish.');
    }

    /**
     * Reject a pending transfer.
     */
    public function reject(Request $request, MemberTransfer $memberTransfer)
    {
        $user = auth()->user();

        // Only destination parish users can reject
        if (!$this->canApproveTransfer($user, $memberTransfer)) {
            abort(403, 'You do not have permission to reject this transfer.');
        }

        if ($memberTransfer->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending transfers can be rejected.']);
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $memberTransfer->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        // Notify initiator
        if ($memberTransfer->initiatedBy) {
            $memberTransfer->initiatedBy->notify(new MemberTransferRejected($memberTransfer));
        }

        return redirect()->route('member-transfers.show', $memberTransfer)
                        ->with('success', 'Member transfer rejected.');
    }

    /**
     * Delete a transfer (only pending transfers and by creator or admin).
     */
    public function destroy(MemberTransfer $memberTransfer)
    {
        $user = auth()->user();

        // Only creator or admin can delete, and only pending transfers
        if ($memberTransfer->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending transfers can be deleted.']);
        }

        if ($memberTransfer->initiated_by !== $user->id && !$user->can('view all members')) {
            abort(403);
        }

        $memberTransfer->delete();

        return redirect()->route('member-transfers.index')
                        ->with('success', 'Transfer request deleted.');
    }

    /**
     * Check if user can transfer a specific member.
     */
    private function canTransferMember($user, $member)
    {
        if ($user->can('view all members')) {
            return true;
        }

        if ($user->can('view assigned members')) {
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            return in_array($member->church_id, $assignedChurchIds);
        }

        if ($user->church_id && $member->church_id == $user->church_id) {
            // Pastor can only transfer members they recorded
            return $member->recorded_by == $user->id;
        }

        return false;
    }

    /**
     * Check if user can view a specific transfer.
     */
    private function canAccessTransfer($user, $transfer)
    {
        if ($user->can('view all members')) {
            return true;
        }

        if ($user->can('view assigned members')) {
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            return in_array($transfer->from_church_id, $assignedChurchIds) || 
                   in_array($transfer->to_church_id, $assignedChurchIds);
        }

        if ($user->church_id) {
            return $transfer->from_church_id == $user->church_id || 
                   $transfer->to_church_id == $user->church_id;
        }

        return false;
    }

    /**
     * Check if user can approve/reject a transfer (destination parish).
     */
    private function canApproveTransfer($user, $transfer)
    {
        if ($user->can('view all members')) {
            return true;
        }

        if ($user->can('view assigned members')) {
            $assignedChurchIds = Church::where('archid_id', $user->id)->pluck('id')->toArray();
            return in_array($transfer->to_church_id, $assignedChurchIds);
        }

        if ($user->church_id) {
            return $transfer->to_church_id == $user->church_id;
        }

        return false;
    }

    /**
     * Notify users at the destination parish about the transfer request.
     */
    private function notifyDestinationParish(MemberTransfer $transfer)
    {
        // Find users assigned to destination church
        $destinationUsers = User::where('church_id', $transfer->to_church_id)->get();
        
        // Also notify archdeacons assigned to that church
        $archdeaconIds = Church::where('id', $transfer->to_church_id)->pluck('archid_id')->filter();
        $archdeacons = User::whereIn('id', $archdeaconIds)->get();
        
        $usersToNotify = $destinationUsers->merge($archdeacons)->unique('id');

        foreach ($usersToNotify as $user) {
            $user->notify(new MemberTransferCreated($transfer));
        }
    }
}
