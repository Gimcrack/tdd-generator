<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Helpers\StubCollection;
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
        return StubCollection::tag('setup',[

            // routes
            Stub::route("web"),
            Stub::route("api-admin"),
            Stub::route("api-user"),

            // controller
            Stub::controller("HomeController"),

            // tests
            Stub::make("phpunit",base_path(),".xml"),
            Stub::make("Tests/TestCase",base_path("tests")),

            // echo server
            Stub::make("laravel-echo-server",base_path(),".json"),
        ]);
    }
}
