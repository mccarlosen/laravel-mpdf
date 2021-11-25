<?php

namespace Meneses\LaravelMpdf;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LaravelMpdfServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pdf.php' => config_path("pdf.php"),
            ], "mpdf-config");
        }
    
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pdf.php', 'pdf'
        );

        $this->app->bind('mpdf.wrapper', function ($app) {
            return new LaravelMpdfWrapper();
        });
    }

}
