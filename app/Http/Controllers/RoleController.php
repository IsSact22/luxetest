<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5)
            ->withQueryString();
        $resource = RoleResource::collection($roles);

        return InertiaResponse::content('Users/Roles/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $permissions = Permission::get();

        return InertiaResponse::content('Users/Roles/Create', ['resource' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): \Illuminate\Http\RedirectResponse
    {
        $roleData = $request->only(['name', 'guard_name']);
        $role = Role::create($roleData);
        $role->syncPermissions($request->get('permissions'));

        return to_route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): \Inertia\Response
    {
        $rolePermissions = Permission::join(
            'role_has_permissions',
            'role_has_permissions.permission_id',
            '=',
            'permissions.id'
        )
            ->where('role_has_permissions.role_id', $role->id)
            ->get();

        return InertiaResponse::content('Users/Roles/Show', ['resource' => $rolePermissions]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): \Inertia\Response
    {
        $permissions = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return InertiaResponse::content('Users/Roles/Edit', [
            'resource' => [
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $role->name = $request->input('name');
        if ($request->has('guard_name')) {
            $role->guard_name = $request->get('guard_name');
        }
        $role->save();
        $role->syncPermissions($request->input('permissions'));

        return to_route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return to_route('roles.index');
    }
}
