<?php

namespace Ingenious\TddGenerator\Managers;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Helpers\Npm;

class SetupManager {

    /**
     * The StubManager
     * @var StubManager
     */
    protected $stubs;

    /**
     * @var Collection
     */
    public $output;

    /**
     * @var object
     */
    public $paths;

    /**
     * SetupManager constructor.
     * @param null $stubs
     */
    public function __construct($stubs = null)
    {
        $this->stubs = $stubs ?? new StubManager;

        $this->paths = (object) [
            'example_tests' => base_path("tests") . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*Example*",
            'example_component' => base_path("resources/assets/js/components") . DIRECTORY_SEPARATOR . "*Example*",
            'user_factory' => database_path("Factories") . DIRECTORY_SEPARATOR . "UserFactory*",
            'user_migration' => database_path("Migrations") . DIRECTORY_SEPARATOR . "2014_10_12_000000_create_users_table*",
            'route_service_provider' => app_path("Providers") . DIRECTORY_SEPARATOR . "RouteServiceProvider*",
            'http_kernel' => app_path("Http") . DIRECTORY_SEPARATOR . "Kernel*",
            'user_model' => app_path() . DIRECTORY_SEPARATOR . "User*",
        ];

        $this->output = collect([]);
    }

    /**
     * Setup the TDD Generator
     * @method base
     *
     * @return   static
     */
    public static function base()
    {
        $setup = new static();

        $setup
            ->mergeOutput(
                FileManager::backup( $setup->paths->example_tests ),
                FileManager::env("BROADCAST_DRIVER","redis"),
                FileManager::env("CACHE_DRIVER","redis"),
                FileManager::env("SESSION_DRIVER","redis"),
                FileManager::insert(
                    FileManager::config("app"),
                    "\t'echo_host' => env('ECHO_HOST','tdd-generator-test.test'),\n",
                    18
                )
            );

        return $setup;
    }

    /**
     * Setup the admin files
     * @method admin
     *
     * @return   static
     */
    public static function admin()
    {
        $setup = new static();

        $setup->mergeOutput( collect([
                'user_factory',
                'user_migration',
                'route_service_provider',
                'http_kernel',
                'user_model'
            ])->map( function($key) use ($setup) {
                return FileManager::backup( $setup->paths->$key );
            })->all()
        );

        return $setup;
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @param Command|null $command
     * @return static
     */
    public static function frontend($command = null)
    {
        $setup = new static();

        if ( $command ) $command->comment("Setting up NPM dependencies. This may take a few seconds.");

        $setup->mergeOutput(
            Npm::install(),
            FileManager::backup( $setup->paths->example_component )
        );

        return $setup;
    }

    /**
     * Merge the new output with the existing output
     *
     * @return $this
     */
    public function mergeOutput()
    {
        foreach( func_get_args() as $output) {
            $this->output = $this->output->merge($output);
        }

        return $this;
    }
}
