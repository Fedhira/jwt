<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class UserAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token JWT dari session
        $jwtSession = session()->get('jwt_token');

        // Cek apakah token JWT ada di session
        if (!$jwtSession) {
            Log::error('❌ User tidak terautentikasi');
            return redirect()->route('login')->with('status', 'error');
        }

        // Ambil user dari JWT token
        try {
            $user = JWTAuth::setToken($jwtSession)->authenticate();
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            Log::error('❌ Gagal mengambil data user dari JWT', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('status', 'error');
        }

        if (!$user || $user->role !== 'staff') {
            Log::error('❌ User staff user biasa');
            return redirect()->route('login')->with('status', 'error');
        }

        return $next($request);
    }
}
