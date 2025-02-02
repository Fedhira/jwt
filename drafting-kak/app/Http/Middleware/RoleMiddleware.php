<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        try {
            // Ambil user dari token JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Jika user tidak ditemukan atau role tidak sesuai, berikan forbidden response
            if (!$user || $user->role !== $role) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
