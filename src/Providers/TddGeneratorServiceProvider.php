<?php

namespace Ingenious\TddGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Ingenious\TddGenerator\Commands\TddSetup;
use Ingenious\TddGenerator\Commands\TddGenerate;
use Ingenious\TddGenerator\Commands\TddAdminSetup;
use Ingenious\TddGenerator\Commands\TddFrontendSetup;
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
            TddAdminSetup::class,
            TddGenerate::class,
            TddFrontendSetup::class,
        ]);
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
