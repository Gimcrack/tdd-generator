<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Stub;

class BaseStubs {
    /**
     * Get the stubs
     * @method get
     *
     * @param bool $skip_migration
     * @return Collection
     */
    public static function get($skip_migration = false)
    {
        $r = collect( [

            // controllers
            Stub::controller("ThingController"),

            // factories
            Stub::factory("ThingFactory"),

            // models
            Stub::model("Thing"),

            // requests
            Stub::request("NewThingRequest"),
            Stub::request("UpdateThingRequest"),

            // tests
            Stub::test("Unit/ThingTest"),
            Stub::test("Feature/ThingTest"),

            // events
            Stub::event("ThingWasCreated"),
            Stub::event("ThingWasDestroyed"),
            Stub::event("ThingWasUpdated"),

            // vue components
            Stub::component("Thing"),
            Stub::component("Things"),
        ]);

        if ( ! $skip_migration )
            $r[] = Stub::migration("XXXX_XX_XX_XXXXXX_create_things_table");

        return $r;
    }
}
