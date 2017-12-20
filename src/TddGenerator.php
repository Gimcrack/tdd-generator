<?php

namespace Ingenious\TddGenerator;

use File;
use Illuminate\Support\Str;

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
     * The routes file
     */
    protected $routes;

    protected $stub_path;

    protected $stubs;

    public $output = [];

    public function __construct($model, $force = false, $routes = "api.php")
    {
        $this->model = $model;

        $this->force = $force;

        $this->routes = $routes;

        $this->stub_path = __DIR__ . DIRECTORY_SEPARATOR . "stubs";

        $this->stubs = collect( [
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
     * Description
     * @method handle
     *
     * @return   void
     */
    public static function handle($model, $force = false, $routes = "api.php")
    {
        $generator = new static($model, $force, $routes);

        $generator->convertAll();

        return $generator;
    }

    /**
     * Convert the stubs
     * @method convert
     *
     * @return   void
     */
    public function convertAll()
    {
        if ( $this->force ) {
            $this->output = $this->cleanUp();
        }

        $this->output[] = $this->handleRoutes();

        $this->output[] = $this->handleTestCase();

        $this->output[] = $this->handlePhpunit();

        $this->stubs->each( function( $path, $stub ) {
            $this->output[] = $this->convertStub($stub, $path);
        });

        return $this;
    }

    /**
     * Convert Stub
     * @method convertStub
     *
     * @return   void
     */
    private function convertStub($stub, $path)
    {
        $output = $this->getNewFilePath($path, $stub);
        $stub_path = $this->getStubPath($stub);

        $stub_content = file_get_contents($stub_path);
        $new_content = $this->convertText($stub_content);

        if ( ! file_exists( dirname( $output ) ) ) {
            mkdir( dirname($output) );
        }

        if ( ! file_put_contents($output, $new_content) ) {
            throw new Exception("Could not write to $output");
        }

        return "Copying [{$stub_path}] to [{$output}]... Done.";
    }

    /**
     * Replace the placeholders in the text
     * @method parse
     *
     * @return   void
     */
    private function convertText($text)
    {
        $capped = Str::title($this->model);
        $capped_plural = Str::title( Str::plural($this->model) );

        $lower = Str::lower($this->model);
        $lower_plural = Str::lower( Str::plural($this->model) );

        $search = [ '[Things]','[things]','[Thing]','[thing]', 'Things', 'things', 'Thing', 'thing', 'XXXX_XX_XX_XXXXXX' ];
        $replace = [ $capped_plural, $lower_plural, $capped, $lower, $capped_plural, $lower_plural, $capped, $lower, date('Y_m_d_His') ];

        return str_replace($search, $replace, $text);
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
     * Get the full path to the stub
     * @method getStubPath
     *
     * @return   string
     */
    private function getStubPath($stub)
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
     * @method getNewFilePath
     *
     * @return   void
     */
    private function getNewFilePath($path, $stub)
    {
        $output = $path
            . DIRECTORY_SEPARATOR
            . $this->convertText( $this->getStubFilename($stub) )
            . ".php";

        if ( file_exists($output) && ! $this->force )
            throw new \Exception($output . " already exists. Try using the force option --f");

        return $output;
    }

    /**
     * Clean up generated files from previous runs
     * @method cleanUp
     *
     * @return   void
     */
    private function cleanUp()
    {
        $output = [];

        // cleanup migration, if it exists
        $lower_plural = Str::lower( Str::plural($this->model) );

        $migration = "*_create_{$lower_plural}_table*";

        $files = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . $migration );

        foreach( $files as $file ) {
            $output[] = "Removing old file {$file}... Done.";
            File::delete($file);
        }

        $files = File::glob( base_path("tests") . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*Example*");

        foreach( $files as $file ) {
            $output[] = "Removing Example Test {$file}... Done.";
            File::delete($file);
        }

        return $output;
        // everything else will be overwritten
    }

    /**
     * Description
     * @method handleRoutes
     *
     * @return   void
     */
    private function handleRoutes()
    {
        $routes = base_path("routes" . DIRECTORY_SEPARATOR . $this->routes);
        $contents = file_get_contents($routes);

        $lower_plural = Str::lower( Str::plural($this->model) );
        $capped = Str::title($this->model);

        $new_route = "Route::apiResource(\"{$lower_plural}\",\"{$capped}Controller\");";

        if  ( strpos($contents, $new_route) !== false )
        {
            return "Routes already exist";
        }

        $new_contents = $contents . "\n". $new_route;
        file_put_contents($routes, $new_contents);

        return "New routes added";
    }

    /**
     * Handle the Base TestCase
     * @method handleTestCase
     *
     * @return   mixed
     */
    private function handleTestCase()
    {
        $new_contents = file_get_contents( $this->getStubPath("Tests/TestCase") );

        $original = base_path("tests/TestCase.php");

        if ( file_exists( $original ) ) {
            $original_contents = file_get_contents($original);

            if ($new_contents == $original_contents) {
                return "Base TestCase already in place.";
            }

            File::move($original, $original . ".bak");
        }

        file_put_contents($original, $new_contents);
        return "Copying Base TestCase... Done.";
    }

    /**
     * Handle the phpunit.xml
     * @method handlePhpunit
     *
     * @return   mixed
     */
    private function handlePhpunit()
    {
        $new_contents = file_get_contents( $this->getStubPath("phpunit") );

        $original = base_path("phpunit.xml");

        if ( file_exists( $original ) ) {
            $original_contents = file_get_contents($original);

            if ($new_contents == $original_contents) {
                return "Base phpunit.xml already in place.";
            }

            File::move($original, $original . ".bak");
        }

        file_put_contents($original, $new_contents);
        return "Copying Base phpunit.xml ... Done.";
    }
}
