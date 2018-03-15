<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Stub;

class SetupStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect([
            Stub::make("phpunit",base_path(),".xml"),
            Stub::make("Tests/TestCase",base_path("tests")),
        ]);
    }
}
