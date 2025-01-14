<?php

use App\Http\Middleware\UserPartOfTheAnnualFlow;
use App\Http\Middleware\UserPartOfThePeriodicFlow;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'AnnualFlow' => UserPartOfTheAnnualFlow::class,
            'PeriodicFlow' => UserPartOfThePeriodicFlow::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
