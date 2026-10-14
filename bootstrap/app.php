<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuditLogMiddleware;
use App\Http\Middleware\EnsureAdminPermission;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'admin'=>AdminMiddleware::class,
            'auth'=>UserMiddleware::class,
            'force.password.change'=>ForcePasswordChange::class,
            'check.permission'=>EnsureAdminPermission::class,
        ]);
        $middleware->web(append: [
            AuditLogMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
