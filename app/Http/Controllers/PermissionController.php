<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Show the admin UI: a list of roles, each with their permissions.
     * Only admins should see this — enforced by middleware on the route.
     */
    public function index()
    {
        // with('permissions') eagerly loads the permissions relationship
        // so we don't run N+1 queries (one query per role)
        $roles = Role::with('permissions')->get();

        // Permission::all() fetches every row from the permissions table
        $permissions = Permission::all();

        // compact() is a PHP shortcut: creates ['roles'=>$roles, 'permissions'=>$permissions]
        return view('admin.permissions', compact('roles', 'permissions'));
    }

    /**
     * Save the checkboxes the admin submitted.
     * The form sends: role_id (which role) and permission_ids[] (which permissions)
     */
    public function update(Request $request)
    {
        $request->validate([
            'role_id'          => 'required|exists:roles,id',
            // nullable: the array can be empty (admin unchecked everything)
            'permission_ids'   => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        // Find the Role model by its primary key (ID)
        $role = Role::findOrFail($request->role_id);

        // sync() is a BelongsToMany method that:
        //   - Adds new permission_role rows for checked boxes
        //   - Removes permission_role rows for unchecked boxes
        //   - Leaves existing rows unchanged if they're still checked
        // ?? [] means "if permission_ids is null, use an empty array instead"
        $role->permissions()->sync($request->permission_ids ?? []);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions updated successfully!');
    }
}
