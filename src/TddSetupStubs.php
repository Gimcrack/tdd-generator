<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\TddStub;

class TddSetupStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   void
     */
    public static function get()
    {
        return collect([
            TddStub::make("phpunit",base_path(),".xml"),
            TddStub::make("Tests/TestCase",base_path("tests")),
        ]);
    }
}
