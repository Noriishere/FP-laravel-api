<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DriverLocationThrottle
{
    public function handle(Request $request, Closure $next)
    {
        $driver = auth('api')->user()?->driver;

        if (! $driver) {

            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 403);
        }

        $key = 'driver-location-'.$driver->id;

        if (Cache::has($key)) {

            return response()->json([
                'success' => false,
                'message' => 'Too fast update',
            ], 429);
        }

        Cache::put($key, true, 5);

        return $next($request);
    }
}
