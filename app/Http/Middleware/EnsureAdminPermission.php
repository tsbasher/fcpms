<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (!$routeName || !str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        if (in_array($routeName, config('permissions.always_allowed', []), true)) {
            return $next($request);
        }

        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return $next($request);
        }

        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        $slug = $this->resolvePermission($routeName, $request);
        if ($slug === null) {
            return $next($request);
        }

        if (!$admin->hasPermission($slug)) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * Resolve the required permission slug for the route.
     */
    protected function resolvePermission(string $routeName, Request $request): ?string
    {
        $map = config('permissions.route_map', []);
        if (isset($map[$routeName])) {
            return $map[$routeName];
        }

        $modules = array_keys(config('permissions.modules', []));
        $segments = explode('.', substr($routeName, strlen('admin.')));
        $module = $segments[0];

        if (!in_array($module, $modules, true)) {
            return null;
        }

        $actionMap = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
        ];

        $last = end($segments);
        if (isset($actionMap[$last])) {
            $action = $actionMap[$last];
        } else {
            $action = match ($request->method()) {
                'POST' => 'create',
                'PUT', 'PATCH' => 'edit',
                'DELETE' => 'delete',
                default => 'view',
            };
        }

        return "{$module}.{$action}";
    }
}