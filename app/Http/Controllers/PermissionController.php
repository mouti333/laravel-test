<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionServices\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    private PermissionService $permissionService;
    private Permission $permissionModel;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->permissionModel = new Permission();
        $this->roleModel = new Role();
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->createPermission($request->validated(), $this->permissionModel);

        return response()->json([
            'message' => "Permission created successfully",
            'data' => $permission
        ], 201);
    }

    public function update(UpdatePermissionRequest $request, int $id): JsonResponse
    {

        $permission = $this->permissionService->getPermissionById($id, $this->permissionModel);

         $this->permissionService->editPermission($permission, $request->validated());

        return response()->json([
            'message' => "Permission modified succesfully.",
            'data' => $permission
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
        $permission = $this->permissionService->getPermissionById($id, $this->permissionModel);

        $this->permissionService->deletePermission($permission);

        return response()->json(['message' => "Permission deleted succesfully"], 202);
    }
}
