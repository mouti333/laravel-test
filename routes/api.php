<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);


Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::prefix('permissions')->group(function () {
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{idPermission}', [PermissionController::class, 'update']);
        Route::delete('/{idPermission}', [PermissionController::class, 'destroy']);
    });
    Route::prefix('roles')->group(function () {
        Route::post('/', [RoleController::class, 'store']);
        Route::post('/{idRole}/assign-permissions-role', [RoleController::class, 'assignPermissionsToRole']);
        Route::put('/{idRole}', [RoleController::class, 'update']);
        Route::delete('/{idRole}', [RoleController::class, 'destroy']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{idUser}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::post('/{idUser}/assign-roles-user', [UserController::class, 'assignRolesToUser']);
        Route::post('/{idUser}/upload-profile-picture', [UserController::class, 'uploadProfilePicture']);
        Route::put('/{idUser}', [UserController::class, 'update']);
        Route::delete('/{idUser}', [UserController::class, 'destroy']);
});
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index']);
        Route::get('/{idMessage}', [MessageController::class, 'show']);
        Route::get('/{idUser}/received-message', [MessageController::class, 'getUserMessageReciever']);
        Route::get('/{idUser}/sender-message', [MessageController::class, 'getUserMessageSend']);
        Route::post('/', [MessageController::class, 'store']);
        Route::put('/{idMessage}', [MessageController::class, 'update']);
        Route::delete('/{idMessage}', [MessageController::class, 'destroy']);
});
});


