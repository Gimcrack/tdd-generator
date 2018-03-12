<?php

namespace Ingenious\TddGenerator;

use File;
use Ingenious\TddGenerator\TddNpmDependencies;

class TddSetupManager {

    /**
     * The StubManager
     */
    protected $stubs;

    public $output = [];

    public function __construct($stubs = null)
    {
        $this->stubs = $stubs ?? new TddStubManager;
    }

    /**
     * Setup the TDD Generator
     * @method process
     *
     * @return   $this
     */
    public function process()
    {
        $this->output[] = $this->setupPhpunit();

        $this->output[] = $this->setupTestCase();

        $this->output[] = $this->moveExampleTests();

        return $this;
    }

    /**
     * Setup the admin files
     * @method admin
     *
     * @return   $this
     */
    public function admin()
    {
        $this->output[] = $this->setupUserFactory();

        $this->output[] = $this->setupUsersMigration();

        $this->output[] = $this->setupRouteServiceProvider();

        $this->output[] = $this->setupHttpKernel();

        $this->output[] = $this->setupUserModel();

        return $this;
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @return   $this
     */
    public function frontend($command = null)
    {
        if ( $command ) $command->comment("Setting up NPM dependencies. This may take a few seconds.");
        $this->output[] = $this->setupNpmDependencies();

        $this->output[] = $this->moveExampleComponent();

        return $this;
    }

    /**
     * Handle the phpunit.xml
     * @method setupPhpunit
     *
     * @return   mixed
     */
    private function setupPhpunit()
    {
        $new_contents = $this->stubs->getStubContent("phpunit");

        $original = base_path("phpunit.xml");

        if ( file_exists( $original ) ) {
            $original_contents = file_get_contents($original);

            if ($new_contents == $original_contents) {
                return "phpunit.xml already in place.";
            }

            File::move($original, $original . ".bak");
        }

        file_put_contents($original, $new_contents);
        return "Copying phpunit.xml ... Done.";
    }

    /**
     * Handle the Base TestCase
     * @method setupTestCase
     *
     * @return   mixed
     */
    private function setupTestCase()
    {
        $new_contents = $this->stubs->getStubContent("Tests/TestCase");

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
     * Move the example tests
     * @method moveExampleTests
     *
     * @return   void
     */
    private function moveExampleTests()
    {
        $output = [];
        $files = File::glob( base_path("tests") . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*Example*");

        foreach( $files as $file ) {
            $output[] = "Renaming Example Test {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Move the example component
     * @method moveExampleComponent
     *
     * @return   void
     */
    private function moveExampleComponent()
    {
        $output = [];
        $files = File::glob( base_path("resources/assets/js/components") . DIRECTORY_SEPARATOR . "*Example*");

        foreach( $files as $file ) {
            $output[] = "Renaming Example Component {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the UserFactory
     * @method setupUserFactory
     *
     * @return   void
     */
    private function setupUserFactory()
    {
        $output = [];
        $files = File::glob( database_path("Factories") . DIRECTORY_SEPARATOR . "UserFactory*");

        foreach( $files as $file ) {
            $output[] = "Renaming {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the UsersMigration
     * @method setupUsersMigration
     *
     * @return   void
     */
    private function setupUsersMigration()
    {
        $output = [];
        $files = File::glob( database_path("Migrations") . DIRECTORY_SEPARATOR . "2014_10_12_000000_create_users_table*");

        foreach( $files as $file ) {
            $output[] = "Renaming {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the Route Service Provider
     * @method setupRouteServiceProvider
     *
     * @return   void
     */
    private function setupRouteServiceProvider()
    {
        $output = [];
        $files = File::glob( app_path("Providers") . DIRECTORY_SEPARATOR . "RouteServiceProvider*");

        foreach( $files as $file ) {
            $output[] = "Renaming {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the Http Kernel
     * @method setupHttpKernel
     *
     * @return   void
     */
    private function setupHttpKernel()
    {
        $output = [];
        $files = File::glob( app_path("Http") . DIRECTORY_SEPARATOR . "Kernel*");

        foreach( $files as $file ) {
            $output[] = "Renaming {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the User model
     * @method setupUserModel
     *
     * @return   void
     */
    private function setupUserModel()
    {
        $output = [];
        $files = File::glob( app_path() . DIRECTORY_SEPARATOR . "User*");

        foreach( $files as $file ) {
            $output[] = "Renaming {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

    /**
     * Setup the NPM dependencies
     * @method setupNpmDependencies
     *
     * @return   string
     */
    private function setupNpmDependencies()
    {
        return TddNpmDependencies::install();
    }
}
