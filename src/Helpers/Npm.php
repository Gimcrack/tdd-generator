<?php

namespace Ingenious\TddGenerator\Helpers;

class Npm {

    /**
     * Install the npm dependencies
     * @method install
     *
     * @return   string
     */
    public static function install()
    {
        $return = [];
        if ( ! file_exists( base_path("node_modules") ) )
            $return[] = exec('npm install');

        if( ! file_exists( base_path("node_modules/bootstrap-sass") ) )
            $return[] = exec('npm i -D ' . implode(" ", static::get() ) );

        return implode("\n", $return);
    }

    /**
     * Get the npm dependencies
     * @method get
     *
     * @return   array
     */
    public static function get()
    {
        return [
            'bootstrap-sass',
            'bootstrap-vertical-tabs',
            'laravel-echo',
            'sweetalert2',
            'font-awesome',
            'font-awesome-webpack',
            'moment',
            'moment-timezone',
            'sleep-promise',
            'vue-localstorage'
        ];
    }
}
