<?php

namespace App\Services\UserServices;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function getUserById(int $id, User $userModel)
    {
        return $userModel::where('id', $id)->first();
    }

    public function getUsers(User $userModel)
    {
        return $userModel::orderByDesc('id')->get();
    }

    public function createUser(array $data, User $userModel): User
    {
        $user = $userModel::create($this->usersData($data));
        if (isset($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $user->roles()->attach($roles);
        }
        return $user;

    }

    public function editUser(User $User, array $data): void
    {
        $User->update($this->usersData($data));
        if (isset($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $User->roles()->sync($roles);
        }
    }

    public function deleteUser(User $User): void
    {
        $User->delete();
    }
    public function assignRolesToUser(array $roleIds, User $user): void
    {
        $user->roles()->attach($roleIds);
    }


    private function usersData(array $data)
    {
        return [
            'name' => $data['name'],
            'email' =>$data['email'],
            'password' => $data['password'],
        ];
    }
}
