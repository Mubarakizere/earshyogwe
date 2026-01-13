<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class)->middleware(['permission:manage users']);
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware(['permission:manage roles']);
    
    // Audit Logs
    Route::resource('activity-logs', \App\Http\Controllers\ActivityLogController::class)->only(['index'])->middleware(['permission:view activity logs']);
    
    // Giving Type Management
    Route::resource('giving-types', \App\Http\Controllers\GivingTypeController::class)->middleware(['permission:manage giving types']);
    Route::post('giving-types/{givingType}/sub-types', [\App\Http\Controllers\GivingTypeController::class, 'storeSubType'])->name('giving-types.sub-types.store')->middleware(['permission:manage giving types']);
    Route::delete('giving-sub-types/{givingSubType}', [\App\Http\Controllers\GivingTypeController::class, 'destroySubType'])->name('giving-sub-types.destroy')->middleware(['permission:manage giving types']);
    
    // Giving Entry
    Route::get('givings/export', [\App\Http\Controllers\GivingController::class, 'export'])->name('givings.export');
    Route::post('givings/{giving}/mark-sent', [\App\Http\Controllers\GivingController::class, 'markAsSent'])->name('givings.markAsSent');
    Route::post('givings/{giving}/verify-receipt', [\App\Http\Controllers\GivingController::class, 'verifyReceipt'])->name('givings.verifyReceipt');
    Route::get('givings/details/{date}/{church_id}', [\App\Http\Controllers\GivingController::class, 'details'])->name('givings.details');
    Route::delete('givings/bulk/{date}/{church_id}', [\App\Http\Controllers\GivingController::class, 'destroyBulk'])->name('givings.destroyBulk');
    Route::resource('givings', \App\Http\Controllers\GivingController::class);
    
    // Diocese Validations
    Route::get('/diocese/transfers', [\App\Http\Controllers\DioceseTransferController::class, 'index'])->name('diocese.transfers.index');
    Route::post('/diocese/transfers/{giving}/verify', [\App\Http\Controllers\DioceseTransferController::class, 'verify'])->name('diocese.transfers.verify');
    Route::post('/diocese/transfers/{giving}/reject', [\App\Http\Controllers\DioceseTransferController::class, 'reject'])->name('diocese.transfers.reject');
    
    // Expense Category Management
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class)->middleware(['permission:manage expense categories']);
    
    // Expense Entry
    Route::get('expenses/export', [\App\Http\Controllers\ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::post('expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Evangelism Reports (Permission-based - checked in controller)
    Route::resource('evangelism-reports', \App\Http\Controllers\EvangelismReportController::class);
    
    // Activities (Permission-based - checked in controller)
    // Activities (Pastor, Archid, Boss)
    Route::get('activities/export', [\App\Http\Controllers\ActivityController::class, 'export'])->name('activities.export');
    Route::post('activities/{activity}/approve', [\App\Http\Controllers\ActivityController::class, 'approve'])->name('activities.approve');
    Route::post('activities/{activity}/reject', [\App\Http\Controllers\ActivityController::class, 'reject'])->name('activities.reject');
    Route::post('activities/{activity}/complete', [\App\Http\Controllers\ActivityController::class, 'markComplete'])->name('activities.complete');
    Route::resource('activities', \App\Http\Controllers\ActivityController::class);
    
    // Workers & HR
    Route::get('workers/export', [\App\Http\Controllers\WorkerController::class, 'export'])->name('workers.export');
    Route::resource('workers', \App\Http\Controllers\WorkerController::class);
    
    // Service Type Management
    Route::resource('service-types', \App\Http\Controllers\ServiceTypeController::class)->middleware(['role:boss|archid']);
    
    // Churches Management (Permission-based - checked in controller)
    Route::get('churches/export', [\App\Http\Controllers\ChurchController::class, 'export'])->name('churches.export');
    Route::resource('churches', \App\Http\Controllers\ChurchController::class);
    
    // Departments / Activity Types (Permission-based - checked in controller)
    Route::get('departments/export', [\App\Http\Controllers\DepartmentController::class, 'export'])->name('departments.export');
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    
    // Attendance Management (Permission-based - checked in controller)
    Route::get('attendances/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendances.export');
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    // Service Types Management  
    Route::resource('service-types', \App\Http\Controllers\ServiceTypeController::class)->middleware(['permission:manage service types']);
    
    // Members Management (Permission-based - checked in controller)
    Route::get('members/export', [\App\Http\Controllers\MemberController::class, 'export'])->name('members.export');
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    
    // Population Census (Pastor, Archid, Boss)
    Route::resource('population-censuses', \App\Http\Controllers\PopulationCensusController::class)->middleware(['role:boss|archid|pastor']);
});

// ============================================
// Database Management Routes (for shared hosting)
// ============================================
// IMPORTANT: Only accessible when APP_DEBUG is true
// After running, set APP_DEBUG=false in .env for security
if (config('app.debug')) {
    
    // Run all seeders
    Route::get('/run-seeders', function () {
        try {
            Artisan::call('db:seed');
            return '<h1>✅ Seeders executed successfully!</h1>' . 
                   '<pre>' . Artisan::output() . '</pre>' .
                   '<a href="/dashboard">Go to Dashboard</a>';
        } catch (\Exception $e) {
            return '<h1>❌ Error running seeders</h1>' . 
                   '<pre>' . $e->getMessage() . '</pre>';
        }
    });
    
    // Run specific seeder
    Route::get('/run-seeder/{seeder}', function ($seeder) {
        try {
            $seederClass = "Database\\Seeders\\{$seeder}";
            
            if (!class_exists($seederClass)) {
                return '<h1>❌ Seeder not found</h1>' . 
                       '<p>Seeder class: ' . $seederClass . ' does not exist.</p>';
            }
            
            Artisan::call('db:seed', ['--class' => $seederClass]);
            return '<h1>✅ Seeder executed successfully!</h1>' . 
                   '<p>Seeder: ' . $seeder . '</p>' .
                   '<pre>' . Artisan::output() . '</pre>' .
                   '<a href="/dashboard">Go to Dashboard</a>';
        } catch (\Exception $e) {
            return '<h1>❌ Error running seeder</h1>' . 
                   '<pre>' . $e->getMessage() . '</pre>';
        }
    });
    
    // Run migrations
    Route::get('/run-migrations', function () {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return '<h1>✅ Migrations executed successfully!</h1>' . 
                   '<pre>' . Artisan::output() . '</pre>' .
                   '<a href="/dashboard">Go to Dashboard</a>';
        } catch (\Exception $e) {
            return '<h1>❌ Error running migrations</h1>' . 
                   '<pre>' . $e->getMessage() . '</pre>';
        }
    });
    
    // Clean up orphaned departments (departments without churches)
    Route::get('/cleanup-orphaned-departments', function () {
        try {
            $orphaned = \App\Models\Department::whereDoesntHave('church')->get();
            $count = $orphaned->count();
            
            if ($count > 0) {
                \App\Models\Department::whereDoesntHave('church')->delete();
                return '<h1>✅ Cleanup successful!</h1>' . 
                       '<p>Deleted ' . $count . ' orphaned department(s).</p>' .
                       '<a href="/departments">Go to Departments</a>';
            } else {
                return '<h1>✅ No cleanup needed</h1>' . 
                       '<p>No orphaned departments found.</p>' .
                       '<a href="/departments">Go to Departments</a>';
            }
        } catch (\Exception $e) {
            return '<h1>❌ Error during cleanup</h1>' . 
                   '<pre>' . $e->getMessage() . '</pre>';
        }
    });
}

require __DIR__.'/auth.php';

