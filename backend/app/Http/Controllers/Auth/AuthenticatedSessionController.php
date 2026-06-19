<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request (Login via Token).
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var \App\Models\User $user */ 
        $user = Auth::user();

        // Sekarang Intelephense tahu createToken() itu valid dan ada di model User
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'role'    => $user->role, 
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    /**
     * Destroy an authenticated session (Logout / Revoke Token).
     */
    public function destroy(Request $request): JsonResponse
    {
        // Menghapus token yang sedang digunakan dari database
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil, token telah dihapus.'
        ], 200);
    }
}