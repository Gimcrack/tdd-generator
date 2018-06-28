<?php

namespace Ingenious\TddGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Ingenious\TddGenerator\Commands\TddParent;
use Ingenious\TddGenerator\Commands\TddSetup;
use Ingenious\TddGenerator\Commands\TddGenerate;
use Ingenious\TddGenerator\Commands\TddCleanupBackups;

class TddGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            TddSetup::class,
            TddCleanupBackups::class,
            TddGenerate::class,
            TddParent::class,
        ]);

        $this->loadViewsFrom( base_path('vendor/ingenious/tdd-generator/src/resources/views'), 'tdd-generator');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
