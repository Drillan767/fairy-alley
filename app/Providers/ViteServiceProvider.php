<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('vite', function(): string
        {
            $html = '';
            $devServerRuns = false;
            if (app()->environment('local')) {
                try {
                    $devServerRuns = file_get_contents(public_path('hot'));
                } catch (Exception) {}

                if ($devServerRuns) {
                    $html = '
                        <script type="module" src="' . env('ASSET_URL') . '/@vite/client"></script>
                        <script type="module" src="' . env('ASSET_URL') . '/resources/js/app.js"></script>
                    ';
                }
            }
            else {
                $manifest = json_decode(file_get_contents(public_path('dist/manifest.json')), true);
                $html = '
                    <script type="module" src="/dist/' . $manifest['resources/js/app.js']['file'] . '"></script>
                    <link rel="stylesheet" href="/dist/' . $manifest['resources/js/app.js']['css'][0] .'">
                ';
            }

            return $html;
        });
    }
}
