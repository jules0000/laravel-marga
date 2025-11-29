<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix for MySQL/MariaDB key length issue with utf8mb4
        // Set default string length to 191 to avoid "key too long" errors
        Schema::defaultStringLength(191);
    }
}

