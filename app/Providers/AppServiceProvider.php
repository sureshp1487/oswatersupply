<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                // Cache settings in memory for 1 hour to prevent DB query on every asset/page hit
                $appSettings = cache()->remember('app_settings_global', 3600, function () {
                    return Setting::all()->pluck('value', 'key')->toArray();
                });
                View::share('appSettings', $appSettings);
            }
        } catch (\Throwable $e) {
            View::share('appSettings', []);
        }
    }
}
