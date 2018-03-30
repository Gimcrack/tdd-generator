<?php

namespace Ingenious\TddGenerator\Helpers;

class Npm {

    /**
     * Install the npm dependencies
     * @method install
     *
     * @return   array
     */
    public static function install()
    {
        $return = [];
        //if ( ! file_exists( base_path("node_modules") ) )
        $return[] = shell_exec('npm install');

        //if ( ! file_exists( base_path("node_modules/bootstrap-sass") ) )
        $return[] = shell_exec('npm i -D ' . static::get() );

        return $return;
    }

    /**
     * Get the npm dependencies
     * @method get
     *
     * @return   string
     */
    public static function get()
    {
        return collect([
            'bootstrap-sass',
            'bootstrap-vertical-tabs',
            'laravel-echo',
            'sweetalert2',
            'font-awesome',
            'font-awesome-webpack',
            'moment',
            'moment-timezone',
            'sleep-promise',
            'vue-localstorage',
            'tdd-generator-ui',
            //'file:../tdd-generator-ui',
        ])->implode(" ");
    }
}
