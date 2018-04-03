<?php

namespace Ingenious\TddGenerator\StubCollections;

use Ingenious\TddGenerator\Stub;
use Ingenious\TddGenerator\Helpers\StubCollection;

class SetupStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   \Illuminate\Support\Collection
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

            // provider
            Stub::provider("AppServiceProvider"),

            // tests
            Stub::make("phpunit",base_path(),".xml"),
            Stub::make("Tests/TestCase",base_path("tests")),

            // echo server
            Stub::make("laravel-echo-server",base_path(),".json"),
        ]);
    }
}
