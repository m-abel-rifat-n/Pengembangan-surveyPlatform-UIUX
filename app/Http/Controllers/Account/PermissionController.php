<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::when(request()->q, function ($query) {
            $query->where('name', 'like', '%' . request()->q . '%');
        })->paginate(20);

        $permissions->appends(['q' => request()->q]);

        return inertia('Account/Permissions/Permissions', [
            'permissions' => $permissions
        ]);
    }

    public function create()
    {
        return inertia('Account/Permissions/Create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('account.permissions.index');
    }

    public function edit(String $permissions)
    {
        $permissions = Permission::findOrFail($permissions);

        return inertia('Account/Permissions/Edit', [
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string',
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('account.permissions.index');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('account.permissions.index');
    }
}
