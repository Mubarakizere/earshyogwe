<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Giving;
use Illuminate\Http\Request;

class DioceseTransferController extends Controller
{
    public function index()
    {
        $this->authorize('verify diocese receipt');
        
        $query = Giving::pendingVerification();
        
        $totalAmount = (clone $query)->sum('amount');
        $pendingCount = (clone $query)->count();
        
        $pendingTransfers = $query->with(['church', 'givingType', 'givingSubType', 'enteredBy'])
            ->latest()
            ->paginate(20);
            
        return view('diocese.transfers.index', compact('pendingTransfers', 'totalAmount', 'pendingCount'));
    }

    public function verify(Request $request, Giving $giving)
    {
        $this->authorize('verify diocese receipt');
        
        $giving->update([
            'receipt_status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);
        
        // Notify the paster/enterer
        if ($giving->enteredBy) {
            $giving->enteredBy->notify(new \App\Notifications\DioceseReceiptVerified($giving));
        }
        
        return back()->with('success', 'Transfer verified successfully.');
    }

    public function reject(Request $request, Giving $giving)
    {
        $this->authorize('verify diocese receipt');
        
        $giving->update([
            'receipt_status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Notify
        if ($giving->enteredBy) {
             // You might need a specific notification for rejection, using same for now or generic message
             // For now assuming existing notification can handle status or generic
        }

        return back()->with('success', 'Transfer rejected.');
    }
}
