<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('🔍 Session pada user.index:', session()->all());

        // Ambil token JWT dari session
        $jwtSession = session()->get('jwt_token');

        // Cek apakah token JWT ada di session
        if (!$jwtSession) {
            Log::error('❌ User tidak terautentikasi saat mengakses user.index');
            return redirect()->route('login')->with('status', 'error');
        }

        // Ambil user dari JWT token
        try {
            $user = JWTAuth::setToken($jwtSession)->authenticate();
        } catch (JWTException $e) {
            Log::error('❌ Gagal mengambil data user dari JWT:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('status', 'error');
        }

        if (!$user) {
            Log::error('❌ User tidak ditemukan melalui JWT');
            return redirect()->route('login')->with('status', 'error');
        }

        Log::info('✅ User terautentikasi:', ['id' => $user->id, 'role' => $user->role]);

        if ($user->role !== 'admin') {
            Log::error('❌ User bukan admin:', ['id' => $user->id, 'role' => $user->role]);
            return redirect()->route('login')->with('status', 'error');
        }

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
