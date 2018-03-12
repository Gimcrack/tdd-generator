<?php

namespace Ingenious\TddGenerator;

class TddNpmDependencies {

    /**
     * Install the npm dependencies
     * @method install
     *
     * @return   string
     */
    public static function install()
    {
        return exec('npm i -D ' . implode(" ", static::get() ) );
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
