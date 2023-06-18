<?php

namespace App\Services\RoleServices;

use App\Models\Role;

class RoleService
{

    public function getRoleById(int $id, Role $roleModel)
    {
        return $roleModel::where('id', $id)->first();
    }


    public function createRole(array $data, Role $roleModel): Role
    {
        return $roleModel::create($this->rolesData($data));

    }

    public function editRole(Role $Role, array $data): void
    {
        $Role->update($this->rolesData($data));
    }

    public function deleteRole(Role $Role): void
    {
        $Role->delete();
    }
    public function assignPermissionsToRole(array $permissionIds, Role $role): void
    {
        $role->permissions()->sync($permissionIds);
    }


    private function rolesData(array $data)
    {
        return [
            'name' => $data['name'],
        ];
    }
}

