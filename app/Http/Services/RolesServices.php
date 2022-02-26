<?php

namespace App\Http\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


trait RolesServices
{
    public function createRoleService($request)
    {
        $role = Role::create($request->toArray());
        // $permission = Permission::create(['name' => 'edit articles']);
        foreach ($request->permissions as $permission) {
            $role->givePermissionTo($permission);
        }


        return response()->json([
            "role" =>  $role,
        ], 201);
    }
    public function getRolesService($request)
    {
        // testing
        // $role =   Role::first();
        // $user = $request->user();
        // $permission = Permission::first();

        // $role->givePermissionTo($permission);
        // $user->assignRole('writer');

        // testing
        $roles = Role::orderByDesc("id")->paginate(10);

        return response()->json([
            "roles" => $roles,
        ], 200);
    }
    public function getAllRolesService($request)
    {
        $roles = Role::with('permissions:name,id')->select("name", "id")->get();
        return response()->json([
            "roles" => $roles,
        ], 200);
    }
}
