<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;
        $response = $user->toArray();
        $response['token'] = $token;
        return response()->json([
            'user' => $response,
        ]);
    }

    /**
     * delete user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'message' => 'Success'
        ]);
    }
}
