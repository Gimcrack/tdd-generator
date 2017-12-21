<?php

namespace Ingenious\TddGenerator;

use File;

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
}
