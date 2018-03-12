<?php

namespace Ingenious\TddGenerator;

use File;
use Ingenious\TddGenerator\TddStub;
use Ingenious\TddGenerator\TddFrontendStubs;

class TddStubManager {

    /**
     * The StubConverter
     */
    public $converter;

    /**
     * The stubs collection
     */
    public $stubs;

    public $count = 0;

    public function __construct($converter = null, $stubs = null)
    {
        $this->converter = $converter;

        $this->stubs = $stubs;
    }

    /**
     * Base Stubs manager
     * @method base
     *
     * @return   static
     */
    public static function base(TddParams $params)
    {
        $converter = new TddStubConverter($params);
        $skip_migration = $converter->migrationExists();

        return new static( $converter, TddBaseStubs::get($skip_migration) );
    }

    /**
     * Admin Stub manager
     * @method admin
     *
     * @return   static
     */
    public static function admin(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddAdminStubs::get() );
    }

    /**
     * Parent Stub manager
     * @method parent
     *
     * @return   static
     */
    public static function parent(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddParentStubs::get() );
    }

    /**
     * Frontend Stub manager
     * @method parent
     *
     * @return   static
     */
    public static function frontend(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddFrontendStubs::get() );
    }

    /**
     * Process the stubs
     * @method process
     *
     * @return   void
     */
    public function process()
    {
        $output = $this->stubs->map( function(TddStub $stub) {
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
     * @return   void
     */
    public function cleanUp()
    {
        $output = [];

        // cleanup migration, if it exists
        $migration = "*_create_{$this->converter->model->lower_plural}_table*";

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
