<?php

namespace Pliasun\PdfToPng;

use Illuminate\Support\ServiceProvider;

/**
 * Class PdfToPngServiceProvider
 * @package Pliasun\PdfToPng
 */
class PdfToPngServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('PdfToPngClient', function ($app) {
            return new PdfToPngClient();
        });
    }
}
