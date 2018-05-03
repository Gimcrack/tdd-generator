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
            'laravel-echo',
            'moment',
            'moment-timezone',
            'sleep-promise',
            'vue-localstorage',
            'tdd-generator-ui',
            //'../tdd-generator-ui',
        ])->implode(" ");
    }

    /**
     * Compile the assets
     * @method compile
     *
     * @return string
     */
    public static function compile()
    {
        $compile_output = shell_exec('npm run dev');

        // run mix again if needed
        if ( strpos($compile_output, "Please run Mix again.") !== false ) {
            $compile_output .= shell_exec('npm run dev');
        }

        return $compile_output;
    }
}
