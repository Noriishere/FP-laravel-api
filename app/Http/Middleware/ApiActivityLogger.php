<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ApiActivityLog;
use Symfony\Component\HttpFoundation\Response;

class ApiActivityLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = (string) Str::uuid();

        $request->headers->set(
            'X-Request-Id',
            $requestId
        );

        $start = microtime(true);

        $response = $next($request);

        $duration = round(
            (microtime(true) - $start) * 1000
        );

        try {

            ApiActivityLog::create([

                'user_id' => auth('api')->id(),

                'request_id' => $requestId,

                'method' => $request->method(),

                'url' => $request->fullUrl(),

                'status_code' => $response->getStatusCode(),

                'duration_ms' => $duration,

                'ip' => $request->ip(),

                'user_agent' => $request->userAgent(),

                'headers' => $this->sanitizeHeaders(
                    $request->headers->all()
                ),

                'request_body' => $this->sanitizeRequest(
                    $request->all()
                ),

                'response_body' => $this->responseBody($response),
            ]);

        } catch (\Throwable $e) {

            report($e);
        }

        $response->headers->set(
            'X-Request-Id',
            $requestId
        );

        return $response;
    }

    protected function sanitizeHeaders(array $headers): array
    {
        unset(
            $headers['authorization'],
            $headers['cookie']
        );

        return $headers;
    }

    protected function sanitizeRequest(array $data): array
    {
        unset(
            $data['password'],
            $data['password_confirmation'],
            $data['token'],
            $data['signature']
        );

        return $data;
    }

    protected function responseBody($response)
    {
        if (
            str_contains(
                $response->headers->get('content-type', ''),
                'application/json'
            )
        ) {
            return json_decode(
                $response->getContent(),
                true
            );
        }

        return null;
    }
}