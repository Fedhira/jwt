<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;  // Pastikan ini di-import dengan benar

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)  // Tambahkan dependency injection Router
    {
        // Mendaftarkan middleware kustom agar bisa digunakan dalam routes

        // Middleware untuk otentikasi admin
        $router->aliasMiddleware('admin.auth', \App\Http\Middleware\AdminAuthMiddleware::class);

        // Middleware untuk otentikasi user biasa
        $router->aliasMiddleware('user.auth', \App\Http\Middleware\UserAuthMiddleware::class);

        // Middleware untuk otentikasi supervisor
        $router->aliasMiddleware('supervisor.auth', \App\Http\Middleware\SupervisorAuthMiddleware::class);

        // Middleware untuk pengecekan role pengguna
        $router->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
    }
}
