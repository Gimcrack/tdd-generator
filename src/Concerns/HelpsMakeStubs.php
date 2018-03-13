<?php

namespace Ingenious\TddGenerator\Concerns;

trait HelpsMakeStubs {

    public static $paths = [
        "assets" => "Resources/assets",
        "js" => "Resources/assets/js",
        "components" => "Resources/assets/js/components",
        "mixins" => "Resources/assets/js/components/mixins",
        "sass" => "Resources/assets/sass",
        "views" => "Resources/views"
    ];

    /**
     * Make a new stub
     * @method make
     *
     * @return   static
     */
    public static function make($name, $path, $type = '.php')
    {
        return new static($name, $path, $type);
    }

    /**
     * Make a new js stub
     * @method js
     *
     * @return   static
     */
    public static function js($name)
    {
        return static::make(
            static::$paths['js'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['js']),
            ".js"
        );
    }

    /**
     * Make a new mixin stub
     * @method mixin
     *
     * @return   static
     */
    public static function mixin($name)
    {
        return static::make(
            static::$paths['mixins'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['mixins']),
            ".js"
        );
    }

    /**
     * Make a new component stub
     * @method component
     *
     * @return   static
     */
    public static function component($name)
    {
        return static::make(
            static::$paths['components'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['components']),
            ".vue"
        );
    }

    /**
     * Make a new sass stub
     * @method sass
     *
     * @return   static
     */
    public static function sass($name)
    {
        return static::make(
            static::$paths['sass'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['sass']),
            ".scss"
        );
    }

    /**
     * Make a new view stub
     * @method view
     *
     * @return   static
     */
    public static function view($name)
    {
        $subdir = trim( dirname($name), "." );

        return static::make(
            static::$paths['views'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['views'] . DIRECTORY_SEPARATOR . $subdir)
        );
    }

    /**
     * Make a new route stub
     * @method route
     *
     * @return   static
     */
    public static function route($name)
    {
        return static::make(
            "Routes" . DIRECTORY_SEPARATOR . $name,
            base_path("routes")
        );
    }

    /**
     * Make a new model stub
     * @method model
     *
     * @return   static
     */
    public static function model($name)
    {
        return static::make(
            "Models" . DIRECTORY_SEPARATOR . $name,
            app_path()
        );
    }

    /**
     * Make a new controller stub
     * @method controller
     *
     * @return   static
     */
    public static function controller($name)
    {
        return static::make(
            "Controllers" . DIRECTORY_SEPARATOR . $name,
            app_path("Http/Controllers")
        );
    }

    /**
     * Make a new middleware stub
     * @method middleware
     *
     * @return   static
     */
    public static function middleware($name)
    {
        return static::make(
            "Middleware" . DIRECTORY_SEPARATOR . $name,
            app_path("Http/Middleware")
        );
    }

    /**
     * Make a new migration stub
     * @method migration
     *
     * @return   static
     */
    public static function migration($name)
    {
        return static::make(
            "Migrations" . DIRECTORY_SEPARATOR . $name,
            database_path("Migrations")
        );
    }

    /**
     * Make a new test stub
     * @method test
     *
     * @return   static
     */
    public static function test($name)
    {
        $subdir = trim( dirname($name), "." );

        return static::make(
            "Tests" . DIRECTORY_SEPARATOR . $name,
            base_path("tests" . DIRECTORY_SEPARATOR . $subdir)
        );
    }

    /**
     * Make a new factory stub
     * @method factory
     *
     * @return   static
     */
    public static function factory($name)
    {
        return static::make(
            "Factories" . DIRECTORY_SEPARATOR . $name,
            database_path("Factories")
        );
    }

    /**
     * Make a new provider stub
     * @method provider
     *
     * @return   static
     */
    public static function provider($name)
    {
        return static::make(
            "Providers" . DIRECTORY_SEPARATOR . $name,
            app_path("Providers")
        );
    }

    /**
     * Make a new request stub
     * @method request
     *
     * @return   static
     */
    public static function request($name)
    {
        return static::make(
            "Requests" . DIRECTORY_SEPARATOR . $name,
            app_path("Http\Requests")
        );
    }

    /**
     * Make a new event stub
     * @method event
     *
     * @return   static
     */
    public static function event($name)
    {
        return static::make(
            "Events" . DIRECTORY_SEPARATOR . $name,
            app_path("Events")
        );
    }
}
