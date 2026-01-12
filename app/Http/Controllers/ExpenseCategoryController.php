<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('manage expense categories');
        
        $categories = ExpenseCategory::with('creator')->latest()->get();
        return view('expense-categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('manage expense categories');
        
        return view('expense-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage expense categories');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_approval' => 'boolean',
        ]);

        ExpenseCategory::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense category created successfully!');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $this->authorize('manage expense categories');
        
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->authorize('manage expense categories');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense category updated successfully!');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->authorize('manage expense categories');
        
        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense category deleted successfully!');
    }
}
