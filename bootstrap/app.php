<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // API रुटिङ थपियो (यदि आवश्यक छ भने)
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        home: '/dashboard',  // ✅ लगइन पछि ड्यासबोर्डमा पठाउने
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // यहाँ मिडलवेयर रजिस्टर गर्न सक्नुहुन्छ
        // उदाहरण: $middleware->alias(['admin' => \App\Http\Middleware\AdminMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })
    ->create();