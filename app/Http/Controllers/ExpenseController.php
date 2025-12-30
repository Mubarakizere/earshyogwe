<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Church;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
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
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', now()->year);
        }
        
        $expenses = $query->latest('date')->paginate(20);
        
        $categories = ExpenseCategory::where('is_active', true)->get();
        $churches = $this->getChurchesForUser($user);
        
        $totalAmount = $query->sum('amount');
        $pendingCount = Expense::pending()->count();
        
        return view('expenses.index', compact('expenses', 'categories', 'churches', 'totalAmount', 'pendingCount'));
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

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

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
