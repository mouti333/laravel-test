<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserServices\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    private User $userModel;
    const USER_NOT_FOUND_MESSAGE = "User not found";

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->userModel = new User();
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id, $this->userModel);

        if (!$user) {
            return response()->json(['message' => self::USER_NOT_FOUND_MESSAGE], 404);
        }

        return response()->json($user);
    }

    public function index(): JsonResponse
    {
        return response()->json($this->userService->getUsers($this->userModel));
    }


    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated(), $this->userModel);

        return response()->json([
            'message' => "User created successfully",
            'data' => $user
        ], 201);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id, $this->userModel);

        $this->userService->editUser($user, $request->validated());

        return response()->json([
            'message' => "User modified succesfully.",
            'data' => $user
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
        $user = $this->userService->getUserById($id, $this->userModel);

        $this->userService->deleteUser($user);

        return response()->json(['message' => "User deleted succesfully"], 202);
    }

    public function assignRolesToUser(StoreRoleUserRequest $request,int $userId)
    {
        $user = User::findOrFail($userId);
        $this->userService->assignRolesToUser($request->validated()['roles_ids'], $user);
        return response()->json([
            'message' => 'Permissions assigned to role successfully',
        ]);
    }

    public function uploadProfilePicture(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
        $user->save();
        return response()->json([
            'message' => 'Profile picture uploaded successfully',
            'user' => $user,
        ]);
    }


}
