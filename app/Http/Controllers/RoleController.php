<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\LogsActivity;

class RoleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $this->authorize('manage users'); // Re-using this permission for now, or could check 'manage roles' if added
        
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('manage users');
        
        $permissions = Permission::all()->groupBy(function($data) {
            // Group logic: "view all churches" -> "Church Management"
            // Simple heuristic based on known groups or keywords?
            // For now, let's just pass them flattened or grouped by first word.
            return 'All Permissions'; 
        });
        
        // Better Grouping Manual logic
        $groupedPermissions = $this->groupPermissions(Permission::all());

        return view('roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage users');
        
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        self::log('created', "Created role {$role->name}", 'roles', $role);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorize('manage users');
        
        if ($role->name === 'boss') {
            return redirect()->back()->with('error', 'Cannot edit the Boss role.');
        }

        $groupedPermissions = $this->groupPermissions(Permission::all());
        
        return view('roles.edit', compact('role', 'groupedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('manage users');

        if ($role->name === 'boss') {
            return redirect()->back()->with('error', 'Cannot edit the Boss role.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        self::log('updated', "Updated role {$role->name}", 'roles', $role);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('manage users');

        if (in_array($role->name, ['boss', 'pastor', 'archid'])) { // Protect core roles
             return redirect()->back()->with('error', 'Cannot delete system roles.');
        }

        $role->delete();
        self::log('deleted', "Deleted role {$role->name}", 'roles', $role);

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    private function groupPermissions($permissions)
    {
        // Organize permissions into categories for the UI
        $groups = [
            'Church Management' => ['church'],
            'Giving Management' => ['giving'],
            'Expense Management' => ['expense'],
            'Evangelism' => ['evangelism'],
            'Activities' => ['activit'],
            'HR Management' => ['worker', 'contract', 'retirement'],
            'User & Role' => ['user', 'role', 'permission'],
            'Department' => ['department'],
            'Population' => ['member', 'population'],
            'Audit' => ['log'],
        ];

        $grouped = [];
        $misc = [];

        foreach ($permissions as $perm) {
            $matched = false;
            foreach ($groups as $groupName => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($perm->name, $keyword)) {
                        $grouped[$groupName][] = $perm;
                        $matched = true;
                        break;
                    }
                }
                if ($matched) break;
            }
            if (!$matched) {
                $misc[] = $perm;
            }
        }
        
        if (!empty($misc)) {
            $grouped['Miscellaneous'] = $misc;
        }

        return $grouped;
    }
}
