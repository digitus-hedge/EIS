<?php

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
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
   ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (PostTooLargeException $e, $request) {
            return back()
                ->withInput($request->except([
                    'banner_image',
                    'overview_image',
                    'process',
                    'features',
                ]))
                ->with('error', 'The files you uploaded are too large for the server to accept. Please reduce image/video sizes (images under 10MB, videos under 20MB, total under ~90MB) and try again.');
        });
    })
    ->create();
