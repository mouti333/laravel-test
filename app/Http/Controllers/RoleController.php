<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Models\Permission;
use App\Services\RoleServices\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    private RoleService $roleService;
    private Role $roleModel;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->roleModel = new Role();
        $this->permissionModel = new Permission();
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated(), $this->roleModel);

        return response()->json([
            'message' => "Role created successfully",
            'data' => $role
        ], 201);
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id, $this->roleModel);

         $this->roleService->editRole($role, $request->validated());

        return response()->json([
            'message' => "Role modified succesfully.",
            'data' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id, $this->roleModel);

        $this->roleService->deleteRole($role);

        return response()->json(['message' => "Role deleted succesfully"], 202);
    }


    public function assignPermissionsToRole(StorePermissionRoleRequest $request, int $roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->roleService->assignPermissionsToRole($request->validated()['permission_ids'],$role);

        return response()->json([
            'message' => 'Permissions assigned to role successfully',
        ]);
    }


}
