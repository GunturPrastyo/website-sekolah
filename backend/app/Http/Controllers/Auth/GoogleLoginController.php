<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        return $driver->stateless()->redirect(); 
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        $frontendUrl = config('app.frontend_url');

        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = $driver->stateless()->user();

            // Cari pengguna berdasarkan email.
            $user = User::where('email', $googleUser->getEmail())->first();

            // Jika pengguna dengan email tersebut tidak ditemukan di database, tolak login.
            if (!$user) {
                // Arahkan kembali ke halaman login di frontend dengan pesan error
                return redirect()->to($frontendUrl . '/login?error=email_not_registered');
            }

            // Update/set google_id, avatar, dan pastikan email terverifikasi.
            $user->google_id = $googleUser->getId();
            $user->avatar = $googleUser->getAvatar();
            $user->email_verified_at = $user->email_verified_at ?? now();
            $user->save();

            // Buat token Sanctum untuk user
            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect()->to($frontendUrl . '/auth/callback?token=' . $token . '&role=' . $user->role);
        } catch (\Exception $e) {
            return redirect()->to($frontendUrl . '/login?error=google_auth_failed');
        }
    }
}