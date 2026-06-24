<?php

use App\Http\Middleware\ApiActivityLogger;
use App\Http\Middleware\DriverLocationThrottle;
use App\Http\Middleware\RoleMiddleware;
use App\Models\ApiCrashLog;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'driver.location.throttle' => DriverLocationThrottle::class,
        ]);
        $middleware->api(append: [
            ApiActivityLogger::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {

        $exceptions->report(function (Throwable $e) {

            if (! request()->is('api/*')) {
                return;
            }

            try {

                ApiCrashLog::create([

                    'user_id' => auth('api')->id(),

                    'request_id' => request()->header('X-Request-Id'),

                    'method' => request()?->method(),

                    'url' => request()?->fullUrl(),

                    'status_code' => method_exists($e, 'getStatusCode')
                        ? $e->getStatusCode()
                        : 500,

                    'message' => $e->getMessage(),

                    'trace' => $e->getTraceAsString(),

                    'request_body' => request()?->except([
                        'password',
                        'password_confirmation',
                        'token',
                        'signature',
                    ]),

                    'ip' => request()?->ip(),

                    'user_agent' => request()?->userAgent(),
                ]);

            } catch (Throwable $logException) {

                report($logException);
            }
        });

    })->create();
