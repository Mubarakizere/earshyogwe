<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAsRead');

    // User Management (Boss Only - checked in controller)
    // User Management (Boss Only - checked in controller)
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    
    // Audit Logs (Boss Only)
    Route::resource('activity-logs', \App\Http\Controllers\ActivityLogController::class)->only(['index']);
    
    // Giving Type Management (Boss only - controlled in views)
    Route::resource('giving-types', \App\Http\Controllers\GivingTypeController::class);
    Route::post('giving-types/{givingType}/sub-types', [\App\Http\Controllers\GivingTypeController::class, 'storeSubType'])->name('giving-types.sub-types.store');
    Route::delete('giving-sub-types/{givingSubType}', [\App\Http\Controllers\GivingTypeController::class, 'destroySubType'])->name('giving-sub-types.destroy');
    
    // Giving Entry (Pastor, Archid, Boss)
    Route::get('givings/export', [\App\Http\Controllers\GivingController::class, 'export'])->name('givings.export');
    Route::post('givings/{giving}/mark-sent', [\App\Http\Controllers\GivingController::class, 'markAsSent'])->name('givings.markAsSent');
    Route::post('givings/{giving}/verify-receipt', [\App\Http\Controllers\GivingController::class, 'verifyReceipt'])->name('givings.verifyReceipt');
    Route::resource('givings', \App\Http\Controllers\GivingController::class);
    
    // Diocese Validations
    Route::get('/diocese/transfers', [\App\Http\Controllers\DioceseTransferController::class, 'index'])->name('diocese.transfers.index');
    Route::post('/diocese/transfers/{giving}/verify', [\App\Http\Controllers\DioceseTransferController::class, 'verify'])->name('diocese.transfers.verify');
    Route::post('/diocese/transfers/{giving}/reject', [\App\Http\Controllers\DioceseTransferController::class, 'reject'])->name('diocese.transfers.reject');
    
    // Expense Category Management (Boss only - controlled in views)
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class);
    
    // Expense Entry (Pastor, Archid, Boss)
    Route::get('expenses/export', [\App\Http\Controllers\ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::post('expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Evangelism Reports (Pastor, Archid, Boss)
    Route::resource('evangelism-reports', \App\Http\Controllers\EvangelismReportController::class);
    
    // Activities (Pastor, Archid, Boss)
    // Activities (Pastor, Archid, Boss)
    Route::post('activities/{activity}/approve', [\App\Http\Controllers\ActivityController::class, 'approve'])->name('activities.approve');
    Route::post('activities/{activity}/reject', [\App\Http\Controllers\ActivityController::class, 'reject'])->name('activities.reject');
    Route::post('activities/{activity}/complete', [\App\Http\Controllers\ActivityController::class, 'markComplete'])->name('activities.complete');
    Route::resource('activities', \App\Http\Controllers\ActivityController::class);
    
    // HR Management - Workers (Pastor, Archid, Boss)
    Route::resource('workers', \App\Http\Controllers\WorkerController::class);
    
    // Service Type Management
    Route::resource('service-types', \App\Http\Controllers\ServiceTypeController::class)->middleware(['role:boss|archid']);
    
    // Departments / Activity Types
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    
    // Attendance Management (Pastor, Archid, Boss)
    Route::get('attendances/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendances.export');
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    // Church Management
    Route::resource('churches', \App\Http\Controllers\ChurchController::class);
    
    // Population Census (Pastor, Archid, Boss)
    Route::resource('population-censuses', \App\Http\Controllers\PopulationCensusController::class);
});

require __DIR__.'/auth.php';
