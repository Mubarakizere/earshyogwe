<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Church;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class ExpenseController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $this->getFilteredQuery($request, $user);
        
        $expenses = $query->latest('date')->paginate(20);
        
        $categories = ExpenseCategory::where('is_active', true)->get();
        $churches = $this->getChurchesForUser($user);
        
        // Calculate totals based on filtered query for the view
        $totalAmount = $query->sum('amount');
        $pendingCount = Expense::pending()->count(); // This might need to be filtered too if we want "Pending in this filter"? 
        // For now, let's keep pendingCount global as it's an alert, or filter it?
        // Let's filter pending count by the same query constraints EXCEPT status
        $pendingQuery = $this->getFilteredQuery($request, $user);
        $pendingCount = $pendingQuery->where('status', 'pending')->count();
        
        return view('expenses.index', compact('expenses', 'categories', 'churches', 'totalAmount', 'pendingCount'));
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = $this->getFilteredQuery($request, $user);
        $expenses = $query->latest('date')->get();

        $filename = "expenses_export_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, ['Date', 'Church', 'Category', 'Description', 'Amount (RWF)', 'Status', 'Entered By']);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->date->format('Y-m-d'),
                    $expense->church->name,
                    $expense->expenseCategory->name,
                    $expense->description,
                    $expense->amount,
                    ucfirst($expense->status),
                    $expense->enteredBy ? $expense->enteredBy->name : 'N/A'
                ]);
            }

            fclose($file);
        }, $filename);
    }

    private function getFilteredQuery(Request $request, $user)
    {
        $query = Expense::with(['church', 'expenseCategory', 'enteredBy', 'approver']);
        
        // Role-based filtering
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // Apply filters
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->church_id);
        }
        
        if ($request->filled('expense_category_id')) {
            $query->where('expense_category_id', $request->expense_category_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if (!$request->filled('start_date') && !$request->filled('end_date') && $request->filled('year')) {
             $query->where('year', $request->year);
        } elseif (!$request->filled('start_date') && !$request->filled('end_date')) {
             $query->where('year', now()->year);
        }

        return $query;
    }

    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('expenses.create', compact('categories', 'churches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $expense = new Expense($validated);
        $expense->entered_by = auth()->id();
        
        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $expense->receipt_path = $path;
        }
        
        $expense->save();

        // Notify Bosses of new expense
        $bosses = \App\Models\User::role('boss')->get();
        \Illuminate\Support\Facades\Notification::send($bosses, new \App\Notifications\ExpenseSubmitted($expense));

        $this->logActivity('created', "Created expense of {$expense->amount} for {$expense->description}", 'expenses');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('expenses.edit', compact('expense', 'categories', 'churches'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $expense->fill($validated);
        
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $expense->receipt_path = $path;
        }
        
        $expense->save();

        $this->logActivity('updated', "Updated expense #{$expense->id}", 'expenses');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        $this->logActivity('deleted', "Deleted expense #{$expense->id}", 'expenses');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }

    public function approve(Request $request, Expense $expense)
    {
        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->notes,
        ]);

        // Notify submitter
        if ($expense->entered_by) {
            $submitter = \App\Models\User::find($expense->entered_by);
            if ($submitter) {
                $submitter->notify(new \App\Notifications\ExpenseStatusUpdated($expense, 'approved'));
            }
        }

        $this->logActivity('approved', "Approved expense #{$expense->id}", 'expenses');

        return back()->with('success', 'Expense approved!');
    }

    public function reject(Request $request, Expense $expense)
    {
        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->notes,
        ]);

        // Notify submitter
        if ($expense->entered_by) {
            $submitter = \App\Models\User::find($expense->entered_by);
            if ($submitter) {
                $submitter->notify(new \App\Notifications\ExpenseStatusUpdated($expense, 'rejected'));
            }
        }

        $this->logActivity('rejected', "Rejected expense #{$expense->id}", 'expenses');

        return back()->with('success', 'Expense rejected!');
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
