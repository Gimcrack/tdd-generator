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
}
