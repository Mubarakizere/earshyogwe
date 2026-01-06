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
        
        // Permission-based filtering
        if ($user->can('view all expenses')) {
             // See all
        } elseif ($user->can('view assigned expenses') && $user->hasRole('archid')) {
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
