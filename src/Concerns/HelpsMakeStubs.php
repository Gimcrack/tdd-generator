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
     * @param $name
     * @param $path
     * @param string $type
     * @param array $tags
     * @return static
     */
    public static function make($name, $path, $type = '.php', $tags = [])
    {
        return new static($name, $path, $type, $tags);
    }

    /**
     * Make a new js stub
     * @method js
     *
     * @param $name
     * @return static
     */
    public static function js($name)
    {
        return static::make(
            static::$paths['js'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['js']),
            ".js"
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new mixin stub
     * @method mixin
     *
     * @param $name
     * @return static
     */
    public static function mixin($name)
    {
        return static::make(
            static::$paths['mixins'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['mixins']),
            ".js"
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new component stub
     * @method component
     *
     * @param $name
     * @return static
     */
    public static function component($name)
    {
        return static::make(
            static::$paths['components'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['components']),
            ".vue"
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new sass stub
     * @method sass
     *
     * @param $name
     * @return static
     */
    public static function sass($name)
    {
        return static::make(
            static::$paths['sass'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['sass']),
            ".scss"
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new view stub
     * @method view
     *
     * @param $name
     * @return static
     */
    public static function view($name)
    {
        $subdir = trim( dirname($name), "." );

        return static::make(
            static::$paths['views'] . DIRECTORY_SEPARATOR . $name,
            base_path(static::$paths['views'] . DIRECTORY_SEPARATOR . $subdir)
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new route stub
     * @method route
     *
     * @param $name
     * @return static
     */
    public static function route($name)
    {
        return static::make(
            "Routes" . DIRECTORY_SEPARATOR . $name,
            base_path("routes")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new model stub
     * @method model
     *
     * @param $name
     * @return static
     */
    public static function model($name)
    {
        return static::make(
            "Models" . DIRECTORY_SEPARATOR . $name,
            app_path()
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new controller stub
     * @method controller
     *
     * @param $name
     * @return static
     */
    public static function controller($name)
    {
        return static::make(
            "Controllers" . DIRECTORY_SEPARATOR . $name,
            app_path("Http/Controllers")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new middleware stub
     * @method middleware
     *
     * @param $name
     * @return static
     */
    public static function middleware($name)
    {
        return static::make(
            "Middleware" . DIRECTORY_SEPARATOR . $name,
            app_path("Http/Middleware")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new migration stub
     * @method migration
     *
     * @param $name
     * @return static
     */
    public static function migration($name)
    {
        return static::make(
            "Migrations" . DIRECTORY_SEPARATOR . $name,
            database_path("Migrations")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new test stub
     * @method test
     *
     * @param $name
     * @return static
     */
    public static function test($name)
    {
        $subdir = trim( dirname($name), "." );

        return static::make(
            "Tests" . DIRECTORY_SEPARATOR . $name,
            base_path("tests" . DIRECTORY_SEPARATOR . $subdir)
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new factory stub
     * @method factory
     *
     * @param $name
     * @return static
     */
    public static function factory($name)
    {
        return static::make(
            "Factories" . DIRECTORY_SEPARATOR . $name,
            database_path("Factories")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new provider stub
     * @method provider
     *
     * @param $name
     * @return static
     */
    public static function provider($name)
    {
        return static::make(
            "Providers" . DIRECTORY_SEPARATOR . $name,
            app_path("Providers")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new request stub
     * @method request
     *
     * @param $name
     * @return static
     */
    public static function request($name)
    {
        return static::make(
            "Requests" . DIRECTORY_SEPARATOR . $name,
            app_path("Http/Requests")
        )->tag(__FUNCTION__);
    }

    /**
     * Make a new event stub
     * @method event
     *
     * @param $name
     * @return static
     */
    public static function event($name)
    {
        return static::make(
            "Events" . DIRECTORY_SEPARATOR . $name,
            app_path("Events")
        )->tag(__FUNCTION__);
    }
}
