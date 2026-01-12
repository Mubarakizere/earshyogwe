<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Church;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpenseSubmitted;
use App\Notifications\ExpenseStatusUpdated;

class ExpenseController extends Controller
{

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
        
        // Permission-based filtering
        if ($user->can('view all expenses')) {
             // See all
        } elseif ($user->can('view assigned expenses')) {
             $churchIds = Church::where('archid_id', $user->id)->pluck('id');
             $query->whereIn('church_id', $churchIds);
        } elseif ($user->can('view own expenses') && $user->church_id) {
             $query->where('church_id', $user->church_id);
        } else {
             // Fallback for safety, or if they have no explicit view permission
             $query->where('id', 0); 
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
        $this->authorize('enter expenses');
        
        $categories = ExpenseCategory::where('is_active', true)->get();
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('expenses.create', compact('categories', 'churches'));
    }

    // ... store ...

    public function store(Request $request)
    {
        $this->authorize('enter expenses');
        
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:30720', // 30MB limit
        ]);

        // Process receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create([
            'church_id' => $validated['church_id'],
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'description' => $validated['description'],
            'receipt_path' => $receiptPath,
            'entered_by' => auth()->id(),
            // Status is handled by model boot event based on category
        ]);

        if ($expense->status === 'pending') {
            // Notify approvers
            $approvers = User::permission('approve expenses')->get();
            // Fallback to roles if permission query gives nothing (safety net)
            if ($approvers->isEmpty()) {
                $approvers = User::role(['boss', 'archid'])->get();
            }
            Notification::send($approvers, new ExpenseSubmitted($expense));
        }

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // Allow if approver OR (owner AND pending)
        if (!auth()->user()->can('approve expenses') && 
            ($expense->entered_by !== auth()->id() || $expense->status !== 'pending')) {
            abort(403);
        }
        
        $categories = ExpenseCategory::where('is_active', true)->get();
        // Include the current category even if inactive, so it displays correctly
        if (!$categories->contains('id', $expense->expense_category_id)) {
            $categories->push($expense->expenseCategory);
        }
        
        $churches = $this->getChurchesForUser(auth()->user());
        
        return view('expenses.edit', compact('expense', 'categories', 'churches'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Allow if approver OR (owner AND pending)
        if (!auth()->user()->can('approve expenses') && 
            ($expense->entered_by !== auth()->id() || $expense->status !== 'pending')) {
            abort(403);
        }

        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:30720', // 30MB limit
        ]);

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists (optional cleanup)
            // if ($expense->receipt_path) Storage::disk('public')->delete($expense->receipt_path);
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
            $expense->receipt_path = $receiptPath;
        }

        $expense->update([
            'church_id' => $validated['church_id'],
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        // Allow if approver OR (owner AND pending)
        if (!auth()->user()->can('approve expenses') && 
            ($expense->entered_by !== auth()->id() || $expense->status !== 'pending')) {
            abort(403);
        }
        $expense->delete();
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }

    public function approve(Request $request, Expense $expense)
    {
        $this->authorize('approve expenses');

        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->input('notes'),
        ]);

        // Notify submitter
        if ($expense->enteredBy) {
            $expense->enteredBy->notify(new ExpenseStatusUpdated($expense, 'approved'));
        }

        return back()->with('success', 'Expense approved successfully!');
    }

    public function reject(Request $request, Expense $expense)
    {
        $this->authorize('approve expenses');

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->input('notes'),
        ]);

        // Notify submitter
        if ($expense->enteredBy) {
            $expense->enteredBy->notify(new ExpenseStatusUpdated($expense, 'rejected'));
        }

        return back()->with('success', 'Expense rejected.');
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
