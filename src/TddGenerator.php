<?php

namespace Ingenious\TddGenerator;

use File;

class TddGenerator {

    /**
     * The new model name to create
     */
    protected $model;

    /**
     * Force overwriting of existing files
     */
    protected $force;

    /**
     * The routes helper
     */
    protected $routes;

    /**
     * The Stub manager
     */
    protected $manager;

    public $output = [];

    public function __construct($model = null, $force = false, $routes = "api.php")
    {
        $this->model = $model;

        $this->force = $force;

        $converter = new TddStubConverter($model);

        $this->stubs = new TddStubManager($converter, $force);

        $this->routes = new TddRoutesManager($converter, $routes);
    }

    /**
     * Description
     * @method handle
     *
     * @return   void
     */
    public static function handle($model, $force = false, $routes = "api.php")
    {
        $generator = new static($model, $force, $routes);

        $generator
            ->init()
            ->process();

        return $generator;
    }

    /**
     * Initialize the environment
     * @method init
     *
     * @return   $this
     */
    public function init()
    {
        if ( ! $this->force() && $this->stubs->migrationExists() )
            throw new Exception("Migration found for {$this->model} table. If you wish to overwrite it, try the command again with the --force option.");

        if ( $this->force )
            $this->output[] = $this->stubs->cleanUp();

        return $this;
    }

    /**
     * Convert the stubs
     * @method convert
     *
     * @return   void
     */
    public function process()
    {
        $this->output[] = $this->routes->process();

        $this->output[] = $this->stubs->process();

        return $this;
    }
}
