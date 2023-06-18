<?php

namespace App\Services\PermissionServices;

use App\Models\Permission;
use App\Models\Role;

class PermissionService
{

    public function getPermissionById(int $id, Permission $permissionModel)
    {
        return $permissionModel::where('id', $id)->first();
    }

    public function createPermission(array $data, Permission $permissionModel): Permission
    {
        return $permissionModel::create($this->permissionData($data));

    }

    public function editPermission(Permission $Permission, array $data): void
    {
        $Permission->update($this->permissionData($data));
    }

    public function deletePermission(Permission $Permission): void
    {
        $Permission->delete();
    }

    private function permissionData(array $data)
    {
        return [
            'name' => $data['name'],
        ];
    }
}
