<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

// 1. The Upload Command
Schedule::command('app:upload-contracts')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->before(function () {})
    ->after(function () {
        Artisan::call('contracts:check-outstanding');
    });
