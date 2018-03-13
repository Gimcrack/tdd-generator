<?php

namespace Ingenious\TddGenerator;

use File;

class TddSetupManager {

    /**
     * The StubManager
     */
    protected $stubs;

    public $output = [];

    public $paths;

    public function __construct($stubs = null)
    {
        $this->stubs = $stubs ?? new TddStubManager;

        $this->paths = (object) [
            'example_tests' => base_path("tests") . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*Example*",
            'example_component' => base_path("resources/assets/js/components") . DIRECTORY_SEPARATOR . "*Example*",
            'user_factory' => database_path("Factories") . DIRECTORY_SEPARATOR . "UserFactory*",
            'user_migration' => database_path("Migrations") . DIRECTORY_SEPARATOR . "2014_10_12_000000_create_users_table*",
            'route_service_provider' => app_path("Providers") . DIRECTORY_SEPARATOR . "RouteServiceProvider*",
            'http_kernel' => app_path("Http") . DIRECTORY_SEPARATOR . "Kernel*",
            'user_model' => app_path() . DIRECTORY_SEPARATOR . "User*",
        ];
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

        $setup->output[] = TddFileBackup::backup( $setup->paths->example_tests );

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

        $setup->output[] = collect([
            'user_factory',
            'user_migration',
            'route_service_provider',
            'http_kernel',
            'user_model'
        ])->map( function($key) use ($setup) {
            return TddFileBackup::backup( $setup->paths->$key );
        })->implode("\n");

        return $setup;
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @return   static
     */
    public static function frontend($command = null)
    {
        $setup = new static();

        if ( $command ) $command->comment("Setting up NPM dependencies. This may take a few seconds.");

        $setup->output[] = TddNpmDependencies::install();

        $setup->output[] = TddFileBackup::backup( $setup->paths->example_component );

        return $setup;
    }
}
