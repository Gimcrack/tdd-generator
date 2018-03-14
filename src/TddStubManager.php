<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class TddStubManager {

    /**
     * The StubConverter
     *
     * @var TddStubConverter
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
        $this->converter = $converter ?? new TddStubConverter( new TddParams );

        $this->stubs = $stubs ?? collect();
    }

    /**
     * Setup Stubs manager
     * @method setup
     *
     * @param TddParams $params
     * @return static
     */
    public static function setup(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddSetupStubs::get() );
    }

    /**
     * Base Stubs manager
     * @method base
     *
     * @param TddParams $params
     * @return static
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
     * @param TddParams $params
     * @return static
     */
    public static function admin(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddAdminStubs::get() );
    }

    /**
     * Parent Stub manager
     * @method parent
     *
     * @param TddParams $params
     * @return static
     */
    public static function parent(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddParentStubs::get() );
    }

    /**
     * Frontend Stub manager
     * @method parent
     *
     * @param TddParams $params
     * @return static
     */
    public static function frontend(TddParams $params)
    {
        return new static( new TddStubConverter($params), TddFrontendStubs::get() );
    }

    /**
     * Process the stubs
     * @method process
     *
     * @return   string
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
     * @return   string
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
