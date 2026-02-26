<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

// 1. The Upload Command
Schedule::command('app:upload-contracts')
    ->everyMinute()
    ->withoutOverlapping()
    ->before(function () {
        // Set a flag that this command is running.
        // The '5' is a 5-minute fallback TTL just in case the process crashes
        // heavily (e.g., Out of Memory) so the lock doesn't get stuck forever.
        Cache::put('upload_contracts_running', true, now()->addMinutes(5));
    })
    ->after(function () {
        // Remove the flag when finished (runs on both success and failure)
        Cache::forget('upload_contracts_running');
    });

// 2. The Outstanding Contracts Command
Schedule::command('contracts:check-outstanding')
    ->hourly()
    ->withoutOverlapping()
    ->skip(function () {
        // Skip this hourly run entirely if the upload command's flag exists
        return Cache::has('upload_contracts_running');
    });
