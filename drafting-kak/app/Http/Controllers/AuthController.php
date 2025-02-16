<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login untuk Web.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file ini ada di resources/views/auth/login.blade.php
    }

    /**
     * Login untuk Web (Menggunakan Session).
     */
    public function webLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return redirect()->route('login')->with('status', 'error');
        }

        $user = JWTAuth::user(); // Ambil user dari JWT

        // ✅ Paksa Laravel menyimpan user ke session
        Auth::guard('web')->login($user, true);

        // ✅ Simpan token JWT ke session
        session(['jwt_token' => $token]);

        // 🔍 Debugging untuk melihat session
        Log::info('✅ User berhasil login via JWT:', ['id' => $user->id, 'role' => $user->role]);
        Log::info('Session setelah login:', session()->all());

        // 🔀 Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($user->role === 'staff') {
            return redirect()->route('user.index');
        } elseif ($user->role === 'supervisor') {
            return redirect()->route('supervisor.index');
        }

        return redirect()->route('login')->with('status', 'error');
    }



    /**
     * Logout untuk Web.
     */
    public function webLogout()
    {
        Auth::logout();
        return redirect()->route('login')->with('status', 'success');
    }

    /**
     * Login untuk API (Menggunakan JWT).
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Tentukan URL redirect berdasarkan role
        $redirect_url = match ($user->role) {
            'admin' => url('/admin/index'),
            'staff' => url('/user/index'),
            'supervisor' => url('/supervisor/index'),
            default => url('/login'),
        };

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'role' => $user->role,
            'redirect_url' => $redirect_url, // Kirim URL redirect ke frontend
        ]);
    }

    /**
     * Mendapatkan data user yang sedang login.
     */
    public function me()
    {
        try {
            return response()->json([
                'user' => Auth::user()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }
    }

    /**
     * Logout untuk API.
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400);
            }

            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout, invalid token'], 400);
        }
    }
}
