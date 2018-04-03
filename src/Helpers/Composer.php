<?php

namespace Ingenious\TddGenerator\Helpers;

class Composer {

    /**
     * Setup the composer dependencies
     * @method setup
     *
     * @return   array
     */
    public static function setup()
    {
        return ( static::checkDeps() ) ? [
            "Composer dependencies in place"
        ] : [
            "Installing composer dependencies",
            shell_exec('composer require predis/predis'),
            shell_exec('composer require --dev barryvdh/laravel-ide-helper')
        ];
    }

    /**
     * Check if the composer deps are in place
     * @method checkDeps
     *
     * @return   bool
     */
    private static function checkDeps()
    {
        return FileSystem::exists(
            base_path("vendor/predis/predis"),
            base_path("vendor/barryvdh/laravel-ide-helper")
        );
    }
}
