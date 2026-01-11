<?php



namespace App\Http;



use Illuminate\Foundation\Http\Kernel as HttpKernel;



class Kernel extends HttpKernel

{

    // ... (Middleware groups tetap sama)



    /**

     * The application's route middleware aliases.

     *

     * @var array<string, class-string>

     */
    // Backwards-compatible route middleware map (some Laravel versions expect this)
    protected $routeMiddleware = [
        'staff' => \App\Http\Middleware\StaffMiddleware::class,
        'staf' => \App\Http\Middleware\StaffMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        'not_super_admin' => \App\Http\Middleware\NotSuperAdminMiddleware::class,
        'demo' => \App\Http\Middleware\DemoModeMiddleware::class,
        'demo_or_auth' => \App\Http\Middleware\DemoOrAuthMiddleware::class,
    ];

    /**
     * The application's route middleware aliases.
     *
     * @var array<string, class-string>
     */
    protected array $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,

        // --- SOLUSI ALTERNATIF EKSTREM UNTUK REQUIRE PASSWORD CONFIRMATION ---
        // (Menggunakan Namespace Session jika Auth/Routing/Foundation Gagal)
        'password.confirm' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        // ----------------------------------------------------------------------

        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // --- ALIAS WAJIB ANDA ---
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'staff' => \App\Http\Middleware\StaffMiddleware::class,
        'staf' => \App\Http\Middleware\StaffMiddleware::class,
        'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        'not_super_admin' => \App\Http\Middleware\NotSuperAdminMiddleware::class,
        'demo' => \App\Http\Middleware\DemoModeMiddleware::class,
        'demo_or_auth' => \App\Http\Middleware\DemoOrAuthMiddleware::class,

        // -------------------------
    ];

}
