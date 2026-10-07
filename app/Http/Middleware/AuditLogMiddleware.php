<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        [$userType, $userId] = $this->currentActor();

        $response = $next($request);

        try {
            AuditLog::create([
                'user_type' => $userType,
                'user_id' => $userId,
                'method' => $request->method(),
                'path' => $request->fullUrl(),
                'route_name' => $request->route()?->getName(),
                'module' => $this->moduleFromRoute($request->route()?->getName()),
                'status_code' => $response->getStatusCode(),
                'duration_ms' => round((microtime(true) - $start) * 1000, 2),
                'payload' => $this->sanitizedPayload($request),
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // never let auditing break the request
        }

        return $response;
    }

    /**
     * Determine whether this request should be audited.
     */
    protected function shouldSkip(Request $request): bool
    {
        if ($request->ajax()) {
            return true;
        }

        if ($request->expectsJson()) {
            return true;
        }

        // Fetch requests with Sec-Fetch-Mode cors (e.g. drag/drop uploads) are AJAX-like
        if (strtolower((string) $request->header('Sec-Fetch-Mode')) === 'cors') {
            return true;
        }

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return true;
        }

        // Don't audit the audit-log pages themselves.
        if (str_starts_with((string) $request->route()?->getName(), 'admin.audit_logs')) {
            return true;
        }

        return false;
    }

    /**
     * Resolve the currently authenticated actor before the request runs,
     * so logout requests are attributed to the user who was logged in.
     *
     * @return array{0: string, 1: string|null}
     */
    protected function currentActor(): array
    {
        if (Auth::guard('admin')->check()) {
            return ['admin', Auth::guard('admin')->id()];
        }

        if (Auth::guard('web')->check()) {
            return ['user', Auth::guard('web')->id()];
        }

        return ['guest', null];
    }

    /**
     * Derive a human-readable module from the route name.
     */
    protected function moduleFromRoute(?string $routeName): ?string
    {
        if ($routeName === null || $routeName === '') {
            return null;
        }

        $parts = explode('.', $routeName);

        // common.* / admin.* / user.* get mapped to their resource segment.
        $parts = array_values(array_filter($parts, fn ($part) => !in_array($part, ['admin', 'user', 'common'], true)));

        if (in_array($parts[0] ?? null, ['login', 'login_post', 'logout'], true)) {
            return 'auth';
        }

        if (in_array($parts[0] ?? null, ['home', 'package'], true)) {
            return $parts[0];
        }

        return $parts[0] ?? $routeName;
    }

    /**
     * Sanitize request input so sensitive data and files never reach the log.
     */
    protected function sanitizedPayload(Request $request): ?array
    {
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return null;
        }

        $data = $request->all();

        foreach ($data as $key => $value) {
            if (in_array($key, ['password', 'password_confirmation', '_token', '_method'], true)) {
                $data[$key] = '[FILTERED]';
            } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                $data[$key] = '[FILE: '.$value->getClientOriginalName().']';
            } elseif (is_array($value)) {
                $data[$key] = array_map(function ($item) {
                    if ($item instanceof \Illuminate\Http\UploadedFile) {
                        return '[FILE: '.$item->getClientOriginalName().']';
                    }
                    return $item;
                }, $value);
            }
        }

        return $data;
    }
}