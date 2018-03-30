<?php

namespace Ingenious\TddGenerator\Helpers;

use Illuminate\Support\Facades\File;

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
            exec('composer require predis/predis'),
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
        return File::exists(base_path("vendor/predis/predis"));
    }
}
