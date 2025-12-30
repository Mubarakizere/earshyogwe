<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Giving Type Management (Boss only - controlled in views)
    Route::resource('giving-types', \App\Http\Controllers\GivingTypeController::class);
    Route::post('giving-types/{givingType}/sub-types', [\App\Http\Controllers\GivingTypeController::class, 'storeSubType'])->name('giving-types.sub-types.store');
    Route::delete('giving-sub-types/{givingSubType}', [\App\Http\Controllers\GivingTypeController::class, 'destroySubType'])->name('giving-sub-types.destroy');
    
    // Giving Entry (Pastor, Archid, Boss)
    Route::resource('givings', \App\Http\Controllers\GivingController::class);
    
    // Expense Category Management (Boss only - controlled in views)
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class);
    
    // Expense Entry (Pastor, Archid, Boss)
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::post('expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Evangelism Reports (Pastor, Archid, Boss)
    Route::resource('evangelism-reports', \App\Http\Controllers\EvangelismReportController::class);
    
    // Activities (Pastor, Archid, Boss)
    Route::resource('activities', \App\Http\Controllers\ActivityController::class);
    
    // HR Management - Workers (Pastor, Archid, Boss)
    Route::resource('workers', \App\Http\Controllers\WorkerController::class);
    
    // Attendance Management (Pastor, Archid, Boss)
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
});

require __DIR__.'/auth.php';
