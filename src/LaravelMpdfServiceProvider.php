<?php

namespace Mccarlosen\LaravelMpdf;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LaravelMpdfServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider
     * 
     * @return void
     */

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pdf.php' => config_path("pdf.php"),
        ], "mpdf-config");
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {    
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pdf.php', 'pdf'
        );

        $this->app->bind('laravel-mpdf', function ($app) {
            return new LaravelMpdfWrapper();
        });
    }

}
