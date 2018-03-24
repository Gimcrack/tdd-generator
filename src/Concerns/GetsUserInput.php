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
        if ( isset($this->params->routes) )
            return $this->params->routes;

        if ( !! $this->hasArgument('routes') ) {
            return $this->argument('routes');
        }

        if ( !! $this->option('routes') )
            return $this->option('routes');

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

        $chosen = $this->ask("> Select one. [0]", false) ?: 0;

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
        if ( isset($this->params->prefix) )
            return $this->params->prefix;

        if (  $this->hasArgument('prefix') )
            return $this->argument('prefix');

        if ( !! $this->option('prefix') )
            return $this->option('prefix');

        if ( $this->option('defaults') )
            return null;

        return $this->ask("> What prefix should the new routes have? e.g. admin [none]", false);
    }

    /**
     * Get the tags to include
     * @method getTags
     *
     * @return   string
     */
    private function getTags()
    {
        if ( isset($this->params->tags) )
            return $this->params->tags;

        if ( $this->hasArgument('tags') )
            return $this->argument('tags');

        if ( !! $this->option('tags') )
            return $this->option('tags');

        if ( $this->option('defaults') )
            return 'all';

        return $this->ask("> What tags should be included? Enter tags, comma separated [all]", false);
    }

    /**
     * Force overwriting of existing files?
     * @method getForce
     *
     * @return   bool
     */
    private function getForce()
    {
        if ( isset($this->params->force) )
            return $this->params->force;

        if ( !! $this->option('force') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Force overwriting of existing files? [Don't overwrite]", false);
    }

    /**
     * Backup existing files?
     * @method getBackup
     *
     * @return   bool
     */
    private function getBackup()
    {
        if ( isset($this->params->backup) )
            return $this->params->backup;

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
        if ( isset($this->params->parent) )
            return $this->params->parent;

        if ( $this->hasArgument('parent') )
            return $this->argument('parent');

        if ( !! $this->option('parent') )
            return $this->option('parent');

        if ( $this->option('defaults') )
            return null;

        return $this->ask("> Does the model have a parent model? [None]", false);
    }

    /**
     * Admin only routes?
     * @method getForce
     *
     * @return   bool
     */
    private function getAdmin()
    {
        if ( isset($this->params->admin) )
            return $this->params->admin;

        if ( !! $this->option('admin') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Admin only routes? [No]", false);
    }

    /**
     * Does the argument exist and have a value?
     *
     * @param $arg
     * @return bool
     */
    public function hasArgument($arg)
    {
        return collect($this->arguments())->contains($arg)
            && !! $this->argument($arg);
    }
}
