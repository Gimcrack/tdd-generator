<?php

namespace Ingenious\TddGenerator\Concerns;

use Illuminate\Support\Facades\File;

trait GetsUserInput {

    /**
     * Get the routes file
     * @method getRoutesFile
     *
     * @return   string
     */
    private function getRoutesFile()
    {
        if ( !! $this->argument('routes') )
            return $this->argument('routes');

        $files = File::glob( base_path("routes" . DIRECTORY_SEPARATOR . "*api*.php" ) );

        if ( count($files) == 1 )
            return File::name($files[0]) . ".php";

        if ( $this->option('defaults') ) {
            return File::name( $files[0] ) . ".php";
        }

        $this->comment("\n\nWhat routes files should the new routes be added to?");

        foreach( $files as $key => $file ) {
            $this->comment( " [{$key}] ". File::name($file) . ".php");
        }

        $chosen = $this->ask("> Select one. [0]") ?: 0;

        return File::name( $files[$chosen] ) . ".php";
    }

    /**
     * Get the route prefix
     * @method getPrefix
     *
     * @return   string
     */
    private function getPrefix()
    {
        if ( !! $this->argument('prefix') )
            return $this->argument('prefix');

        if ( $this->option('defaults') )
            return null;

        $this->comment("\n\nWhat prefix should the new routes have? Optional");

        return $this->ask("> Enter a prefix", false);
    }

    /**
     * Force overwriting of existing files?
     * @method getForce
     *
     * @return   bool
     */
    private function getForce()
    {
        if ( !! $this->option('force') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Force overwriting of existing files?", false);
    }

    /**
     * Backup existing files?
     * @method getBackup
     *
     * @return   bool
     */
    private function getBackup()
    {
        if ( !! $this->option('backup') )
            return true;

        if ( !! $this->option('force') )
            return false;

        if ( $this->option('defaults') )
            return true;

        return (bool) $this->ask("> Skip or Backup/Replace existing files? [Skip]", false);
    }

    /**
     * Get the model Parent
     * @method getParent
     *
     * @return   string
     */
    private function getParent()
    {
        if ( !! $this->argument('parent') )
            return $this->argument('parent');

        if ( $this->option('defaults') )
            return null;

        $this->comment("\n\nDoes the model have a parent model? Optional");

        return $this->ask("> Enter a parent. [None]", false);
    }

    /**
     * Admin only routes?
     * @method getForce
     *
     * @return   bool
     */
    private function getAdmin()
    {
        if ( !! $this->option('admin') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Admin only routes? [No]", false);
    }
}
