<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Settings::class, function () {
            return Settings::make(storage_path('app/settings.json'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->environment() !== 'local') {
            URL::forceScheme('https');
        }

        Route::resourceVerbs([
            'create' => 'nouveau',
            'edit' => 'edition',
        ]);
    }
}
