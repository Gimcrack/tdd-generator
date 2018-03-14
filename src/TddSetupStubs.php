<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;

class TddSetupStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect([
            TddStub::make("phpunit",base_path(),".xml"),
            TddStub::make("Tests/TestCase",base_path("tests")),
        ]);
    }
}
