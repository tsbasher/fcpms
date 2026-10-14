<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->must_change_password) {
            $allowed = ['admin.profile.index', 'admin.profile.update', 'admin.logout'];
            if (!in_array($request->route()?->getName(), $allowed)) {
                return redirect()->route('admin.profile.index')
                    ->with('warning', 'Please change your password before continuing.');
            }
        }

        $user = Auth::guard('web')->user();

        if ($user && $user->must_change_password) {
            $allowed = ['user.profile.index', 'user.profile.update', 'user.logout'];
            if (!in_array($request->route()?->getName(), $allowed)) {
                return redirect()->route('user.profile.index')
                    ->with('warning', 'Please change your password before continuing.');
            }
        }

        return $next($request);
    }
}