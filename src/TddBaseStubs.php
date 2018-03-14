<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;

class TddBaseStubs {
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
            TddStub::controller("ThingController"),

            // factories
            TddStub::factory("ThingFactory"),

            // models
            TddStub::model("Thing"),

            // requests
            TddStub::request("NewThingRequest"),
            TddStub::request("UpdateThingRequest"),

            // tests
            TddStub::test("Unit/ThingTest"),
            TddStub::test("Feature/ThingTest"),

            // events
            TddStub::event("ThingWasCreated"),
            TddStub::event("ThingWasDestroyed"),
            TddStub::event("ThingWasUpdated"),
        ]);

        if ( ! $skip_migration )
            $r[] = TddStub::migration("XXXX_XX_XX_XXXXXX_create_things_table");

        return $r;
    }
}
