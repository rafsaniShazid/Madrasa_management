<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Schedule daily meal creation at 6:00 AM every day
        $schedule->command('meals:create-daily')
                ->dailyAt('06:00')
                ->description('Create daily meal records for all active students');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
