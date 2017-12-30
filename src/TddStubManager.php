<?php

namespace Ingenious\TddGenerator;

use File;

class TddStubManager {

    /**
     * The StubConverter
     */
    public $converter;

    /**
     * The stubs collection
     */
    public $stubs;

    /**
     * The stub path
     */
    public $stub_path = __DIR__ . DIRECTORY_SEPARATOR . "stubs";

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
        return new static( new TddStubConverter($params), TddBaseStubs::get() );
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
     * Process the stubs
     * @method process
     *
     * @return   void
     */
    public function process()
    {
        $output = [];

        foreach( $this->stubs as $stub => $path ) {

            // convert the current stub
            $this->converter->process(
                $this->getStubContent($stub),
                $this->getNewPath($path, $stub)
            );

            $output[] = $this->getConversionMessage($path, $stub);

            $this->count++;
        }

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
     * Get the stub content
     * @method getStubContent
     *
     * @return   string
     */
    public function getStubContent($stub)
    {
        return file_get_contents( $this->getStubPath($stub) );
    }

    /**
     * Get the full path to the stub
     * @method getStubPath
     *
     * @return   string
     */
    public function getStubPath($stub)
    {
        $path = $this->stub_path
            . DIRECTORY_SEPARATOR
            . str_replace(["\\","/"],DIRECTORY_SEPARATOR,$stub)
            . ".stub";

        if ( ! file_exists($path) )
            throw new \Exception("Could not find stub in path " . $path);

        return $path;
    }

    /**
     * Get the path to the new file
     * @method getNewPath
     *
     * @return   void
     */
    private function getNewPath($path, $stub)
    {
        return str_replace(["\\","/"],DIRECTORY_SEPARATOR,$path)
            . DIRECTORY_SEPARATOR
            . $this->converter->interpolate( $this->getStubFilename($stub) )
            . ".php";
    }

    /**
     * Get the stub filename
     * @method getStubFilename
     *
     * @return   string
     */
    private function getStubFilename($stub)
    {
        $parts = explode("/",$stub);

        return array_pop($parts);
    }

    /**
     * Get the conversion message
     * @method getConversionMessage
     *
     * @return   void
     */
    private function getConversionMessage($path, $stub)
    {
        $stub_path = $this->getStubPath($stub);
        $new_path = $this->getNewPath($path, $stub);

        return str_pad( "Creating [" . $this->converter->interpolate( $stub ) . "] ", 75, "-") . "  Done.";
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   void
     */
    public function migrationExists()
    {
        $migration = "*_create_{$this->converter->model->lower_plural}_table*";

        $files = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . $migration );

        return !! count($files);
    }


}
