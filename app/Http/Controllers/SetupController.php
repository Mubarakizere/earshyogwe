<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupController extends Controller
{
    /**
     * Run the permission seeder online (for production servers without terminal access)
     */
    public function seedPermissions(Request $request)
    {
        // Security: Only allow if user is boss or has manage roles permission
        if (!auth()->check() || (!auth()->user()->hasRole('boss') && !auth()->user()->can('manage roles'))) {
            abort(403, 'Unauthorized. Only Boss or users with "manage roles" permission can seed permissions.');
        }

        // Optional: Add a secret key check for extra security
        if ($request->get('key') !== config('app.key')) {
            abort(403, 'Invalid security key.');
        }

        try {
            // Clear cached permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Run the seeder
            \Artisan::call('db:seed', ['--class' => 'RoleAndPermissionSeeder']);
            
            $output = \Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Permissions seeded successfully!',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error seeding permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a simple UI for seeding permissions
     */
    public function seedPermissionsForm()
    {
        // Security check
        if (!auth()->check() || (!auth()->user()->hasRole('boss') && !auth()->user()->can('manage roles'))) {
            abort(403, 'Unauthorized.');
        }

        return view('admin.seed-permissions');
    }
}
