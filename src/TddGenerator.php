<?php

namespace Ingenious\TddGenerator;

class TddGenerator {

    protected $model;

    protected $stub_path;

    protected $stubs;

    public $output;

    public function __construct($model)
    {
        $this->model = $model;

        $this->stub_path = __DIR__ . DIRECTORY_SEPARATOR . "stubs" . DIRECTORY_SEPARATOR;

        $this->stubs = collect( [
            [ "Controllers/ThingController" => app_path("Http\Controllers") ],
            [ "Events/ThingWasCreated" => app_path("Events") ],
            [ "Events/ThingWasDestroyed" => app_path("Events") ],
            [ "Events/ThingWasUpdated" => app_path("Events") ],
            [ "Factories/ThingFactory" => database_path("Factories") ],
            [ "Migrations/XXXX_XX_XX_XXXXXX_create_things_table" => database_path("Migrations")],
            [ "Models/Thing" => app_path() ],
            [ "Requests/NewThingRequest" => app_path("Http\Requests") ],
            [ "Requests/UpdateThingRequest" => app_path("Http\Requests") ],
            [ "Tests/Unit/ThingTest" => base_path("tests\Unit")],
            [ "Tests/Feature/ThingTest" => base_path("tests\Feature")]
        ]);
    }

    /**
     * Description
     * @method handle
     *
     * @return   void
     */
    public static function handle($model, $force = false)
    {
        $generator = new static($model);

        $generator->output = $generator->convertAll($force);

        return $generator;
    }

    /**
     * Convert the stubs
     * @method convert
     *
     * @return   void
     */
    public function convertAll($force = false)
    {
        return $this->stubs->map( function( $path, $stub ) use ($force) {
            return $this->convertStub($stub, $path, $force);
        });
    }

    /**
     * Convert Stub
     * @method convertStub
     *
     * @return   void
     */
    private function convertStub($stub, $path, $force)
    {
        return "Copying {$stub} to {$path}";
    }
}
