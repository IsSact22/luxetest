<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Override;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[Override]
    public function register(): void
    {
        $files = glob(app_path('Helpers').'/*.php');
        foreach ($files as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
