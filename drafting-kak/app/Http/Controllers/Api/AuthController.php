<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JwtToken;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login untuk API menggunakan email dan password.
     */
    public function login(Request $request)
    {
        try {
            // Validasi data yang dikirimkan
            $credentials = $request->only('email', 'password');
            
            // Cek apakah kredensial valid
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Invalid credentials'
                ], 401);
            }

            // Ambil user yang terautentikasi
            $user = JWTAuth::user();

            // Simpan token JWT di tabel jwt_tokens
            JwtToken::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            // Kembalikan response dengan token dan data user
            return response()->json([
                'message' => 'Successfully logged in',
                'token' => $token,
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            // Menangani error jika terjadi kesalahan saat proses
            return response()->json([
                'message' => 'Failed to login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout untuk API, menghancurkan token JWT.
     */
    public function logout()
    {
        // Hancurkan token JWT
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh token JWT.
     */
    public function refresh()
    {
        // Perbarui token JWT
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to refresh token'], 400);
        }
    }

    /**
     * Mendapatkan data pengguna yang sedang terautentikasi.
     */
    public function me()
    {
        // Ambil user yang terautentikasi dari token
        $user = JWTAuth::user();

        // Kembalikan data user dalam response
        return response()->json($user);
    }
}