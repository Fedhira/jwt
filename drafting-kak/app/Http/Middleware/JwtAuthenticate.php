<?php

namespace App\Http\Middleware; // Menentukan namespace middleware ini agar bisa digunakan dalam Laravel

use Closure; // Closure digunakan untuk meneruskan request ke middleware berikutnya
use Illuminate\Http\Request; // Menggunakan Request dari Laravel
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth; // Menggunakan JWTAuth untuk autentikasi token JWT
use Symfony\Component\HttpFoundation\Response; // Menggunakan Response untuk pengembalian hasil request

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 🔹 Mengecek apakah request memiliki header "Authorization"
            if (!$request->header('Authorization')) {
                return response()->json(['error' => 'Authorization header not found'], 401);
            }

            // 🔹 Memproses dan mengecek validitas token JWT
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // 🔹 Jika token tidak valid atau terjadi error, kembalikan response dengan kode 401 (Unauthorized)
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // 🔹 Jika token valid, lanjutkan request ke middleware atau controller berikutnya
        return $next($request);
    }
}
