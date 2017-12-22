<?php

namespace Ingenious\TddGenerator;

use File;

class TddStubManager {

    /**
     * The StubConverter
     */
    public $converter;

    /**
     * Force overwriting of existing files
     */
    public $force;

    /**
     * The stub path
     */
    public $stub_path;

    public $count = 0;

    /**
     * The stubs collection
     */
    public $stubs;

    public function __construct($converter = null, $force = false, $stubs = null)
    {
        $this->converter = $converter ?? new TddStubConverter;

        $this->force = $force;

        $this->stub_path = __DIR__ . DIRECTORY_SEPARATOR . "stubs";

        $this->stubs = $stubs ?? collect( [
            "Controllers/ThingController" => app_path("Http\Controllers"),
            "Events/ThingWasCreated" => app_path("Events"),
            "Events/ThingWasDestroyed" => app_path("Events"),
            "Events/ThingWasUpdated" => app_path("Events"),
            "Factories/ThingFactory" => database_path("Factories"),
            "Migrations/XXXX_XX_XX_XXXXXX_create_things_table" => database_path("Migrations"),
            "Models/Thing" => app_path(),
            "Requests/NewThingRequest" => app_path("Http\Requests"),
            "Requests/UpdateThingRequest" => app_path("Http\Requests"),
            "Tests/Unit/ThingTest" => base_path("tests\Unit"),
            "Tests/Feature/ThingTest" => base_path("tests\Feature")
        ]);
    }

    /**
     * Admin Stub manager
     * @method admin
     *
     * @return   void
     */
    public static function admin($force = false, $prefix = '')
    {
        $converter = new TddStubConverter(null, $force, $prefix);

        $manager = new static($converter, $force, collect([
            "Routes/api-admin" => base_path("routes"),
            "Routes/api-user" => base_path("routes"),
            "Models/User" => app_path(),
            "Controllers/UserController" => app_path("Http/Controllers"),
            "Controllers/UserPromotionController" => app_path("Http/Controllers"),
            "Middleware/AuthenticateAsAdmin" => app_path("Http/Middleware"),
            "Middleware/Kernel" => app_path("Http"),
            "Migrations/2014_10_12_000000_create_users_table" => database_path("Migrations"),
            "Tests/Unit/UserTest" => base_path("tests/Unit"),
            "Tests/Feature/UserTest" => base_path("tests/Feature"),
            "Factories/UserFactory" => database_path("Factories"),
            "Providers/RouteServiceProvider" => app_path("Providers"),
            "Requests/NewUserRequest" => app_path("Http\Requests"),
            "Requests/UpdateUserRequest" => app_path("Http\Requests"),
            "Events/UserWasCreated" => app_path("Events"),
            "Events/UserWasDestroyed" => app_path("Events"),
            "Events/UserWasUpdated" => app_path("Events"),
        ]));

        return $manager;
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
        $migration = "*_create_{$this->converter->model_lower_plural}_table*";

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

        return "Copying [{$stub_path}] to [{$new_path}]... Done.";
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   void
     */
    public function migrationExists()
    {
        $migration = "*_create_{$this->converter->model_lower_plural}_table*";

        $files = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . $migration );

        return !! count($files);
    }


}
