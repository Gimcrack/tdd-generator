<?php

namespace Ingenious\TddGenerator\Managers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Stub;
use Ingenious\TddGenerator\Utility\Converter;
use Ingenious\TddGenerator\Utility\ModelCase;
use Ingenious\TddGenerator\StubCollections\AdminStubs;
use Ingenious\TddGenerator\StubCollections\BaseStubs;
use Ingenious\TddGenerator\StubCollections\ParentStubs;
use Ingenious\TddGenerator\StubCollections\SetupStubs;
use Ingenious\TddGenerator\StubCollections\FrontendStubs;

class StubManager {

    /**
     * The StubConverter
     *
     * @var Converter
     */
    public $converter;

    /**
     * The stubs collection
     *
     * @var Collection
     */
    public $stubs;

    /**
     * @var int
     */
    public $count = 0;

    public function __construct($converter = null, $stubs = null)
    {
        $this->converter = $converter ?? new Converter( new Params );

        $this->stubs = $stubs ?? collect();
    }

    /**
     * Setup Stubs manager
     * @method setup
     *
     * @param Params $params
     * @return static
     */
    public static function setup(Params $params)
    {
        return new static( new Converter($params), SetupStubs::get() );
    }

    /**
     * Base Stubs manager
     * @method base
     *
     * @param Params $params
     * @return static
     */
    public static function base(Params $params)
    {
        $converter = new Converter($params);
        $skip_migration = $converter->migrationExists();

        return new static( $converter, BaseStubs::get($skip_migration) );
    }

    /**
     * Admin Stub manager
     * @method admin
     *
     * @param Params $params
     * @return static
     */
    public static function admin(Params $params)
    {
        return new static( new Converter($params), AdminStubs::get() );
    }

    /**
     * Parent Stub manager
     * @method parent
     *
     * @param Params $params
     * @return static
     */
    public static function parent(Params $params)
    {
        return new static( new Converter($params), ParentStubs::get() );
    }

    /**
     * Frontend Stub manager
     * @method parent
     *
     * @param Params $params
     * @return static
     */
    public static function frontend(Params $params)
    {
        return new static( new Converter($params), FrontendStubs::get() );
    }

    /**
     * Process the stubs
     * @method process
     *
     * @return   string
     */
    public function process()
    {
        $output = $this->stubs->map( function(Stub $stub) {
            $this->count++;

            return $this->converter->process( $stub );
        })->all();

        $output = array_merge($output, $this->converter->output);

        return implode("\n",$output);
    }

    /**
     * Clean up generated files from previous runs
     * @method cleanUp
     *
     * @return   string
     */
    public function cleanUp()
    {
        $output = [];

        // cleanup migration, if it exists
        $migration = "*_create_{$this->converter->params->model->lower_plural}_table*";

        $files = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . $migration );

        foreach( $files as $file ) {
            $output[] = "Removing old file {$file}... Done.";
            File::delete($file);
        }

        return implode("\n",$output);
        // everything else will be overwritten
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   bool
     */
    public function migrationExists()
    {
        return $this->converter->migrationExists();
    }


}
