<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
            'provider' => ['required', Rule::in(['local', 'google'])],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->provider = $request->provider;
        
        // Jika menggunakan provider local (Email & Password), set password default.
        if ($request->provider === 'local') {
            $user->password = Hash::make('password123');
        }
        
        $user->save();

        return response()->json(['message' => 'Pengguna berhasil ditambahkan', 'data' => $user], 201);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
            'provider' => ['required', Rule::in(['local', 'google'])],
        ]);

        $user->update($request->only('name', 'email', 'role', 'provider'));

        return response()->json(['message' => 'Pengguna berhasil diperbarui', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }
}