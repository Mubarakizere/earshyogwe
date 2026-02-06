<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('login');
});

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales'))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/settings', [\App\Http\Controllers\NotificationController::class, 'settings'])->name('notifications.settings');
    Route::post('/notifications/settings', [\App\Http\Controllers\NotificationController::class, 'updateSettings'])->name('notifications.updateSettings');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/latest', [\App\Http\Controllers\NotificationController::class, 'latest'])->name('notifications.latest');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/read', [\App\Http\Controllers\NotificationController::class, 'destroyRead'])->name('notifications.destroyRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');


    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class)->middleware(['permission:manage users']);
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware(['permission:manage roles']);
    
    // Archdeacon Management
    Route::get('archdeacons', [\App\Http\Controllers\ArchdeaconController::class, 'index'])->name('archdeacons.index')->middleware(['permission:manage users|permission:view all churches']);
    Route::get('archdeacons/{user}/edit', [\App\Http\Controllers\ArchdeaconController::class, 'edit'])->name('archdeacons.edit')->middleware(['permission:manage users|permission:view all churches']);
    Route::put('archdeacons/{user}', [\App\Http\Controllers\ArchdeaconController::class, 'update'])->name('archdeacons.update')->middleware(['permission:manage users|permission:view all churches']);
    
    // Audit Logs
    Route::resource('activity-logs', \App\Http\Controllers\ActivityLogController::class)->only(['index'])->middleware(['permission:view activity logs']);
    
    // Giving Type Management
    Route::resource('giving-types', \App\Http\Controllers\GivingTypeController::class)->middleware(['permission:manage giving types']);
    Route::post('giving-types/{givingType}/sub-types', [\App\Http\Controllers\GivingTypeController::class, 'storeSubType'])->name('giving-types.sub-types.store')->middleware(['permission:manage giving types']);
    Route::delete('giving-sub-types/{givingSubType}', [\App\Http\Controllers\GivingTypeController::class, 'destroySubType'])->name('giving-sub-types.destroy')->middleware(['permission:manage giving types']);
    
    // Giving Entry
    Route::get('givings/export', [\App\Http\Controllers\GivingController::class, 'export'])->name('givings.export');
    Route::get('givings/export-pdf', [\App\Http\Controllers\GivingController::class, 'exportPdf'])->name('givings.exportPdf');
    Route::post('givings/{giving}/mark-sent', [\App\Http\Controllers\GivingController::class, 'markAsSent'])->name('givings.markAsSent');
    Route::post('givings/mark-all-sent/{date}/{church_id}', [\App\Http\Controllers\GivingController::class, 'markAllAsSent'])->name('givings.markAllAsSent');
    Route::post('givings/{giving}/verify-receipt', [\App\Http\Controllers\GivingController::class, 'verifyReceipt'])->name('givings.verifyReceipt');
    Route::get('givings/details/{date}/{church_id}', [\App\Http\Controllers\GivingController::class, 'details'])->name('givings.details');
    Route::delete('givings/bulk/{date}/{church_id}', [\App\Http\Controllers\GivingController::class, 'destroyBulk'])->name('givings.destroyBulk');
    Route::resource('givings', \App\Http\Controllers\GivingController::class);
    
    // Diocese Validations
    Route::get('/diocese/transfers', [\App\Http\Controllers\DioceseTransferController::class, 'index'])->name('diocese.transfers.index');
    Route::post('/diocese/transfers/{giving}/verify', [\App\Http\Controllers\DioceseTransferController::class, 'verify'])->name('diocese.transfers.verify');
    Route::post('/diocese/transfers/{giving}/reject', [\App\Http\Controllers\DioceseTransferController::class, 'reject'])->name('diocese.transfers.reject');
    
    // Parish Transfers (Custom Amount Transfers)
    Route::get('parish-transfers', [\App\Http\Controllers\ParishTransferController::class, 'index'])->name('parish-transfers.index');
    Route::get('parish-transfers/create', [\App\Http\Controllers\ParishTransferController::class, 'create'])->name('parish-transfers.create');
    Route::post('parish-transfers', [\App\Http\Controllers\ParishTransferController::class, 'store'])->name('parish-transfers.store');
    Route::get('parish-transfers/{transfer}', [\App\Http\Controllers\ParishTransferController::class, 'show'])->name('parish-transfers.show');
    Route::delete('parish-transfers/{transfer}', [\App\Http\Controllers\ParishTransferController::class, 'destroy'])->name('parish-transfers.destroy');
    Route::post('parish-transfers/{transfer}/verify', [\App\Http\Controllers\ParishTransferController::class, 'verify'])->name('parish-transfers.verify');
    Route::post('parish-transfers/{transfer}/reject', [\App\Http\Controllers\ParishTransferController::class, 'reject'])->name('parish-transfers.reject');
    
    // Expense Category Management
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class)->middleware(['permission:manage expense categories']);
    
    // Expense Entry
    Route::get('expenses/export', [\App\Http\Controllers\ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('expenses/export-pdf', [\App\Http\Controllers\ExpenseController::class, 'exportPdf'])->name('expenses.exportPdf');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::post('expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Church Groups Management
    Route::resource('church-groups', \App\Http\Controllers\ChurchGroupController::class)->middleware(['permission:manage church groups']);
    
    // Evangelism Reports (Permission-based - checked in controller)
    Route::resource('evangelism-reports', \App\Http\Controllers\EvangelismReportController::class);
    
    // Objectives (Goals assigned by Heads)
    Route::get('objectives/export', [\App\Http\Controllers\ObjectiveController::class, 'export'])->name('objectives.export');
    Route::get('objectives/export-pdf', [\App\Http\Controllers\ObjectiveController::class, 'exportPdf'])->name('objectives.exportPdf');

    // Server Helper for Shared Hosting (Run Artisan Commands)
    Route::get('/server/run/{command}', function ($command) {
        // Only allow specific commands for safety
        $allowed = ['optimize:clear', 'config:cache', 'view:clear', 'migrate', 'storage:link', 'about'];
        
        if (!in_array($command, $allowed)) {
            return "Command '$command' is not in the allowed list.";
        }
        
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            return "<h3>Command: $command</h3><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "<h3>Error executing $command</h3><pre>" . $e->getMessage() . "</pre>";
        }
    })->middleware(['auth', 'permission:manage users']); // Restricted to admins

    Route::get('objectives/export-pdf', [\App\Http\Controllers\ObjectiveController::class, 'exportPdf'])->name('objectives.exportPdf');
    Route::post('objectives/{objective}/approve', [\App\Http\Controllers\ObjectiveController::class, 'approve'])->name('objectives.approve');
    Route::post('objectives/{objective}/reject', [\App\Http\Controllers\ObjectiveController::class, 'reject'])->name('objectives.reject');
    Route::resource('objectives', \App\Http\Controllers\ObjectiveController::class);

    // Objective Reports (Pastors reporting on Objectives)
    Route::get('objectives/{objective}/report', [\App\Http\Controllers\ObjectiveReportController::class, 'create'])->name('objectives.report.create');
    Route::post('objectives/{objective}/report', [\App\Http\Controllers\ObjectiveReportController::class, 'store'])->name('objectives.report.store');
    
    // Workers & HR
    Route::get('workers/export', [\App\Http\Controllers\WorkerController::class, 'export'])->name('workers.export');
    Route::get('workers/export-pdf', [\App\Http\Controllers\WorkerController::class, 'exportPdf'])->name('workers.exportPdf');
    Route::get('workers/trashed', [\App\Http\Controllers\WorkerController::class, 'trashed'])->name('workers.trashed');
    Route::post('workers/{id}/restore', [\App\Http\Controllers\WorkerController::class, 'restore'])->name('workers.restore');
    Route::delete('workers/{id}/force-delete', [\App\Http\Controllers\WorkerController::class, 'forceDelete'])->name('workers.force-delete');
    Route::delete('worker-documents/{document}', [\App\Http\Controllers\WorkerController::class, 'destroyDocument'])->name('worker-documents.destroy');
    Route::get('worker-documents/{document}/download', [\App\Http\Controllers\WorkerController::class, 'downloadDocument'])->name('worker-documents.download');
    Route::resource('workers', \App\Http\Controllers\WorkerController::class);
    
    // Institutions Management
    Route::resource('institutions', \App\Http\Controllers\InstitutionController::class)->middleware(['permission:manage institutions']);
    
    // Custom Fields Management (Phase 2)
    Route::resource('custom-fields', \App\Http\Controllers\CustomFieldController::class)->middleware(['permission:manage custom fields']);
    
    // Service Type Management
    Route::resource('service-types', \App\Http\Controllers\ServiceTypeController::class)->middleware(['role:boss|archid']);
    
    // Churches Management (Permission-based - checked in controller)
    Route::get('churches/export', [\App\Http\Controllers\ChurchController::class, 'export'])->name('churches.export');
    Route::get('churches/export-pdf', [\App\Http\Controllers\ChurchController::class, 'exportPdf'])->name('churches.exportPdf');
    Route::resource('churches', \App\Http\Controllers\ChurchController::class);
    
    // Departments / Activity Types (Permission-based - checked in controller)
    Route::get('departments/export', [\App\Http\Controllers\DepartmentController::class, 'export'])->name('departments.export');
    Route::get('departments/export-pdf', [\App\Http\Controllers\DepartmentController::class, 'exportPdf'])->name('departments.exportPdf');
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    
    // Attendance Management (Permission-based - checked in controller)
    Route::get('attendances/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendances.export');
    Route::get('attendances/export-pdf', [\App\Http\Controllers\AttendanceController::class, 'exportPdf'])->name('attendances.exportPdf');
    Route::delete('attendance-documents/{document}', [\App\Http\Controllers\AttendanceController::class, 'deleteDocument'])->name('attendance-documents.destroy');
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    // Service Types Management  
    Route::resource('service-types', \App\Http\Controllers\ServiceTypeController::class)->middleware(['permission:manage service types']);
    
    // Members Management (Permission-based - checked in controller)
    Route::get('members/export', [\App\Http\Controllers\MemberController::class, 'export'])->name('members.export');
    Route::get('members/export-pdf', [\App\Http\Controllers\MemberController::class, 'exportPdf'])->name('members.exportPdf');
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    
    // Member Transfers (Permission-based - checked in controller)
    Route::get('member-transfers', [\App\Http\Controllers\MemberTransferController::class, 'index'])->name('member-transfers.index');
    Route::get('member-transfers/create', [\App\Http\Controllers\MemberTransferController::class, 'create'])->name('member-transfers.create');
    Route::post('member-transfers', [\App\Http\Controllers\MemberTransferController::class, 'store'])->name('member-transfers.store');
    Route::get('member-transfers/{memberTransfer}', [\App\Http\Controllers\MemberTransferController::class, 'show'])->name('member-transfers.show');
    Route::delete('member-transfers/{memberTransfer}', [\App\Http\Controllers\MemberTransferController::class, 'destroy'])->name('member-transfers.destroy');
    Route::post('member-transfers/{memberTransfer}/approve', [\App\Http\Controllers\MemberTransferController::class, 'approve'])->name('member-transfers.approve');
    Route::post('member-transfers/{memberTransfer}/reject', [\App\Http\Controllers\MemberTransferController::class, 'reject'])->name('member-transfers.reject');
    
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

// Temporary Debug Route for PDF Issue
Route::get('/debug-pdf', function () {
    // 1. Clear Cache
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $cacheStatus = "Cache cleared successfully!";
    } catch (\Exception $e) {
        $cacheStatus = "Cache clear failed: " . $e->getMessage();
    }
    
    // 2. Check Paths
    $path = base_path('vendor/barryvdh/laravel-dompdf/src/Facade/Pdf.php');
    $exists = file_exists($path);
    $classExists = class_exists('Barryvdh\DomPDF\Facade\Pdf');
    
    return [
        'message' => $cacheStatus,
        'file_path' => $path,
        'file_exists_on_disk' => $exists ? 'YES' : 'NO (Action: Re-upload vendor/barryvdh folder)',
        'class_autoloadable' => $classExists ? 'YES' : 'NO (Action: Re-upload vendor/composer folder)',
        'vendor_folder_exists' => is_dir(base_path('vendor')) ? 'YES' : 'NO',
        'php_version' => phpversion(),
    ];
});

